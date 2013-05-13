<?php
require_once 'classes/Item.php';

class Oferta extends Item {
	function __construct (array $valores){
		static::$lista_propiedades = static::getListaPropiedades();
		$this->propiedades = $valores;
	}

	static function getListaPropiedades(){
		return array (
			'id' => FILTER_VALIDATE_INT,
			'producto_id' => FILTER_VALIDATE_INT,
			'precio_anterior'=>FILTER_VALIDATE_FLOAT,
			'precio_oferta'=>FILTER_VALIDATE_FLOAT,
			'activa'=>FILTER_VALIDATE_BOOLEAN
			);
	}

}