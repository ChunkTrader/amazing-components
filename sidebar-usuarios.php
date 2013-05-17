<div id="sidebar">
	<ul>
		<li>Mi cuenta
			<ul>
				<?php
				if (isset($regSistema->getValor('privilegios')['verHome'])) {
					echo "<li><a href=\"usuarioPrincipal.php?ver=Mi+perfil\">Mi perfil</a></li>";
					echo "<li><a href=\"usuarioPrincipal.php?ver=Mis+datos\">Mis datos de envio</a></li>";
					echo "<li><a href=\"usuarioPrincipal.php?ver=Mis+pedidos\">Mis pedidos</a></li>";
				}
				?>				
			</ul>
		</li>

	</ul>
</div>