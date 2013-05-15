<?php
require_once 'classes/Rol.php';
require_once 'classes/Coleccion.php';

class Roles extends Coleccion{
	protected $tabla;


	public function __construct(Controlador $controlador) {
		$this->controlador = $controlador;
		$this->tabla = 'roles';
		$this->orden = 'nombre';
		$this->miembro = 'Rol';
	}

	public function getPrivilegiosBD(Rol $rol){
		$prepare = "SELECT p.nombre FROM roles r INNER JOIN privilegios_rol pr ON r.id=pr.rol_id INNER JOIN privileges p ON pr.privilegio_id=p.id WHERE r.id={$rol->getPropiedad('id')}";
		echo "<br>$prepare</br>";
		$stmt = $this->controlador->getPDO()->prepare($prepare);
		$stmt->execute();
		$rows = $stmt->fetchAll();

		// Rellenamos la lista de roles del objeto
		foreach ($rows as $key => $row){
			$rol->setPrivilegio($row['nombre']);
		}
	}


	public function setPrivilegiosBD(Privilegios $privilegios, Rol $rol){

		// Eliminamos todos los roles actuales
		$prepare = "DELETE FROM privilegios_rol WHERE rol_id = '" . $rol->getPropiedad('id') . "'";
		$stmt = $this->controlador->getPDO()->prepare($prepare);
		$count=$stmt->execute();
		//$this->controlador->getRegistro('feedback')->addFeedback("Roles anteriores eliminados.");

		// Añadimos los roles seleccionados
		$prepare = "INSERT INTO privilegios_rol(privilegio_id, rol_id) VALUES (:privilegio_id, :rol_id)";
		$stmt = $this->controlador->getPDO()->prepare($prepare);
		$a = $rol->getPrivilegios();
		foreach ($a as $key=>$privilegio) {
			//$this->controlador->getRegistro('feedback')->addFeedback("Añadido rol <b>$key</b>.");
			
			$stmt->execute(array (
					':privilegio_id' => $privilegios->getItemByNombre($key)->getPropiedad('id'),
					':rol_id' => $rol->getPropiedad('id')
				));
		}

	}


}
?>