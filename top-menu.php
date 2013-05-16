<div id="top-menu">
	<ul>
		<?php
		if (isset($regSistema->getValor('privilegios')['verHome'])) {
			echo "<li><a href=\"usuarioPrincipal.php\">Mi cuenta</a></li>";
		}

		if (isset($regSistema->getValor('privilegios')['verAdminPrincipal'])) {
			echo "<li><a href=\"adminPrincipal.php\">Administración</a></li>";
		}

		?>
		

	</ul>
</div>