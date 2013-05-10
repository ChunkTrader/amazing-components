<?php
require_once 'classes/Item.php';

class Oferta extends Item {
	function __construct (array $valores){
		static::$lista_propiedades = static::getListaPropiedades();
		$this->propiedades = $valores;
	}

	static function getListaPropiedades(){
		return array (
			'id' => 'int',
			'producto_id' =>'int',
			'precio_anterior'=>'double',
			'activa'=>'boolean'
			);
	}

}