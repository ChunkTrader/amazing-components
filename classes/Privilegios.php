<?php
require_once 'classes/Privilegio.php';
require_once 'classes/Coleccion.php';

class Privilegios extends Coleccion{
	protected $tabla;

	public function __construct(Controlador $controlador) {
		$this->controlador = $controlador;
		$this->tabla = 'privilegios';
		$this->orden = 'nombre';
		$this->miembro = 'Privilegio';
	}

	public function getItemByNombre($nombre){
		$a=$this->coleccion;
		foreach ($a as $usuario){
			if ($usuario->getPropiedad('nombre')==$nombre) {
				return $usuario;
			}
		}
		return null;
	}

}
?>