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

	function getItemBD(array $opciones=null) {
		// Si tenemos una $id cargamos solo un elemento
		if (isset($opciones['id'])) {
			$prepare = 'SELECT * FROM ' . $this->tabla . ' WHERE id = :id';
			$stmt = $this->controlador->getPDO()->prepare($prepare);
			$stmt->execute(array(':id'=>$opciones['id']));
		} else if (isset($opciones['pedido_id'])) {
			$prepare = 'SELECT * FROM ' . $this->tabla . ' WHERE pedido_id = :pedido_id';
			$stmt = $this->controlador->getPDO()->prepare($prepare);
			$stmt->execute(array(':pedido_id'=>$opciones['pedido_id']));		
		} else {
			// Vaciamos la colección antes de cargar la tabla de la base de datos
			$this->coleccion = array();
			$prepare = 'SELECT * FROM ' . $this->tabla .' ORDER BY ' . $this->orden;
			$stmt = $this->controlador->getPDO()->prepare($prepare);
			$stmt->execute();
		}

		$rows = $stmt->fetchAll();
		// Obtenemos el diccionario de $propiedades 'activo' => boolean, 'nombre' => string, etc.
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