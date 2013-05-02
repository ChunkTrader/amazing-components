<?php

class ListasCategorias {
	private $categorias = array ();
	private $PDO;

	function __construct($PDO) {
		$this->PDO = $PDO;
	}

	function addCategoria(Categoria $categoria) {
		$this->categorias [] = $categoria;
	}
	
	function getCategoriaById($categoria_id=null) {
		if (!$categoria_id) {
			return $this->categorias;
		}

		foreach ($this->categorias as $key=>$categoria) {
			if ($categoria->getPropiedad('id')==$categoria_id){
				return $this->categorias [$key];
			}
		}
		return null;
	}

	function delCategoria($categoria_id){
		$categoria = $this->getCategoriaById($categoria_id);
		if (($key = array_search($categoria, $this->categorias, TRUE)) !== FALSE ){
			unset($this->categorias[$key]);
		}
	}


	function getChildCategoriasById($parentId) {
		$childCats = array ();
		foreach ( $this->categorias as $cat ) {
			if ($cat->getPropiedad('parent_id') == $parentId) {
				$childCats [] = $cat;
			}
		}
		return $childCats;
	}

	function getCategoriaBD( $categoria_id=null) {
		//Vaciamos la lista de categorias antes de cargar
		$this->categorias = array ();

		if ($categoria_id) {
			$stmt = $this->PDO->prepare ( 'SELECT * FROM categorias WHERE categoria_id = :categoria_id');
			$stmt->execute ( array (':categoria_id'=>$categoria_id));

		} else {			
			// ordenadas por nombre:
			$stmt = $this->PDO->prepare ( 'SELECT * FROM categorias ORDER BY nombre' );
			//$stmt = $this->PDO->prepare ( 'SELECT * FROM categorias' );
			$stmt->execute ();
		}


		while ( $row = $stmt->fetch () ) {
			$nombre = $this->sanitize($row ['nombre']);
			$categoria_id = $this->sanitize($row ['categoria_id']);
			$parent_id = $this->sanitize($row ['parent_id']);
			$descripcion = $this->sanitize($row ['descripcion']);
			$activo = $row['activa'] ? TRUE : FALSE;
			
			$valores= array('nombre'=>$nombre, 'id'=>$categoria_id, 'parent_id'=>$parent_id,
				'descripcion'=>$descripcion, 'activo'=>$activo);
			$cat = new Categoria ( $valores );
			$this->addCategoria ( $cat );
		}
		return $this;
	}

	function addCategoriaBD( Categoria $categoria) {
		$stmt = $this->PDO->prepare ( 'INSERT INTO categorias (nombre, descripcion, parent_id) VALUES (:nombre, :descripcion, :parentId)' );
		$stmt->execute ( array (
				':nombre' => $categoria->getPropiedad ('nombre'),
				':descripcion' => $categoria->getPropiedad ('descripcion'),
				':parentId' => $categoria->getPropiedad ('parent_id')
		) );		
	}
	function delCategoriaBD( $categoria_id) {
		$stmt = $this->PDO->prepare ( 'DELETE FROM categorias WHERE categoria_id = :categoria_id');
		$stmt->execute ( array (
				':categoria_id' => $categoria_id
		) );
		$this->delCategoria($categoria_id);
	}

	function setCategoriaBD( Categoria $categoria) {
		$stmt = $this->PDO->prepare ('UPDATE categorias SET nombre = :nombre, parent_id = :parent_id, descripcion = :descripcion, activa = :activa WHERE categoria_id = :categoria_id');
		$stmt->execute ( array (
				':categoria_id' => $categoria->getPropiedad('id'),
				':nombre' => $categoria->getPropiedad ('nombre'),
				':descripcion' => $categoria->getPropiedad ('descripcion'),
				':parent_id' => $categoria->getPropiedad ('parent_id'),
				':activa' => $categoria->getPropiedad('activo'),
		) );
		$this->delCategoria($categoria->getPropiedad('id'));
		$this->addCategoria($categoria);
		
	}

	function getTotal(){
		return count($this->categorias);
	}

	private function sanitize($item){
		return htmlentities(trim($item), ENT_QUOTES, 'ISO-8859-1');
	}

	
}