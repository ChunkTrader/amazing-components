<?php
require_once 'classes/Registro.php';

abstract class Item {
	protected $propiedades=array();
	protected static $lista_propiedades = array();

	abstract function __construct(array $valores);

	function getPropiedad($clave=null) {
		// Late Binding, usamos static:: en lugar de self::, para referenciar al metodo
		// de los children.

		if (!$clave) {
			return $this->propiedades;
		} else {
			if (array_key_exists($clave, static::getListaPropiedades())) {
				if (!empty($this->propiedades[$clave])) {
					return $this->propiedades[$clave];
				}
				
			} else {
				throw new Exception("{$clave} no es una propiedad válida.");
			}
		}
		return null;		
	}

	function setPropiedad($clave, $valor) {
		if (array_key_exists($clave, static::getListaPropiedades())) {
			$this->propiedades[$clave] = $valor;
		} else {
			throw new Exception("{$clave} no es una propiedad válida.");
		}
	}

	static function getListaPropiedades() {
		return static::$lista_propiedades;
	}


}

?>