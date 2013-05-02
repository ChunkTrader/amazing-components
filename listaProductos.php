		<?php
		// Dibujar arbol
		$a = $cats->getChildCategoriasById(0);
		$columnas = 3;
		$contador = 0;
		$contador_max = ceil(($cats->getTotal()+$prods->getTotal())/$columnas);

		foreach ( $a as $cat ) {			

		// Principal: categoria null:
			if ($cat->getParentId() == null) {
				if ($contador==0) {
					echo "<div class=\"categoria\">";
				}
				$contador+=1;
				echo "<ul><li><a href=\"editCategorias.php?id={$cat->getId()}\" title=\"Editar: {$cat->getNombre()}\">{$cat->getNombre()}</a>";
				// añadimos el icono de eliminar
				echo "<a href=\"delCategorias.php?id={$cat->getId()}\" title=\"Eliminar: {$cat->getNombre()}\"><img src=\"images/icon_delete.gif\"/></a>";
				echo "<ul>";


				// 	Buscamos sus hijos
				$children = $cats->getChildCategoriasById($cat->getId());

				foreach ($children as $child) {
					if ($contador==0) {
						echo "<div class=\"categoria\"><ul><li><ul>";
					}
					$contador+=1;
					echo "<li>{$child->getNombre()}";
					// Añadimos los productos

					echo "<ul>";
					$productos = $prods->getProductosByCategoriaId($child->getId());
					foreach ($productos as $producto) {
						if ($contador==0) {
							echo "<div class=\"categoria\"><ul><li><ul><li><ul>";
						}
						$contador+=1;
												
						echo "<li><a href=\"newProductos.php?id={$producto->getId()}&accion=Editar\" title=\"Editar: {$producto->getNombre()}\">{$producto->getNombre()}</a>";
						// Añadimos el icono de eliminar
						echo "<a href=\"newProductos.php?id={$producto->getId()}&accion=Eliminar\" title=\"Eliminar: {$producto->getNombre()}\"><img src=\"images/icon_delete.gif\"/></a>";
						echo "</li>";

					if ($contador>=$contador_max) {
						echo "</ul></li></ul></li></ul></div>";
						$contador=0;
					}

					}
					echo "</ul>";

					echo "</li>";

					if ($contador>=$contador_max) {
						echo "</ul></li></ul></div>";
						$contador=0;
					}

				}
				echo "</ul></li></ul>";

				if ($contador>=$contador_max) {
					echo "</div>";
					$contador=0;
				}
				
			}
		}


/*
		foreach ( $a as $cat ) {
			// Principal: categoria null:
			if ($cat->getParentId() == null) {

				echo "<div class=\"categoria\"><ul><li>{$cat->getNombre()}";
				echo "<ul>";


				// 		Buscamos sus hijos
				$children = $cats->getChildCategoriasById($cat->getId());

				foreach ($children as $child) {
					echo "<li>{$child->getNombre()}";
					// Añadimos los productos
					echo "<ul>";

					$productos = $prods->getProductosByCategoriaId($child->getId());
					foreach ($productos as $producto) {
						//$nombre = htmlentities($producto->getNombre(), ENT_QUOTES, 'ISO-8859-1');
						
						echo "<li><a href=\"newProductos.php?id={$producto->getId()}&accion=Editar\" title=\"Editar: {$producto->getNombre()}\">{$producto->getNombre()}</a>";
						// Añadimos el icono de eliminar
						echo "<a href=\"newProductos.php?id={$producto->getId()}&accion=Eliminar\" title=\"Eliminar: {$producto->getNombre()}\"><img src=\"images/icon_delete.gif\"/></a>";
						echo "</li>";
					}
					echo "</ul>";

					echo "</li>";
				}
				echo "</ul></li></ul></div>";
			}
		}
*/
		?>