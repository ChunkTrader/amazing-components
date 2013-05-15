<?php
require_once 'configuracion.php';
require_once 'conectar_bd.php';

require_once 'classes/Controlador.php';
require_once 'classes/Registro.php';

require_once 'classes/Coleccion.php';
require_once 'classes/Usuarios.php';
require_once 'classes/Roles.php';
require_once 'classes/Privilegios.php';
require_once 'classes/Categorias.php';



$PDO = new PDOConfig ();

// Incializamos los registros
$regMem = RegistroMemoria::instancia();
$regError = RegistroErrores::instancia();
$regFeedback = RegistroFeedback::instancia();
$regSistema = RegistroSistema::instancia();


// El controlador de registros almacena un array con acceso a los registros que le añadamos, este
// controlador se pasa a las colecciones al crearlo para que puedan mandar mensajes a la aplicación
$controlador = new Controlador();
$controlador -> setRegistro ('feedback', $regFeedback);
$controlador -> setRegistro ('errores', $regError);
$controlador -> setPDO($PDO);

// Cargamos la comprobación despues de cargar las demás clases e inicializar los registros

$usuarios = new Usuarios($controlador);
$roles = new Roles($controlador);
$privilegios = new Privilegios($controlador);

require_once 'comprobarUsuario.php';

$cats = new Categorias($controlador);

if (($regSistema->getValor('privilegios')['noRegistrar'])) {
	$regSistema->setValor('acceso_denegado', 'principal');
	header('Location: error.php');
	exit;
} 

$roles->getItemBD();

// Titulo por defecto de la página
$regMem->setValor('titulo', 'Nuevo usuario');

// Cargamos las listas


if ($regMem->getValor('accion')=='Registrarse') {
	$correcto = TRUE;

	// Comprobamos si ya existe el usuario:
	$usuario = $usuarios->getUsuarioByNombreBD($regMem->getValor('nombre'))->getItemById();

	// Comenzamos a validar los datos
	if ($usuario){
		$regError->setError('nombre', 'Ya existe un usuario con ese nombre');
		$correcto=FALSE;
	} else if (strlen($regMem->getValor('nombre'))<5) {
		$regError->setError('nombre', 'El nombre debe tener al menos 5 caracteres.');
		$correcto=FALSE;
	} else if (strlen($regMem->getValor('nombre'))>40) {
		$regError->setError('nombre', 'El nombre no puede tener más de 40 caracteres.');
		$correcto=FALSE;
	}

	if ($regMem->getValor('password2')!=$regMem->getValor('password1')){
		$regError->setError('password', 'Las contraseñas no coinciden.');
		$correcto=FALSE;
	} else if (strlen($regMem->getValor('password1'))<5){
		$regError->setError('password', 'La contraseña debe tener al menos 5 caracteres.');
		$correcto=FALSE;
	} else if (strlen($regMem->getValor('password1'))>40) {
		$regError->setError('password', 'La contraseña debe ser como máximo de 40 caracteres.');
		$correcto=FALSE;
	}

	if ($correcto) {
		$valores = array (
			'nombre' => $regMem->getValor('nombre'),
			'password' => SHA1($regMem->getValor('password1'))
		);
		$usuario = new Usuario($valores);
		$usuarios->addItemBD($usuario);
		
		// Recuperamos el usuario de la base de datos para obtener la id
		$usuario = $usuarios->getUsuarioByNombreBD($usuario->getPropiedad('nombre'))->getItemByNombre($usuario->getPropiedad('nombre'));

		// Todos los usuarios creados tienen asignado el rol usuario automáticamente
		$usuario->setRol('Usuario');
		$usuarios->setRolesBD($usuario, $roles);

		$regFeedback->addFeedback ('Te has registrado con éxito como <b>' . $regMem->getValor('nombre') . '</b>');

		// Codigo casi identico al de  de login.php
		session_regenerate_id();
		$usuario->setToken();
					
		// Almacenamos el token en la base de datos, de momento guardamos todo el usuario entero
		// Esto habria que optimizarlo.				
		$usuarios->setItemBD($usuario);

		// Aqui hay que poner el código para almacenar los datos en la session y en las cookies
		$regSistema->setValor('autenticado', TRUE);
		$regSistema->setValor('nombre', $usuario->getPropiedad('nombre'));
		$regSistema->setValor('id', $usuario->getPropiedad('id'));

		// Recuperamos los roles y los privilegios
		$usuarios->getRolesBD($usuario);

		// Obtenemos la lista de privilegios del usuario
		$usuarios->getPrivilegiosUsuarioBD($usuario);
					
		// Guardamos los privilegios en la sesion
		$regSistema->setValor('privilegios', $usuario->getPrivilegios());


	} else {
		$regError->setError('general', 'No ha podido realizarse el registro.');
	}
}


include 'cabecera.php';
?>

<div id="main">

<?php
include 'top-menu.php';
include 'main-menu.php';
include 'sidebar-categorias.php';
?>

<div id="main-content">
	<h2><?=$regMem->getValor('titulo')?></h2>
	<div class="separacion">
		<?php
		if ($regError->getError()) {
			$a = $regError->getError();
			foreach ($a as $error) {
				echo "<p class=\"error centrado\">{$error}</p>";
			}
		}
		if ($regFeedback->getFeedback()) {
			$a = $regFeedback->getFeedback();
			foreach ($a as $feed) {
				echo "<p class=\"centrado\">{$feed}</p>";
			}
		}
		?>
	</div>

	<?php 
	if ($regMem->getValor('accion')=='Registrarse' && $correcto){
		echo "<p class=\"separacion centrado\">¡Bienvenido <b>{$usuario->getPropiedad('nombre')}</b>!";
		echo "<p class=\"separacion centrado\"><a href=\"index.php\">Página principal</a></p>";

	?>
	
	<?php
	} else  {
		?>
	<div class="separacion">
			<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="POST">
				<label>Nombre: </label>
				<input type="text" name="nombre"/>
				
				<label>e-mail: </label>
				<input type="text" name="email" disabled/>

				<label>Password: </label>
				<input type="password" name="password1"/>
				
				<label>Repite pass: </label>
				<input type="password" name="password2"/>
				
				<input type="hidden" name="ver" value="<?=$regMem->getValor('ver')?>"/>
				
				<p class="centrado">
					<input type="submit" name="accion" value="Registrarse"/>
				</p>
			</form>
		</div>
	<?php
	}

?>

</div>

<?php
include 'pie.php';
?>

</body>
</html>
