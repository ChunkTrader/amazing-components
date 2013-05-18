<?php
class PDOConfig extends PDO {
	private $engine;
	private $host;
	private $database;
	private $user;
	private $pass;
	public function __construct() {
		$this->engine = 'mysql';
		$this->host = 'sql311.byethost32.com';
		$this->database = 'b32_12380073_amazing_components';
		$this->user = 'b32_12380073';
		$this->pass = 'black13';
		$dns = $this->engine . ':dbname=' . $this->database . ";host=" . $this->host;
		parent::__construct ( $dns, $this->user, $this->pass );
		$this->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		$this->setAttribute ( PDO::ATTR_EMULATE_PREPARES, false );
	}
}