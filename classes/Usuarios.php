<?php
require_once 'classes/Usuario.php';
require_once 'classes/Coleccion.php';

class Usuarios extends Coleccion{
	protected $tabla;

	public function __construct(Controlador $controlador) {
		$this->controlador = $controlador;
		$this->tabla = 'usuarios';
		$this->orden = 'nombre';
		$this->miembro = 'Usuario';
	}

	/* Comprueba si existe algun usuario con esa contraseña en la base de datos
	 * y devuelve su id.
	 *	@param 	$usuario objeto de tipo usuario que queremos comprobar
	 *	@return id del usuario si existe o null
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


	public function getPrivilegiosBD(Usuario $usuario){
		$prepare = "SELECT pr.nombre FROM usuarios u INNER JOIN usuarios_roles ur ON u.id=ur.usuario_id INNER JOIN privilegios_rol pr_r USING (rol_id) INNER JOIN privilegios pr WHERE u.id={$usuario->getPropiedad('id')}";
		echo "<br>$prepare</br>";
		$stmt = $this->controlador->getPDO()->prepare($prepare);
		$stmt->execute();
		$rows = $stmt->fetchAll();

		print_r($rows);

		// Rellenamos la lista de privilegios del objeto
		foreach ($rows as $key => $row){
			$usuario->setPrivilegio($row);
		}

		
	}

}
?>