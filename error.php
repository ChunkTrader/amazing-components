<?php
require_once 'configuracion.php';
require_once 'conectar_bd.php';

require_once 'classes/Controlador.php';
require_once 'classes/Registro.php';

require_once 'classes/Categorias.php';
require_once 'classes/Coleccion.php';

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

require_once 'comprobarUsuario.php';

$cats = new Categorias($controlador);

$regMem->setValor('titulo', 'Error');

include 'cabecera.php';
?>
<div id="main">

	<?php
	include 'top-menu.php';
	include 'main-menu.php';
	switch ($regSistema->getValor('acceso_denegado')) {
		case 'administrar':
			include 'sidebar-administrar.php';
			break;

		default:
			include 'sidebar-categorias.php';
			break;
	}

	// Reiniciamos el valor del error de acceso.
	$regSistema->setValor('acceso_denegado', NULL);
	?>

	<div id="main-content">
		<h2>Error</h2>
		<h3 class="separacion">No tienes permisos para acceder a esta página.</h3>
		<p class="separacion centrado"><a href="index.php">Página principal</a></p>
	</div>

	<?php
	include 'pie.php';
	?>

</body>
</html>
