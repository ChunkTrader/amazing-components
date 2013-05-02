<?php
class Fabricante {
	private $fabricante_id;
	private $nombre;
	private $descripcion;

	function __construct($nombre, $fabricante_id=null, $descripcion=null){
		$this->fabricante_id = $fabricante_id;
		$this->nombre = $nombre;
		$this->descripcion = $descripcion;
	}

	function getNombre() {
		return $this->nombre;
	}

	function getId() {
		return $this->fabricante_id;
	}

	function getDescripcion() {
		return $this->descripcion;
	}

}

class ListaFabricantes {
	private $fabricantes = array ();
	private $PDO;

	function __construct(PDO $PDO){
		$this->PDO = $PDO;
	}

	function addFabricante(Fabricante $fabricante){
		$this->fabricantes[] = $fabricante;
	}


	function getFabricanteById($fabricante_id=null) {
		if (!$fabricante_id) {
			return $this->fabricantes;
		}

		foreach ($this->fabricantes as $key=>$fabricante) {
			if ($fabricantes->getId()==$fabricante_id){
				return $this->fabricantes [$key];
			}
		}
		return null;
	}

	function getFabricanteBD($fabricante_id=null){
		if ($fabricante_id) {
			$stmt = $this->PDO->prepare ('SELECT * FROM fabricantes WHERE fabricante_id = :fabricante_id');
			$stmt->execute (array (':fabricante_id'=>$fabricante_id));
		} else {
			$stmt = $this->PDO->prepare ('SELECT * FROM fabricantes');
			$stmt->execute ();
		}

		while ($row = $stmt->fetch()) {
			$nombre = $this->sanitize($row ['nombre']);
			$fabricante_id = $this->sanitize($row ['fabricante_id']);
			$descripcion = $this->sanitize($row ['descripcion']);
			$this->addFabricante ( new Fabricante ( $nombre, $fabricante_id, $descripcion) );
		}
	}

	private function sanitize($item){
		return htmlentities(trim($item), ENT_QUOTES, 'ISO-8859-1');
	}

}

?>