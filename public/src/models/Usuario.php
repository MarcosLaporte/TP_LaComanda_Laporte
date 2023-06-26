<?php
include_once __DIR__ . "\..\db\AccesoDatos.php";

class Usuario
{
	public $id;
	public $usuario;
	public $clave;
	public $rol;

	public function CrearUsuario()
	{
		$objAccesoDatos = AccesoDatos::ObtenerInstancia();
		$req = $objAccesoDatos->PrepararConsulta("INSERT INTO usuarios (usuario, clave, rol) VALUES (:usuario,:clave,:rol)");

		$claveHash = password_hash($this->clave, PASSWORD_DEFAULT);
		$req->bindValue(':usuario', $this->usuario, PDO::PARAM_STR);
		$req->bindValue(':clave', $claveHash, PDO::PARAM_STR);
		$req->bindValue(':rol', $this->rol, PDO::PARAM_STR);
		$req->execute();

		return $objAccesoDatos->ObtenerUltimoId();
	}

	public static function TraerTodos()
	{
		$objAccesoDatos = AccesoDatos::ObtenerInstancia();
		$req = $objAccesoDatos->PrepararConsulta("SELECT * FROM usuarios");
		$req->execute();
		return $req->fetchAll(PDO::FETCH_CLASS, 'Usuario');
	}

	public static function TraerPorRol($rol)
	{
		$objAccesoDatos = AccesoDatos::ObtenerInstancia();
		$req = $objAccesoDatos->PrepararConsulta("SELECT * FROM usuarios WHERE rol LIKE :rol");
		$req->bindValue(':rol', $rol, PDO::PARAM_STR);
		$req->execute();
		return $req->fetchAll(PDO::FETCH_CLASS, 'Usuario');
	}

	public static function TraerPorId($id)
	{
		$objAccesoDatos = AccesoDatos::ObtenerInstancia();
		$req = $objAccesoDatos->PrepararConsulta("SELECT * FROM usuarios WHERE id=:id");
		$req->bindValue(':id', $id, PDO::PARAM_INT);
		$req->execute();
		return $req->fetchAll(PDO::FETCH_CLASS, 'Usuario');
	}
}