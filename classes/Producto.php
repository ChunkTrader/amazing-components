<?php
require_once 'classes/Item.php';

class Producto extends Item {
	function __construct (array $valores){
		static::$lista_propiedades = static::getListaPropiedades();
		$this->propiedades = $valores;
	}

	static function getListaPropiedades(){
		return array (
			'id' => FILTER_VALIDATE_INT,
			'categoria_id' =>FILTER_VALIDATE_INT,
			'nombre'=>'string',
			'descripcion'=>'string',
			'activo'=>FILTER_VALIDATE_BOOLEAN,
			'precio_venta'=>FILTER_VALIDATE_FLOAT,
			'fabricante_id'=>FILTER_VALIDATE_INT,
			'disponibilidad'=>'string',
			'existencias'=>FILTER_VALIDATE_INT,
			'fecha'=>'date'
			);
		// El campo fecha se podria validar con FILTER_VALIDATE_REGEXP
		// Los valores númericos se pueden modificar con opciones para 
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