<?php
require_once 'configuracion.php';
require_once 'conectar_bd.php';

require_once 'classes/Controlador.php';
require_once 'classes/Registro.php';

require_once 'classes/Coleccion.php';
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

$usuarios = new Usuarios($controlador);

// Cargamos la lista completa de usuarios
$usuarios->getItemBD();

// Titulo por defecto de la página
$regMem->setValor('titulo', 'Añadir usuarios');

//print_r($regMem->getValor());

switch ($regMem->getValor('accion')){
	case 'Cancelar':
		header("Location: {$_SERVER['SCRIPT_NAME']}");
		exit;
		break;

	case 'Añadir':
		$correcto = TRUE;


		// Comenzamos a validar los datos
		if (strlen($regMem->getValor('nombre'))<5) {
			$regError->setError('nombre', 'El nombre debe tener al menos 5 caracteres.');
			$correcto=FALSE;
		} else if ($usuarios->getItemByNombre($regMem->getValor('nombre'))){
			$regError->setError('nombre', 'Ya existe un usuario con ese nombre');
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
					'password' => SHA1($regMem->getValor('nombre'))
				);
			$usuario = new Usuario($valores);
			$usuarios->addItemBD($usuario);

			$regFeedback->addFeedback ('Usuario creado con éxito <b>' . $regMem->getValor('nombre') . ':' . $regMem->getValor('password1') . '</b>');
		} else {
			$regError->setError('general', 'No se ha creado el usuario.');
		}





		break;

	case 'Eliminar':		
		break;

	case 'Editar':
		break;
	}


include 'cabecera.php';
?>

<div id="main">

<?php
include 'top-menu.php';
include 'main-menu.php';
include 'sidebar-administrar.php';
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

		<p class="centrado">
			<input type="submit" name="accion" value="Añadir"/>
		</p>
	</form>



	</div>


	<h2>Lista de usuarios</h2>
	<div class="separacion">
	<?php
	echo "Usuarios en la base de datos: " . $usuarios->getTotal();

	?>	



	</div>

</div>

<?php
include 'pie.php';
?>

</body>
</html>
