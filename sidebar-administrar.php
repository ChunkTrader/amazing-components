<div id="sidebar">
	<ul>
		<li>Administrar Productos
			<ul>
				<?php
				if (isset($regSistema->getValor('privilegios')['verAdminCategorias'])) {
					echo "<li><a href=\"adminCategorias.php\">Categorias</a></li>";
				}
				
				if (isset($regSistema->getValor('privilegios')['verAdminProductos'])) {
					echo "<li><a href=\"adminProductos.php\">Productos</a></li>";
				}
				?>
				
			</ul>
		</li>
		<li>Administrar Ofertas
			<ul>
				<?php
					if (isset($regSistema->getValor('privilegios')['verAdminOfertas'])) {
						echo "<li><a href=\"adminOfertas.php\">Ofertas</a></li>";
						echo "<li><a href=\"#\">Banner (no implementado)</a></li>";
					}
				?>
				
			</ul>
		</li>
		<li>Administrar Pedidos
			<ul>
				<?php
					if (isset($regSistema->getValor('privilegios')['verAdminPedidos'])) {
						echo "<li><a href=\"adminPedidos.php\">Ver pedidos</a></li>";
						echo "<li><a href=\"adminPedidos.php?ver=lista+pagados\">Pedidos para enviar</a></li>";
						echo "<li><a href=\"adminPedidos.php?ver=lista+enviados\">Confirmar recepción</a></li>";
					}
				?>
				
			</ul>
		</li>

		<li>Administrar Usuarios
			<ul>
				<?php
				if (isset($regSistema->getValor('privilegios')['verAdminUsuarios'])) {
					echo "<li><a href=\"adminUsuarios.php?ver=usuarios\">Usuarios</a></li>";
				}

				if (isset($regSistema->getValor('privilegios')['verAdminRoles'])) {
					echo "<li><a href=\"adminUsuarios.php?ver=roles\">Roles</a></li>";
				}

				if (isset($regSistema->getValor('privilegios')['verAdminPrivilegios'])){
					echo "<li><a href=\"adminUsuarios.php?ver=privilegios\">Privilegios</a></li>";
				}

				?>
			</ul>			
		</li>
	</ul>
</div>