<?php
require_once 'classes/Oferta.php';
require_once 'classes/Coleccion.php';

class Ofertas extends Coleccion{
	protected $tabla;

	public function __construct(Controlador $controlador) {
		$this->controlador = $controlador;
		$this->tabla = 'Ofertas';
		$this->orden = 'id';
		$this->miembro = 'Oferta';
	}

	function addItemBD(Item $item) {
		//Las ofertas siempre estan activas al crearlas
		$item->setPropiedad('activo', 1);
		return parent::addItemBD($item);
	}


	function getItemByCategoria($id){
		$items = array();
		foreach ($this->coleccion as $item) {
			if ($item->getPropiedad('categoria_id') == $id) {
				$items[] = $item;
			}
		}
		return $items;
	}


}
?>