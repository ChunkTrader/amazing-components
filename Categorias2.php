<?php
require_once 'Item.php';

class Categoria extends Item {

	function __construct (array $valores){
		$this->lista_propiedades = array ('id', 'parent_id', 'nombre', 'descripcion', 'activo');
		$this->propiedades = $valores;
	}

}



