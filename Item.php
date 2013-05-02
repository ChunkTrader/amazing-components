<?php

abstract class Item {
	protected $propiedades=array();
	protected $lista_propiedades = array();

	abstract function __construct(array $valores);

	function getPropiedad($clave) {
		if (in_array($clave, $this->lista_propiedades)) {
			return $this->propiedades[$clave];
		} else {
			throw new Exception("{$clave} no es una propiedad válida.");
		}
		return null;		
	}

	function setPropiedad($clave, $valor) {
		if (in_array($clave, $this->lista_propiedades)) {
			$this->propiedades[$clave] = $valor;
		} else {
			throw new Exception("{$clave} no es una propiedad válida.");
		}
	}

}

