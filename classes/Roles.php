<?php
require_once 'classes/Rol.php';
require_once 'classes/Coleccion.php';

class Roles extends Coleccion{
	protected $tabla;

	public function __construct(Controlador $controlador) {
		$this->controlador = $controlador;
		$this->tabla = 'roles';
		$this->orden = 'nombre';
		$this->miembro = 'Rol';
	}

}
?>