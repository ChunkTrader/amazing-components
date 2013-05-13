<div id="sidebar">
<?php
if (!$cats->getTotal()) {
	$cats->getItemBD();
}

$a = $cats->getItemById();

foreach ( $a as $cat ) {
	if (!$cat->getPropiedad('parent_id')) {
		$url = 'verProductos.php?cat='.$cat->getPropiedad('id');
		echo "<ul><li><a href=\"$url\">{$cat->getPropiedad('nombre')}</a>";

		// Buscamos sus hijos si hay alguna categoria seleccionada 		
		if ($cat->getPropiedad('id')==$regMem->getValor('cat') || $cat->getPropiedad('id')==$regMem->getValor('cat_parent_id')) {
			$children = $cats->getChildItemsById($cat->getPropiedad('id'));
			echo '<ul>';
			foreach ( $children as $child ) {
				$url = 'verProductos.php?cat='.$child->getPropiedad('id');
				if ($child->getPropiedad('id')==$regMem->getValor('cat')){
					echo "<li ><a class=\"seleccionado\" href=\"$url\" title=\"{$child->getPropiedad('nombre')}\">{$child->getPropiedad('nombre')}</a></li>";
				} else {
					echo "<li><a href=\"$url\" title=\"{$child->getPropiedad('nombre')}\">{$child->getPropiedad('nombre')}</a></li>";
				}
			}
			echo '</ul>';
		}
		echo '</li></ul>';
	}
}

?>
</div>