<?php
require_once 'classes/Item.php';

class DatosUsuario extends Item {

	function __construct (array $valores){
		static::$lista_propiedades = static::getListaPropiedades();
		$this->propiedades = $valores;
	}

	static function getListaPropiedades(){
		return array (
			'id' => FILTER_VALIDATE_INT,
			'nombre'=>NULL,
			'apellido'=>NULL,
			'poblacion'=>NULL,
			'direccion'=>NULL,
			'cp'=>FILTER_VALIDATE_INT,
			);
	}

}
?>