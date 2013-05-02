<div id="sidebar">
<?php
if (!$cats->getTotal()) {
	$cats->getItemBD();
}

$a = $cats->getItemById();

foreach ( $a as $cat ) {
	if (!$cat->getPropiedad('parent_id')) {
		echo "<ul><li><a href=\"#\">{$cat->getPropiedad('nombre')}</a>";
		// Buscamos sus hijos si hay alguna categoria seleccionada
		if ($cat->getPropiedad('id')==$regMem->getValor('cat')) {
			$children = $cats->getChildItemsById($cat->getPropiedad('id'));
			echo '<ul>';
			foreach ( $children as $child ) {
				echo "<li><a href=\"#\" title=\"{$child->getPropiedad('nombre')}\">{$child->getPropiedad('nombre')}</a></li>";
			}
			echo '</ul>';
		}
		echo '</li></ul>';
	}
}
?>
</div>