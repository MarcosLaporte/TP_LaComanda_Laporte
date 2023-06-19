<?php

class AccesoDatos
{
	private static $objAccesoDatos;
	private $pdo;

	private function __construct()
	{
		try {
			$this->pdo = new PDO('mysql:host=localhost;dbname=comanda_laporte;charset=utf8','root', '', array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
			$this->pdo->exec("SET CHARACTER SET utf8");
		} catch (PDOException $e) {
			print "Error: " . $e->getMessage();
			die();
		}
	}

	public static function ObtenerInstancia()
	{
		if (!isset(self::$objAccesoDatos)) {
			self::$objAccesoDatos = new AccesoDatos();
		}
		return self::$objAccesoDatos;
	}

	public function PrepararConsulta($sql)
	{
		return $this->pdo->prepare($sql);
	}

	public function ObtenerUltimoId()
	{
		return $this->pdo->lastInsertId();
	}
}