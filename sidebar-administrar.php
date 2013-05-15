<div id="sidebar">
	<ul>
		<li>Administrar productos
			<ul>
				<li><a href="adminCategorias.php">Categorias</a></li>			
				<li><a href="adminProductos.php">Productos</a></li>			
			</ul>
		</li>
		<li>Administrar Ofertas
			<ul>
				<li><a href="adminOfertas.php">Ofertas</a></li>			
				<li><a href="#">Banner (no implementado)</a></li>
			</ul>
		</li>
		<li>Administrar Usuarios
			<ul>
				<?php
				if ($regSistema->getValor('privilegios')['verAdminUsuarios']){
					echo "<li><a href=\"adminUsuarios.php?ver=usuarios\">Usuarios</a></li>";
				}

				if ($regSistema->getValor('privilegios')['verAdminRoles']){
					echo "<li><a href=\"adminUsuarios.php?ver=roles\">Roles</a></li>";
				}

				if ($regSistema->getValor('privilegios')['verAdminPrivilegios']){
					echo "<li><a href=\"adminUsuarios.php?ver=privilegios\">Privilegios</a></li>";
				}

				?>
				
				
				
			</ul>
		</li>
	</ul>
</div>