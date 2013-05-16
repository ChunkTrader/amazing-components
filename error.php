<?php
require_once 'inicializacion.php';

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

	?>

	<div id="main-content">
		<h2>Error</h2>
		<h3 class="separacion">No tienes permisos para acceder a esta página.</h3>
		<?php
			if ($regSistema->getValor('acceso_denegado')=='autenticar' && !$regSistema->getValor('autenticado')) {
				echo "<p class=\"separacion centrado\">Por favor, identificate para poder continuar.</p>";
				echo "<p class=\"separacion centrado\"><a href=\"login.php\">Conectar</a></p>";
			}

		?>
		<p class="separacion centrado"><a href="index.php">Página principal</a></p>
	</div>

	<?php
	// Reiniciamos el valor del error de acceso.
	$regSistema->setValor('acceso_denegado', NULL);

	include 'pie.php';
	?>

</body>
</html>
