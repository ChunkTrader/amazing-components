<?php
require_once 'classes/Item.php';

class Rol extends Item {
	function __construct (array $valores){
		static::$lista_propiedades = static::getListaPropiedades();
		$this->propiedades = $valores;
	}

	static function getListaPropiedades(){
		return array (
			'id' => FILTER_VALIDATE_INT,
			'nombre'=>NULL,
			'activo'=>FILTER_VALIDATE_BOOLEAN,
			);
	}

}
?>