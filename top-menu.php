<div id="top-menu">
	<ul>
		<?php
		// Este es el primer punto en el que se comprueban los privilegios
		// Y aquí ya están actualizados.
		
		$privilegios=$regSistema->getValor('privilegios');

		if ($privilegios['verHome']) {
			echo "<li><a href=\"usuarioPrincipal.php\">Mi cuenta</a></li>";
		}

		if ($privilegios['verAdminPrincipal']) {
			echo "<li><a href=\"adminPrincipal.php\">Administración</a></li>";
		}

		?>
		

	</ul>
</div>