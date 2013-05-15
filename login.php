<?php
require_once 'configuracion.php';
require_once 'conectar_bd.php';

require_once 'classes/Controlador.php';
require_once 'classes/Registro.php';

require_once 'classes/Coleccion.php';
require_once 'classes/Categorias.php';
require_once 'classes/Usuarios.php';



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

$cats = new Categorias($controlador);
$usuarios = new Usuarios($controlador);



// Cargamos la comprobación despues de cargar las demás clases e inicializar los registros
require_once 'comprobarUsuario.php';


// Titulo por defecto de la página
$regMem->setValor('titulo', 'Conectar');


switch ($regMem->getValor('accion')){

	case 'Conectar':
		$conectado = FALSE;

		if ($regMem->getValor('nombre') && $regMem->getValor('password')) {
			$valores = array (
				'nombre' => $regMem->getValor('nombre'),
				'password' => SHA1($regMem->getValor('password'))
			);

			$usuario = new Usuario($valores);
			$usuario_id = $usuarios->matchUsuario($usuario);
			
			//echo "usuario_id :$usuario_id";
			if ($usuario_id) {
				// Recuperamos los datos del usuario y compramos si esta activado
				$usuario=$usuarios->getItemBD(array('id' =>$usuario_id))->getItemById($usuario_id);
				
				if ($usuario->getPropiedad('activo')) {


					// Si el usuraio existe y la contraseña es correcta regeneramos la sesion y
					// creamos un nuevo token.
					session_regenerate_id();

					$regFeedback->addFeedback ('Has conectado con éxito como <b>'.$usuario->getPropiedad('nombre').'</b>');
					$usuario->setPropiedad('id', $usuario_id);
					$usuario->setToken();
					
					// Almacenamos el token en la base de datos, de momento guardamos todo el usuario entero
					// Esto habria que optimizarlo.				
					$usuarios->setItemBD($usuario);

					// Establecemos el usuario conectado
					$usuario=$usuario;

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
					$regError->setError('usuario', 'Este usuario está desactivado, pongase en contacto con un administrador.');

				}

			} else {
				$regError->setError('general', 'Nombre de usuario o contraseña incorrectos');
			}

		} else {
				$regError->setError('general', 'Debes introducir el nombre de usuario y la contraseña.');
		}
		break;

	case 'Desconectar':
		// Cerramos la sesión:
		$regSistema->limpiar();
		$usuario=null;
		$regFeedback->addFeedback('Ahora estas desconectado.');


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
	//			SIN CONECTAR
	if (!$regSistema->getValor('autenticado')) {
	?>
	<div class="separacion">
	<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="POST">
		<label>Nombre: </label>
		<input type="text" name="nombre"/>
		
		<label>Password: </label>
		<input type="password" name="password"/>

		<p class="centrado">
			<input type="submit" name="accion" value="Conectar"/>
		</p>
	</form>
	
	<?php
	// 			CONECTADO CON ÉXITO
	} else {
		echo "<p class=\"separacion centrado\"><a href=\"index.php\">Página principal</a></p>";

	}
	?>
	</div>

</div>

<?php
include 'pie.php';
?>

</body>
</html>
