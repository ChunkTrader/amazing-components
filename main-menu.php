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
			<?php
			$a = $regSistema->getValor('carrito');
			// Comprobamos si existe el carrito
			if ($a) {
				// Calculamos la cantidad de productos, y el precio total
				$total_cantidad = 0;
				$total_precio = 0;
				foreach ($a as $linea) {
					$total_cantidad += $linea['cantidad'];
					$total_precio += ($linea['cantidad']*$linea['precio']);
				}
				// Asignamos la id carrito para usarlo más adelante con Javascript (si da tiempo)
				// El enlace tiene que redirigir a la página para procesar el pedido.
				// En lugar de un enlace usaremos Javascript para dirigir al pedido con onclick.

				echo "<li id=\"carrito\">";
				echo "<b>{$total_cantidad}</b> productos - <b>" . number_format($total_precio) . "&euro;</b>";
				
				// Creamos el detalle del carrito, deberia estar escondido y ser visible con hover.
				// Deberia prepararse al mismo tiempo que los totales para tener que recorrerlo solo una vez

				echo "<ul id=\"carrito_detalle\">";
				
				$size=35;

				foreach ($a as $key=>$linea){
					echo '<li>';
					// Ponemos el thumbnail

					$imagen=$galeria->getItemByProductoFirst($linea['id']);
					if ($imagen) {
						$b = $imagen->getPropiedad('imagen');
					} else {
						$b = 'default';
					}
					$url = "getthumb.php?path=images/products/{$b}.jpeg&size={$size}";

					echo "<img src=\"{$url}\" alt=\"\" />" ;

					// Ponemos la cantidad y el precio
					$precio_total = $linea['cantidad']*$linea['precio'];
					echo "<span>x{$linea['cantidad']}</span>";
					echo "<span>({$precio_total}&euro;)</span>";


					// Ponemos el nombre del producto

					$p = $prods->getItemBD(array(
							'id' => $linea['id']
					))->getItemById($linea['id'])->getPropiedad('nombre');
					
					echo "<b>{$p}</b>";
					echo "</li>";
				}
				echo '</ul>';

				echo '</li>';

				

			} else {
				echo "<li>El carrito está vacío.</li>";
			}

			?>

	</ul>
</div>