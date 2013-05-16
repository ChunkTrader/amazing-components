<?php
require_once 'classes/LineaPedido.php';
require_once 'classes/Coleccion.php';

class LineasPedido extends Coleccion{
	protected $tabla;

	public function __construct(Controlador $controlador) {
		$this->controlador = $controlador;
		$this->tabla = 'linea_pedido';
		$this->orden = 'id';
		$this->miembro = 'LineaPedido';
	}


}
?>