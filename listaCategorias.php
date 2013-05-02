		<?php
		// Dibujar arbol
		$a = $cats->getChildItemsById(0);
		$columnas = 3;
		$contador = 0;
		$contador_max = ceil($cats->getTotal()/$columnas);

		foreach ( $a as $cat ) {			

		// Principal: categoria null:
			if ($cat->getPropiedad('parent_id') == null) {
				if ($contador==0) {
					echo "<div class=\"categoria\">";
				}
				$contador+=1;
				echo "<ul><li><a href=\"adminCategorias.php?accion=Editar&id={$cat->getPropiedad('id')}\" title=\"Editar: {$cat->getPropiedad('nombre')}\">{$cat->getPropiedad('nombre')}</a>";
				// añadimos el icono de eliminar
				echo "<a href=\"adminCategorias.php?accion=Eliminar&id={$cat->getPropiedad('id')}\" title=\"Eliminar: {$cat->getPropiedad('nombre')}\"><img src=\"images/icon_delete.gif\"/></a>";
				echo "<ul>";


				// 	Buscamos sus hijos
					$children = $cats->getChildItemsById($cat->getPropiedad('id'));
				
				foreach ($children as $child) {
					if ($contador==0) {
						echo "<div class=\"categoria\"><ul><li><ul>";
					}
					$contador+=1;
					echo "<li><a href=\"adminCategorias.php?accion=Editar&id={$child->getPropiedad('id')}\" title=\"Editar: {$child->getPropiedad('nombre')}\">{$child->getPropiedad('nombre')}</a>";
					// añadimos el icono de eliminar
					echo "<a href=\"adminCategorias.php?accion=Eliminar&id={$child->getPropiedad('id')}\" title=\"Eliminar: {$child->getPropiedad('nombre')}\"><img src=\"images/icon_delete.gif\"/></a>";
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


/*
			// Principal: categoria null:
			if ($cat->getParentId() == null) {

				echo "<div class=\"categoria\"><ul><li><a href=\"adminCategorias.php?accion=Editar&id={$cat->getId()}\" title=\"Editar: {$cat->getNombre()}\">{$cat->getNombre()}</a>";
				// añadimos el icono de eliminar
				echo "<a href=\"delCategorias.php?id={$cat->getId()}\" title=\"Eliminar: {$cat->getNombre()}\"><img src=\"images/icon_delete.gif\"/></a>";
				echo "<ul>";


				// 		Buscamos sus hijos
				$children = $cats->getChildItemsById($cat->getId());
				echo 'subcategorias='.count($children);
				foreach ($children as $child) {
					echo "<li><a href=\"adminCategorias.php?accion=Editar&id={$child->getId()}\" title=\"Editar: {$child->getNombre()}\">{$child->getNombre()}</a>";
					// añadimos el icono de eliminar
					echo "<a href=\"delCategorias.php?id={$child->getId()}\" title=\"Eliminar: {$child->getNombre()}\"><img src=\"images/icon_delete.gif\"/></a>";
					echo "</li>";
				}
				echo "</ul></li></ul></div>";
			}
*/
		}

		?>