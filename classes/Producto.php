<?php
require_once 'classes/Item.php';

class Producto extends Item {
	function __construct (array $valores){
		static::$lista_propiedades = static::getListaPropiedades();
		$this->propiedades = $valores;
	}

	static function getListaPropiedades(){
		return array (
			'id' => 'int',
			'categoria_id' =>'int',
			'nombre'=>'string',
			'descripcion'=>'string',
			'activo'=>'boolean',
			'precio_venta'=>'double',
			'fabricante_id'=>'int',
			'disponibilidad'=>'string',
			'existencias'=>'int',
			'fecha'=>'date'
			);
	}

	public function calcDisponibilidad(){
		$aux = 'Agotado';

		if (!$this->getPropiedad('activo')) {
			if ($this->getPropiedad('existencias')>0) {
				$aux = 'Outlet';
			} else {
				$aux = 'Descatalogado';
			}
		} else if ($this->getPropiedad('existencias')>0) {
			$aux = 'En stock';
		} else if ($this->getPropiedad('disponibilidad') == 'Próxima reposición') {
			$aux = $this->getPropiedad('disponibilidad');
		}

		$this->setPropiedad('disponibilidad', $aux);
		return;
	}


}
?>