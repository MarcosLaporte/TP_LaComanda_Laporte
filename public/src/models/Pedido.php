<?php

include_once "Producto.php";
include_once __DIR__ . "\..\db\AccesoDatos.php";

define('PEDIDO_PREPARACION', 0);
define('PEDIDO_LISTO', 1);
class Pedido
{
	public $id;
	public $idMesa;
	public $estado;
	public $precio;
	public $minutos;
	public $foto;

	public function CrearPedido()
	{
		$objAccesoDatos = AccesoDatos::ObtenerInstancia();

		$req = $objAccesoDatos->PrepararConsulta("INSERT INTO pedidos (id, idMesa, estado, precio) " .
			"VALUES (:id, :idMesa, 0, :precio)");
		$req->bindValue(':id', $this->id, PDO::PARAM_STR);
		$req->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
		$req->bindValue(':precio', $this->precio, PDO::PARAM_STR);
		$req->execute();

		return $objAccesoDatos->ObtenerUltimoId();
	}

	public static function AgregarUriFoto($id, $uri)
	{
		$objAccesoDatos = AccesoDatos::ObtenerInstancia();
		$req = $objAccesoDatos->PrepararConsulta("UPDATE pedidos SET foto=:uri WHERE id=:id");
		$req->bindValue(':id', $id, PDO::PARAM_INT);
		$req->bindValue(':uri', $uri, PDO::PARAM_STR);
		$req->execute();

		return $objAccesoDatos->ObtenerUltimoId();
	}

	public static function ModificarDuracion($id)
	{
		$objAccesoDatos = AccesoDatos::ObtenerInstancia();
		$req = $objAccesoDatos->PrepararConsulta(
		"UPDATE pedidos SET minutos = (
			SELECT SUM(minutos)
			FROM productos_pedidos
			WHERE productos_pedidos.idPedido = pedidos.id
		) WHERE id = :id;"
		);

		$req->bindValue(':id', $id, PDO::PARAM_STR);
		$req->execute();

		return $objAccesoDatos->ObtenerUltimoId();
	}

	public static function TraerTodos()
	{
		$objAccesoDatos = AccesoDatos::ObtenerInstancia();
		$req = $objAccesoDatos->PrepararConsulta("SELECT * FROM pedidos");
		$req->execute();
		return $req->fetchAll(PDO::FETCH_CLASS, 'Pedido');
	}

	public static function TraerTodosId()
	{
		$objAccesoDatos = AccesoDatos::ObtenerInstancia();
		$req = $objAccesoDatos->PrepararConsulta("SELECT id FROM pedidos");
		$req->execute();
		return $req->fetchAll(PDO::FETCH_COLUMN);
	}

	public static function TraerPorId($id)
	{
		$objAccesoDatos = AccesoDatos::ObtenerInstancia();
		$req = $objAccesoDatos->PrepararConsulta("SELECT * FROM pedidos WHERE id=:id");
		$req->bindValue(':id', $id, PDO::PARAM_STR);
		$req->execute();

		return $req->fetchAll(PDO::FETCH_CLASS, 'Pedido');
	}

	public static function IncrementarPrecio($id, $valor)
	{
		$objAccesoDatos = AccesoDatos::ObtenerInstancia();

		$req = $objAccesoDatos->PrepararConsulta("UPDATE pedidos SET precio=precio+:valor WHERE id=:id");
		$req->bindValue(':valor', $valor, PDO::PARAM_STR);
		$req->bindValue(':id', $id, PDO::PARAM_STR);
		$req->execute();

		return $objAccesoDatos->ObtenerUltimoId();
	}

	public static function SectorYEmpleadoValido($sector, $rol)
	{
		return (
			($sector == SECTOR_TRAGOS && !strcasecmp($rol, "bartender"))
			|| ($sector == SECTOR_CERVEZAS && !strcasecmp($rol, "cervecero"))
			|| (($sector == SECTOR_COCINA || $sector == SECTOR_CANDY) && !strcasecmp($rol, "cocinero"))
		);
	}

	public static function PedidoListo($id)
	{
		$objAccesoDatos = AccesoDatos::ObtenerInstancia();

		$req = $objAccesoDatos->PrepararConsulta("UPDATE pedidos SET estado=1 WHERE id=:id");
		$req->bindValue(':id', $id, PDO::PARAM_STR);
		$req->execute();

		return $objAccesoDatos->ObtenerUltimoId();
	}
}