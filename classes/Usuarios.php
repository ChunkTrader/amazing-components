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

	public function getRolesBD(Usuario $usuario){
		$prepare = "SELECT r.nombre FROM usuarios u INNER JOIN usuarios_roles ur ON u.id=ur.usuario_id INNER JOIN roles r ON ur.rol_id=r.id WHERE u.id={$usuario->getPropiedad('id')}";
		//echo "<br>$prepare</br>";
		$stmt = $this->controlador->getPDO()->prepare($prepare);
		$stmt->execute();
		$rows = $stmt->fetchAll();

		// Rellenamos la lista de roles del objeto
		foreach ($rows as $key => $row){
			$usuario->setRol($row['nombre']);
		}
	}


	public function getPrivilegiosUsuarioBD(Usuario $usuario){
		$prepare = "SELECT p.nombre FROM privilegios p INNER JOIN privilegios_rol pr ON pr.privilegio_id=p.id INNER JOIN roles r ON pr.rol_id = r.id INNER JOIN usuarios_roles ur ON ur.rol_id=r.id INNER JOIN usuarios u ON ur.usuario_id = u.id WHERE r.activo = 1 AND u.id={$usuario->getPropiedad('id')}";

		//echo "<br>$prepare</br>";
		$stmt = $this->controlador->getPDO()->prepare($prepare);
		$stmt->execute();
		$rows = $stmt->fetchAll();

		// Rellenamos la lista de privilegios del objeto
		foreach ($rows as $key => $row){
			$usuario->setPrivilegio($row['nombre']);
		}
	}

	/* Elimina los roles almacenados en la base de datos para el usuario y añade los nuevos
	 * Hay que pasar los roles porque la base de datos solo almacena las ids, y por claridad
	 * el usuario solo almacena los nombres.
	 * 
	 * @ param $usuario 	Objeto tipo Usuario con los datos del usuario.
	 * @ param $roles 		Objeto de tipo Roles con la lista completa de roles
	 */
	public function setRolesBD(Usuario $usuario, Roles $roles){

		// Eliminamos todos los roles actuales
				
		$prepare = "DELETE FROM usuarios_roles WHERE usuario_id = '" . $usuario->getPropiedad('id') . "'";
		$stmt = $this->controlador->getPDO()->prepare($prepare);
		$stmt->execute();

		// Añadimos los roles seleccionados
		$prepare = "INSERT INTO usuarios_roles(usuario_id, rol_id) VALUES (:usuario_id, :rol_id)";
		$stmt = $this->controlador->getPDO()->prepare($prepare);
		$a = $usuario->getRoles();
		foreach ($a as $key=>$rol) {
			$stmt->execute(array (
					':usuario_id' => $usuario->getPropiedad('id'),
					':rol_id' => $roles->getItemByNombre($key)->getPropiedad('id')
				));
		}

	}

	public function getUsuarioByNombreBD($nombre){
		// Limpiamos la colección
		$this->coleccion = array();
		
		$prepare = "SELECT * FROM {$this->tabla} WHERE nombre = :nombre";
		$stmt = $this->controlador->getPDO()->prepare($prepare);
		$stmt->execute( array(
				':nombre'=>$nombre
			));
		$rows = $stmt->fetchAll();

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

}
?>