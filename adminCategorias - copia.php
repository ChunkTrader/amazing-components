<?php
require_once 'inicializacion.php';

// Comprobamos si tiene privilegio de acceso a la página si es necesario

/* 
if (!$regSistema->getValor('privilegios')[' PRIVILEGIO DE ACCESO ']){
	$regSistema->setValor('acceso_denegado', 'principal');
	header('Location: error.php');
	exit;
}
*/

// Titulo por defecto de la página
$regMem->setValor('titulo', 'Plantilla');


include 'cabecera.php';
?>

<div id="main">

	<?php
	include 'top-menu.php';
	include 'main-menu.php';

	/// Cargamos la sidebar que nos interese
	include 'sidebar-categorias.php';
	//include 'sidebar-usuarios.php';
	//include 'sidebar-administrar.php';

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
			<p>Aqui va el contenido de la página.</p>
		</div>

	</div>

	<?php
	include 'pie.php';
	?>

</body>
</html>
