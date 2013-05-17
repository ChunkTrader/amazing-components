<?php
abstract class Registro {
	protected $valores = array();

	abstract protected function __construct();

	static function instancia(){}

	protected function get ($clave){
		if (!empty($this->valores[$clave])){
			return $this->valores[$clave];
		}
		return null;
	}

	protected function set ($clave, $valor){
		$this->valores[$clave] = $valor;
	}

	protected function del ($clave){
		unset($this->valores[$clave]);
	}


}

class RegistroMemoria extends Registro {
	private static $instancia;
	
	protected function __construct(){}

	static function instancia() {
		if (!isset(self::$instancia)) {
			self::$instancia = new self();
			self::$instancia->inicializar();
		}
		return self::$instancia;
	}
	
	private function inicializar() {
		// Inicializamos con los valores de get y post
		$this->valores = $_REQUEST;

		// Guardamos el método empleado,
		if (!empty($_GET)) {
			$this->setValor('metodo', 'GET');
		} else if (!empty($_POST)){
			$this->setValor('metodo', 'POST');
		}
	}

	public function setValor($clave, $valor){
		parent::set($clave, $valor);
	}

	public function getValor($clave=null){
		if ($clave) {
			return parent::get($clave);
		}
		return $this->valores;
	}
}

class RegistroErrores extends Registro {
	private static $instancia;

	protected function __construct(){}

	static function instancia() {
		if (!isset(self::$instancia)) {
			self::$instancia = new self();
		}
	return self::$instancia;
	}

	public function setError($clave, $error){
		parent::set($clave, $error);
	}

	public function unSetError($clave){
		parent::del($clave);
	}

	public function getError($clave=null){
		if ($clave) {
			return parent::get($clave);
		} else {
			return $this->valores;
		}
	}
}

class RegistroFeedback extends Registro {
	private static $instancia;

	protected function __construct(){}

	static function instancia() {
		if (!isset(self::$instancia)) {
			self::$instancia = new self();
		}
	return self::$instancia;
	}

	public function addFeedback($feedback){
		$this->valores [] = $feedback;
	}

	public function getFeedback(){
		return $this->valores;
	}
}

class RegistroSistema extends Registro {
	private static $instancia;

	protected function __construct(){
	}

	static function instancia() {
		if (!isset(self::$instancia)) {
			self::$instancia = new self();
			self::$instancia->inicializar();
		}
	return self::$instancia;
	}

	private function inicializar() {
		// Inicializamos con los valores de session
		$this->valores=$_SESSION;
		if (isset($_SESSION['usuario'])) {
			$a= $_SESSION['usuario'];
			$valores = array();
			foreach ($a as $key => $test){
				$valores[$key]=$test;
			}
			$this->setValor('usuario', new Usuario($valores));
		}

	}

	public function setValor($clave, $valor){
		$_SESSION[$clave]=$valor;
		parent::set($clave, $valor);
	}

	public function getValor($clave=null){
		if ($clave) {
			return parent::get($clave);
		}
		return $this->valores;
	}

	public function limpiar(){
		$this->valores = null;
		session_unset();
		session_destroy();
		setcookie('login', '', time()-3600);
		setcookie('carrito', '', time()-3600);

	}
}

