<?php
include_once __DIR__ . "/../db/AccesoDatos.php";

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
		$req->bindValue(':usuario', $this->usuario, PDO::PARAM_INT);
		$req->bindValue(':clave', $claveHash, PDO::PARAM_INT);
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

	public static function TraerSocios()
	{
		$objAccesoDatos = AccesoDatos::ObtenerInstancia();
		$req = $objAccesoDatos->PrepararConsulta("SELECT * FROM usuarios WHERE rol='socio'");
		$req->execute();
		return $req->fetchAll(PDO::FETCH_CLASS, 'Usuario');
	}

	public static function TraerUsuario($user)
	{
		$objAccesoDatos = AccesoDatos::ObtenerInstancia();
		$req = $objAccesoDatos->PrepararConsulta("SELECT * FROM usuarios WHERE usuario=:user");
		$req->bindValue(':user', $user);
		$req->execute();
		return $req->fetchAll(PDO::FETCH_CLASS, 'Usuario');
	}
}