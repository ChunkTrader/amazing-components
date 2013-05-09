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

	/*
	*	Devuelve un array con las imagenes que correspondan al producto
	*
	* 	@param $producto_id integer ID del producto
	*	@return array de Item o vacío
	*/
	function getItemByProducto($producto_id) {
		$items = array ();
		foreach ($this->coleccion as $key=>$item) {
			if ($item->getPropiedad('producto_id')==$producto_id) {
				$items [] = $item;
			}
		}
		return $items;
	}

	/*
	*	Devuelve la primera imagen de la colección que corresponda con el producto
	*
	* 	@param $producto_id integer ID del producto
	*	@return Item o null
	*/
	function getItemByProductoFirst($producto_id) {
		$items=$this->getItemByProducto($producto_id);
		foreach ($items as $item) {
			if ($item) {
				return $item;
			}
		}
		return null;
	}

	/*
	*	Carga productos en la colección desde la base de datos
	*
	*	@param $opciones array Si no se especifica carga todos los productos
	*		'id'			Solo la imagen con la id
	*		'principal'		Solo las imagenes con principal = TRUE
	*		'producto_id'	Solo las imagenes del producto
	*/
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