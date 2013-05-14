<?php
require_once 'classes/Usuario.php';
require_once 'classes/Coleccion.php';

class Usuarios extends Coleccion{
	protected $tabla;

	public function __construct(Controlador $controlador) {
		$this->controlador = $controlador;
		$this->tabla = 'usuarios';
		$this->orden = 'id';
		$this->miembro = 'Usuario';
	}

	public function getItemByNombre($nombre){
		$a=$this->coleccion;
		foreach ($a as $usuario){
			if ($usuario->getPropiedad('nombre')==$nombre) {
				return $usuario;
			}
		}
		return null;
	}

	/* Comprueba si existe algun usuario con esa contraseña en la base de datos
	 * y devuelve su id.
	 */
	
	public function matchUsuario(Usuario $usuario){
		// Este método una vez arreglado el getItemDB debería llamarlo 
		// prepararar la consulta.
		$prepare = "SELECT id FROM {$this->tabla} WHERE nombre = :nombre AND password = :password";
		$stmt = $this->controlador->getPDO()->prepare($prepare);
		
		$stmt->execute(array (
			':nombre' =>$usuario->getPropiedad('nombre'),
			':password' => $usuario->getPropiedad('password'))
		);

		$row=$stmt->fetch();
		return $row['id'];
	}


}
?>