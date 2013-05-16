<?php
require_once 'classes/LineaPedido.php';
require_once 'classes/Coleccion.php';

class Pedidos extends Coleccion{
	protected $tabla;

	public function __construct(Controlador $controlador) {
		$this->controlador = $controlador;
		$this->tabla = 'pedidos';
		$this->orden = 'fecha';
		$this->miembro = 'LineaPedido';
	}


}
?>