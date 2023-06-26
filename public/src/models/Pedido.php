<?php
define('PEDIDO_PREPARACION', 0);
define('PEDIDO_LISTO', 1);

class Pedido
{
	public $id;
	public $idConjunto;
	public $idProducto;
	public $estado;
	public $cliente;
	public $minutos;
	public $foto;

	public function CrearPedido()
	{
		$objAccesoDatos = AccesoDatos::ObtenerInstancia();
		$req = $objAccesoDatos->PrepararConsulta("INSERT INTO pedidos (id, idConjunto, idProducto, estado, cliente, minutos, foto) " .
			"VALUES (:id, :idConjunto, :idProducto, 0, :nombreCliente, :minutos, :foto)");


		$req->bindValue(':id', $this->id, PDO::PARAM_STR);
		$req->bindValue(':idConjunto', $this->idConjunto, PDO::PARAM_INT);
		$req->bindValue(':idProducto', $this->idProducto, PDO::PARAM_INT);
		$req->bindValue(':nombreCliente', $this->cliente, PDO::PARAM_STR);
		$req->bindValue(':minutos', $this->minutos, PDO::PARAM_INT);
		$req->bindValue(':foto', $this->foto, PDO::PARAM_STR);
		$req->execute();

		return $objAccesoDatos->ObtenerUltimoId();
	}

	public static function TraerTodos()
	{
		$objAccesoDatos = AccesoDatos::ObtenerInstancia();
		$req = $objAccesoDatos->PrepararConsulta("SELECT * from pedidos");
		$req->execute();
		return $req->fetchAll(PDO::FETCH_CLASS, 'Pedido');
	}

	public static function TraerPorId($id)
	{
		$objAccesoDatos = AccesoDatos::ObtenerInstancia();
		$req = $objAccesoDatos->PrepararConsulta("SELECT * from pedidos WHERE id LIKE :id");
		$req->bindValue(':id', $id, PDO::PARAM_STR);
		$req->execute();

		return $req->fetchAll(PDO::FETCH_CLASS, 'Pedido');
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
        $req->bindValue(':id', $id, PDO::PARAM_INT);
		$req->execute();

        return $objAccesoDatos->ObtenerUltimoId();
	}
}