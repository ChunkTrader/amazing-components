<?php
require_once 'Item.php';

abstract class Coleccion {
	protected $coleccion = array ();
	protected $controlador;
	protected $tabla;
	protected $orden;
	protected $miembro;

	abstract public function __construct(Controlador $controlador);

	function addItem(Item $item) {
		$this->coleccion [] = $item;
	}

	function getItemById($id=null) {
		if (!$id) {			
			return $this->coleccion;
		}

		foreach ($this->coleccion as $key=>$item) {
			if ($item->getPropiedad('id')==$id){
				return $this->coleccion [$key];
			}
		}
		return null;
	}

	function delItem($id){
		$item = $this->getItemById($id);
		if (($key = array_search($item, $this->coleccion, TRUE)) !== FALSE ){
			unset($this->coleccion[$key]);
		}
	}

	function getItemBD($id=null) {
		// Si tenemos una $id cargamos solo un elemento
		if ($id) {
			$prepare = 'SELECT * FROM ' . $this->tabla . ' WHERE id = :id';
			$stmt = $this->controlador->getPDO()->prepare($prepare);
			$stmt->execute(array(':id'=>$id));
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

	function addItemBD(Item $item) {
		$propiedades = $item->getPropiedad();
		$aux1 = array();
		$aux2 = array();

		foreach ($propiedades as $propiedad => $valor) {
			$aux1 [$propiedad]= "$propiedad";
			$aux2 [$propiedad]= ":$propiedad";
			$propiedades[":$propiedad"] = $propiedades[$propiedad];
			unset($propiedades[$propiedad]);
		}

		// Eliminamos el id de los valores a actualizar
		unset ($aux1['id']);
		unset ($aux2['id']);

		$prepare = 'INSERT INTO ' . $this->tabla .  ' (' . implode($aux1, ',') . ') VALUES (' . implode($aux2, ',') . ')';
		echo $prepare;
		echo '<br>';
		$stmt = $this->controlador->getPDO()->prepare($prepare);
		$stmt->execute($propiedades);
	}

	function delItemBD($id) {
		$prepare = 'DELETE FROM ' . $this->tabla . ' WHERE id = :id';
		$stmt = $this->controlador->getPDO()->prepare($prepare);
		$stmt->execute ( array (':id' => $id) );
		$this->delItem($id);
	}

	function setItemBD(Item $item) {
		$propiedades = $item->getPropiedad();
		$aux= array();

		foreach ($propiedades as $propiedad => $valor) {
			$aux [$propiedad]= "$propiedad=:$propiedad";
			$propiedades[":$propiedad"] = $propiedades[$propiedad];
			unset($propiedades[$propiedad]);
		}

		// Eliminamos el id de los valores a actualizar
		unset ($aux['id']);

		$prepare = 'UPDATE ' . $this->tabla . ' SET ' . implode($aux, ',') . ' WHERE id=:id';
		$stmt = $this->controlador->getPDO()->prepare($prepare);
		$stmt->execute ($propiedades);

		// Temporal, en lugar de actualizar el objeto en memoria lo eliminamos y añadimos este.
		$this->delItem($item->getPropiedad('id'));
		$this->addItem($item);
	}

	function getTotal(){
		return count($this->coleccion);
	}

	private function sanitize($valor, $tipo){
		// Por el momento no se utiliza el $tipo
		return htmlentities(trim($valor), ENT_QUOTES, 'ISO-8859-1');
	}


}

class Categorias extends Coleccion{
	protected $tabla;

	public function __construct(Controlador $controlador) {
		$this->controlador = $controlador;
		$this->tabla = 'Categorias';
		$this->orden = 'nombre';
		$this->miembro = 'Categoria';
	}

	function getChildItemsById($parent_id) {
		$childItems = array ();
		foreach ( $this->coleccion as $item ) {
			if ($item->getPropiedad('parent_id') == $parent_id) {
				$childItems [] = $item;
			}
		}
		return $childItems;
	}

}

class Fabricantes extends Coleccion{
	protected $tabla;

	public function __construct(Controlador $controlador) {
		$this->controlador = $controlador;
		$this->tabla = 'Fabricantes';
		$this->orden = 'nombre';
		$this->miembro = 'Fabricante';
	}


}

?>