<?php
require_once 'classes/Item.php';

class Fabricante extends Item {
	function __construct (array $valores){
		static::$lista_propiedades = static::getListaPropiedades();
		$this->propiedades = $valores;
	}

	static function getListaPropiedades(){
		return array (
			'id' => 'int',
			'nombre'=>'string',
			'descripcion'=>'string'
		);
	}
}
?>