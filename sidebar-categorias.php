<?php
$cats = new ListasCategorias ($PDO);
$cats->getCategoriaBD (  );
$a = $cats->getCategoriaById ();

$seleccionada = empty($_GET['cat'])? null : $_GET['cat'];

foreach ( $a as $cat ) {
	// Principal: categoria null:
	if ($cat->getParentId () == null) {
		echo "<ul><li><a href=\"#\">{$cat->getNombre ()}</a>";
		// Buscamos sus hijos si hay alguna categoria seleccionada
		if ($cat->getId()==$seleccionada) {
			$children = $cats->getChildCategoriasById ( $cat->getId () );
			echo '<ul>';
			foreach ( $children as $child ) {
				echo "<li><a href=\"#\" title=\"{$child->getNombre ()}\">{$child->getNombre ()}</a></li>";
			}
			echo '</ul>';
		}
		echo '</li></ul>';
	}
}

?>