<?php
class Controlador {
	private $registros = array();
	private $PDO;

	function setPDO(PDO $PDO) {
		$this->PDO = $PDO;
	}

	function getPDO() {
		return $this->PDO;
	}

	function setRegistro ($clave, Registro $registro){
		$this->registros[$clave] = $registro;
	}

	function getRegistro ($clave) {
		return $this->registros[$clave];
	}
}