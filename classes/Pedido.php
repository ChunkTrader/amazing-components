<?php
require_once 'classes/Item.php';

class Pedido extends Item {
	function __construct (array $valores){
		static::$lista_propiedades = static::getListaPropiedades();
		$this->propiedades = $valores;
	}

	static function getListaPropiedades(){
		return array (
			'id' => FILTER_VALIDATE_INT,
			'usuario_id' => FILTER_VALIDATE_INT,
			'fecha'=> NULL,
			'estado'=> NULL,
			'ref'=> NULL
			);
	}

}
