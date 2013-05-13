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
		// Las ofertas siempre estan desactivadass al crearlas
		// Las activamos al editarlas por pimera vez.
		$item->setPropiedad('activa', 0);
		return parent::addItemBD($item);
	}

	function getItemByProducto($producto_id){
		// Este mètodo es distinot de getItemCategoria() porque devuelve un único producto
		// Cada producto solo puede tener enlazada una oferta.
		foreach ($this->coleccion as $item) {
			//echo "comprobando {$item->getPropiedad('producto_id')} con {$producto_id}<br>";
			if ($item->getPropiedad('producto_id') == $producto_id){
				return $item;
			}
		}
		return null;
	}



}
?>