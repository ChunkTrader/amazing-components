<?php
require_once 'Imagen.php';
require_once 'Coleccion.php';

class Imagenes extends coleccion {


	public function __construct(Controlador $controlador) {
		$this->controlador = $controlador;
		$this->tabla = 'galeria_productos';
		$this->orden = '';
		$this->miembro = 'Imagen';
	}

	function getItemByProducto($producto_id) {
		$items = array ();
		foreach ($this->coleccion as $key=>$item) {
			if ($item->getPropiedad('producto_id')==$producto_id) {
				$items [] = $item;
			}
		}
		return $items;
	}

	function getItemBD(array $opciones=null) {
		if (isset($opciones['id'])) {
			$prepare = 'SELECT * FROM ' . $this->tabla . ' WHERE id = :id';
			if (isset($opciones['principal'])){
				$prepare .= ' AND principal = 1';
			}
			$stmt = $this->controlador->getPDO()->prepare($prepare);
			$stmt->execute(array(':id'=>$opciones['id']));
		} else if (isset($opciones['producto_id'])) {
			// Vaciamos la colección antes de cargar la tabla de la base de datos
			$this->coleccion = array();
	
			$prepare = 'SELECT * FROM ' . $this->tabla . ' WHERE producto_id = :producto_id';
			if (isset($opciones['principal'])){
				$prepare .= ' AND principal = 1';
			}
			$stmt = $this->controlador->getPDO()->prepare($prepare);
			$stmt->execute(array(':producto_id'=>$opciones['producto_id']));
		} else {
			// Vaciamos la colección antes de cargar la tabla de la base de datos
			$this->coleccion = array();
			
			$prepare = 'SELECT * FROM ' . $this->tabla;
			if (isset($opciones['principal'])){
				$prepare .= ' WHERE principal = 1';
			}
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
			$aux = new $clase ($campos);
			$this->addItem($aux);
		}
		return $this;
	}

}


?>