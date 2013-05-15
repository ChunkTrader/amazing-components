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

// Comprobamos si tiene privilegio de acceso a la página
if (!$regSistema->getValor('privilegios')['verAdminPrincipal']){
	$regSistema->setValor('acceso_denegado', 'administrar');
	header('Location: error.php');
	exit;
}

$cats = new Categorias($controlador);


// Titulo por defecto de la página
$regMem->setValor('titulo', 'Administración');

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

	<div class="separacion centrado">
		<p>Esta es la página principal de administración.</p>
		<p>Por el momento está vacía.</p>
	</div>
</div>

<?php
include 'pie.php';
?>

</body>
</html>
