<div id="top-menu">
	<ul>
		<?php
		if (isset($regSistema->getValor('privilegios')['verAdminPrincipal'])) {
			echo "<li><a href=\"adminPrincipal.php\">Administración</a></li>";
		}
		?>
		

	</ul>
</div>