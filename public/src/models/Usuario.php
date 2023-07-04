<?php
include_once(__DIR__ . "\..\db\AccesoDatos.php");

define('USUARIO_INACTIVO', -1);
define('USUARIO_SUSPENDIDO', 0);
define('USUARIO_ACTIVO', 1);

class Usuario
{
	public $id;
	public $usuario;
	public $clave;
	public $rol;
	public $estado;

	public function CrearUsuario()
	{
		$objAccesoDatos = AccesoDatos::ObtenerInstancia();
		$req = $objAccesoDatos->PrepararConsulta("INSERT INTO usuarios (usuario, clave, rol, estado) VALUES (:usuario,:clave,:rol, 1)");

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

	public static function ModificarEstado($id, $estado)
	{
		$objAccesoDatos = AccesoDatos::ObtenerInstancia();
		$req = $objAccesoDatos->PrepararConsulta("UPDATE usuarios SET estado=:estado WHERE id=:id");
		$req->bindValue(':estado', $estado, PDO::PARAM_INT);
		$req->bindValue(':id', $id, PDO::PARAM_INT);
		$req->execute();
		return $req->fetchAll(PDO::FETCH_CLASS, 'Usuario');
	}

	protected static function ParseEstado($estado)
	{
		switch ($estado) {
			case -1:
				return "INACTIVO";
			case 0:
				return "SUSPENDIDO";
			case 1:
				return "ACTIVO";
		}
	}
}