<?php
require_once 'classes/DatosUsuario.php';
require_once 'classes/Coleccion.php';

class DatosUsuarios extends Coleccion{
	protected $tabla;

	public function __construct(Controlador $controlador) {
		$this->controlador = $controlador;
		$this->tabla = 'datos_usuarios';
		$this->orden = 'nombre';
		$this->miembro = 'DatosUsuario';
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

		// En este caso no eliminamos la id  id 
		
		
		$prepare = 'INSERT INTO ' . $this->tabla .  ' (' . implode($aux1, ',') . ') VALUES (' . implode($aux2, ',') . ')';

		$stmt = $this->controlador->getPDO()->prepare($prepare);
		$stmt->execute($propiedades);
	}
}
?>