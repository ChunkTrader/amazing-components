<?php
require_once 'classes/Producto.php';
require_once 'classes/Coleccion.php';

class Productos extends Coleccion{
	protected $tabla;

	public function __construct(Controlador $controlador) {
		$this->controlador = $controlador;
		$this->tabla = 'Productos';
		$this->orden = 'nombre';
		$this->miembro = 'Producto';
	}

	function setItemBD(Item $item) {
		$item->calcDisponibilidad();
		return parent::setItemBD($item);
	}

	function addItemBD(Item $item) {
		$item->calcDisponibilidad();
		return parent::addItemBD($item);
	}


	function getItemByCategoria($id){
		$items = array();
		foreach ($this->coleccion as $item) {
			if ($item->getPropiedad('categoria_id') == $id) {
				$items[] = $item;
			}
		}
		return $items;
	}

	function getItemBD(array $opciones=null) {
		// Si tenemos una $id cargamos solo un elemento
		if ($opciones['id']) {
			$prepare = 'SELECT * FROM ' . $this->tabla . ' WHERE id = :id';
			$stmt = $this->controlador->getPDO()->prepare($prepare);
			$stmt->execute(array(':id'=>$opciones['id']));
		} else {
			// Vaciamos la colección antes de cargar la tabla de la base de datos
			$this->coleccion = array();

			if ($opciones['novedades']) {
				// Novedades, mostramos los últimso productos añadidos primero.
				$prepare = 'SELECT * FROM ' . $this->tabla . ' ORDER BY fecha DESC LIMIT ' . $opciones['novedades'];
			} else if ($opciones['outlet']) {
				// Outlet, mostramos los productos más antiguos primero.
				$prepare = 'SELECT * FROM ' . $this->tabla . ' WHERE disponibilidad = \'Outlet\' ORDER BY fecha ASC LIMIT ' . $opciones['outlet'];
			} else {
				// Default, los mostramos según lo establecido en el constructor.
				$prepare = 'SELECT * FROM ' . $this->tabla .' ORDER BY ' . $this->orden;	
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
			$aux->calcDisponibilidad();
			$this->addItem($aux);
		}
		return $this;
	}

}
?>