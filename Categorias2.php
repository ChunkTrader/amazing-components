<?php
require_once 'Item.php';

class Categoria extends Item {

	function __construct (array $valores){
		static::$lista_propiedades = static::getListaPropiedades();
		$this->propiedades = $valores;
	}

	static function getListaPropiedades(){
		return array ('id' => 'int', 'parent_id' =>'int', 'nombre'=>'string', 'descripcion'=>'string', 'activa'=>'boolean');
	}

	

}

