<div id="top-menu">
	<ul>
		<?php
		// Este es el primer punto en el que se comprueban los privilegios
		// Y aqu� ya est�n actualizados.
		
		$privilegios=$regSistema->getValor('privilegios');

		if ($privilegios['verHome']) {
			echo "<li><a href=\"usuarioPrincipal.php\">Mi cuenta</a></li>";
		}

		if ($privilegios['verAdminPrincipal']) {
			echo "<li><a href=\"adminPrincipal.php\">Administraci�n</a></li>";
		}

		?>
		

	</ul>
</div>