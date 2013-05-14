<?php
require_once 'classes/Item.php';

class Usuario extends Item {
	function __construct (array $valores){
		static::$lista_propiedades = static::getListaPropiedades();
		$this->propiedades = $valores;
	}

	static function getListaPropiedades(){
		return array (
			'id' => FILTER_VALIDATE_INT,
			'nombre'=>NULL,
			'password'=>NULL,
			'activo'=>FILTER_VALIDATE_BOOLEAN,
			'token'=>NULL,
			);
	}

	public function setToken(){
		// Generamos un token de 40 caracteres.
		$this->setPropiedad('token', sha1(uniqid()));
	}

}
?>