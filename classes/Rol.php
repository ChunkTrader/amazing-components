<?php
require_once 'classes/Item.php';

class Rol extends Item {
	protected $privilegios = array();

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


	public function setPrivilegio($clave){
		//Siempre que se asigna un privilegio es TRUE
		$this->privilegios[$clave] = TRUE;
	}

	/* Devuelve verdadero o false si encuentra o no el privilegio en la lista
	 *
	 *	@param 	$clave 	El nombre del privilegio
	 *	@return Verdadero o falso según si lo encuentra o no.
	 */
	public function getPrivilegio($clave) {
		//Si no existe es devolvemos FALSE
		if (isset($this->privilegios[$clave])) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/* Devuelve la lista completa de privilegios
	 * 
	 * 	@return Devuelve el array con la lista de privilegios
	 */
	public function getPrivilegios() {
		return $this->privilegios;
	}


}
?>