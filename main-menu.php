<div id="main-menu">
	<form action="buscar.php" method="GET">
		<input type="text" name="buscar" value="" size="40"/> <input type="submit" value="Buscar" />
	</form>
	<ul>
		<li><?php
		if (isset($regSistema->getValor('privilegios')['noConectar'])){
			echo "Estas conectado como <b>{$regSistema->getValor('nombre')}</b> ";
			echo "<a href=\"login.php?accion=Desconectar\">(desconectar)</a>";
		} else {
			echo "<a href=\"login.php\">Conectar</a>";
		}


		?></li>
		<li>12 productos (10000 &euro;)</li>
	</ul>
</div>