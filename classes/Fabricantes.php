<?php
require_once 'classes/Fabricante.php';
require_once 'classes/Coleccion.php';

class Fabricantes extends Coleccion{
	protected $tabla;

	public function __construct(Controlador $controlador) {
		$this->controlador = $controlador;
		$this->tabla = 'Fabricantes';
		$this->orden = 'nombre';
		$this->miembro = 'Fabricante';
	}

}
