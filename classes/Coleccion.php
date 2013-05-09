<?php
require_once 'classes/Item.php';

abstract class Coleccion {
	protected $coleccion = array ();
	protected $controlador;
	protected $tabla;
	protected $orden;
	protected $miembro;
	protected $totalBD;

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


	function getItemBD(array $opciones=null) {
		// Si tenemos una $id cargamos solo un elemento
		if ($opciones['id']) {
			$prepare = 'SELECT * FROM ' . $this->tabla . ' WHERE id = :id';
			$stmt = $this->controlador->getPDO()->prepare($prepare);
			$stmt->execute(array(':id'=>$opciones['id']));
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

	function getTotalBD() {
		return $this->totalBD;
	}

	protected function sanitize($valor, $tipo){
		// Por el momento no se utiliza el $tipo
		return htmlentities(trim($valor), ENT_QUOTES, 'ISO-8859-1');
	}

}
?>