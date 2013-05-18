<?php
require_once 'classes/Categoria.php';
require_once 'classes/Coleccion.php';

class Categorias extends Coleccion{
	protected $tabla;

	public function __construct(Controlador $controlador) {
		$this->controlador = $controlador;
		$this->tabla = 'categorias';
		$this->orden = 'nombre';
		$this->miembro = 'Categoria';
	}

	function getChildItemsById($parent_id) {
		$childItems = array ();
		foreach ( $this->coleccion as $item ) {
			if ($item->getPropiedad('parent_id') == $parent_id) {
				$childItems [] = $item;
			}
		}
		return $childItems;
	}
}
