<?php
require_once 'classes/Item.php';

class Usuario extends Item {
	private $privilegios = array();
	private $roles = array();

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

	public function setPrivilegio($clave){
		//Siempre que se asigna un privilegio es TRUE
		$this->privilegios[$clave] = TRUE;
	}

	public function getPrivilegio($clave) {
		//Si no existe es devolvemos FALSE
		if (isset($this->privilegios[$clave])) {
			return TRUE;
		} else {
			return FALSE;
		}
	}


	public function setRol($clave){
		//Siempre que se asigna un privilegio es TRUE
		
		$this->roles[$clave] = TRUE;
	}

	public function getRol($clave) {
		//Si no existe es devolvemos FALSE
		if (isset($this->roles[$clave])) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function getRoles() {
		return $this->roles;
	}

	public function getPrivilegios() {
		return $this->privilegios;
	}



}
?>