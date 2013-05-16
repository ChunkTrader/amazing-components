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
if (!$regSistema->getValor('privilegios')['verHome']){
	$regSistema->setValor('acceso_denegado', 'principal');
	header('Location: error.php');
	exit;
}

$cats = new Categorias($controlador);


// Titulo por defecto de la página
$regMem->setValor('titulo', 'Mi cuenta');

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

	<div class="separacion">
		<h2>Mi perfil</h2>
		<p class="separacion centrado">Esta es la página principal del usuario.</p>
		<p class="separacion centrado">Por el momento está vacía.</p>

		<h2 class="separacion">Mis pedidos</h2>


	</div>
</div>

<?php
include 'pie.php';
?>

</body>
</html>
