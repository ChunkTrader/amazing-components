<?php

abstract class Item {
	protected $propiedades=array();
	protected static $lista_propiedades = array();

	abstract function __construct(array $valores);

	function getPropiedad($clave=null) {
		if (!$clave) {
			return $this->propiedades;
		} else {
			if (array_key_exists($clave, self::$lista_propiedades)) {
				return $this->propiedades[$clave];
			} else {
				throw new Exception("{$clave} no es una propiedad válida.");
			}
		}
		return null;		
	}

	function setPropiedad($clave, $valor) {
		if (array_key_exists($clave, $self::$lista_propiedades)) {
			$this->propiedades[$clave] = $valor;
		} else {
			throw new Exception("{$clave} no es una propiedad válida.");
		}
	}

	static function getListaPropiedades() {
		return static::$lista_propiedades;
	}

}

