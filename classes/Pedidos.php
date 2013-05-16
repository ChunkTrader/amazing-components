<?php
require_once 'classes/Pedido.php';
require_once 'classes/Coleccion.php';

class Pedidos extends Coleccion{
	protected $tabla;

	public function __construct(Controlador $controlador) {
		$this->controlador = $controlador;
		$this->tabla = 'pedidos';
		$this->orden = 'fecha';
		$this->miembro = 'Pedido';
	}


	public function getPedidoByRefBD($ref){
		$prepare = "SELECT * FROM {$this->tabla} WHERE ref = :ref";
		$stmt = $this->controlador->getPDO()->prepare($prepare);
		$stmt->execute(array(
				':ref' => $ref
		));

		$rows = $stmt->fetchAll();
		$clase = $this->miembro;
		$propiedades = call_user_func($clase.'::getListaPropiedades');

		foreach ($rows as $row){
			$campos = array();
			foreach ($propiedades as $propiedad=>$tipo) {
				$campos[$propiedad] = $this->sanitize($row[$propiedad], $tipo);
			}
			$this->addItem(new $clase ($campos));
		}
		return $this;
	}



}
?>