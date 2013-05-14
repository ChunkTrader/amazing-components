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

// El controlador de registros almacena un array con acceso a los registros que le añadamos, este
// controlador se pasa a las colecciones al crearlo para que puedan mandar mensajes a la aplicación
$controlador = new Controlador();
$controlador -> setRegistro ('feedback', $regFeedback);
$controlador -> setRegistro ('errores', $regError);
$controlador -> setPDO($PDO);

$cats = new Categorias($controlador);
$usuarios = new Usuarios($controlador);

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
				// Si el usurio existe y la contraseña es correcta regeneramos la sesion y
				// creamos un nuevo token.
				session_regenerate_id();

				$regFeedback->addFeedback ('Has conectado con éxito como <b>'.$usuario->getPropiedad('nombre').'</b>');
				$usuario->setPropiedad('id', $usuario_id);
				$usuario->setToken();
				
				// Almacenamos el token en la base de datos, de momento guardamos todo el usuario entero
				// Esto habria que optimizarlo.
				$usuarios->setItemBD($usuario);

				// Aqui hay que poner el código para almacenar los datos en la session y en las cookies
				$conectado = TRUE;


			} else {
				$regError->setError('general', 'Nombre de usuario o contraseña incorrectos');
			}

		} else {
			$regError->setError('general', 'Debes introducir el nombre de usuario y la contraseña.');
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
	//			SIN CONECTAR
	if (!$conectado) {
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




	}
	?>
	</div>

</div>

<?php
include 'pie.php';
?>

</body>
</html>
