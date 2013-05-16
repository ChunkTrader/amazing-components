<?php
require_once 'classes/Item.php';

class LineaPedido extends Item {
	function __construct (array $valores){
		static::$lista_propiedades = static::getListaPropiedades();
		$this->propiedades = $valores;
	}

	static function getListaPropiedades(){
		return array (
			'id' => FILTER_VALIDATE_INT,
			'pedido_id' => FILTER_VALIDATE_INT,
			'producto_id'=> FILTER_VALIDATE_INT,
			'cantidad'=> FILTER_VALIDATE_INT,
			'precio'=>FILTER_VALIDATE_FLOAT,
			);
	}

}