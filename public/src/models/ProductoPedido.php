<?php

class ProductoPedido
{
	public $idProducto;
	public $idMesa;
	public $idPedido;
	public $estado;
	public $minutos;
	public $cliente;

	public function CrearProductoPedido()
	{
		$objAccesoDatos = AccesoDatos::ObtenerInstancia();

		$req = $objAccesoDatos->PrepararConsulta("INSERT INTO productos_pedidos (idProducto, idMesa, idPedido, estado, cliente) "
			. "VALUES (:idProducto, :idMesa, :idPedido, 0, :cliente)");
		$req->bindValue(':idProducto', $this->idProducto, PDO::PARAM_INT);
		$req->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
		$req->bindValue(':idPedido', $this->idPedido, PDO::PARAM_STR);
		$req->bindValue(':cliente', $this->cliente, PDO::PARAM_STR);
		$req->execute();

		return $objAccesoDatos->ObtenerUltimoId();
	}

	public static function BuscarEnPedido($idProducto, $idPedido)
	{
		$objAccesoDatos = AccesoDatos::ObtenerInstancia();
		$req = $objAccesoDatos->PrepararConsulta("SELECT * from productos_pedidos WHERE idProducto=:idProducto AND idPedido=:idPedido");
        $req->bindValue(':idProducto', $idProducto, PDO::PARAM_INT);
		$req->bindValue(':idPedido', $idPedido, PDO::PARAM_STR);
		$req->execute();

		return $req->fetchAll(PDO::FETCH_CLASS, 'ProductoPedido');
	}

	public static function TraerProdPorPedido($idPedido)
	{
		$objAccesoDatos = AccesoDatos::ObtenerInstancia();
		$req = $objAccesoDatos->PrepararConsulta("SELECT idProducto from productos_pedidos WHERE idPedido=:idPedido");
		$req->bindValue(':idPedido', $idPedido, PDO::PARAM_STR);
		$req->execute();

		return $req->fetchAll(PDO::FETCH_COLUMN);
	}

	public static function TraerPorSector($sector)
	{
		$objAccesoDatos = AccesoDatos::ObtenerInstancia();
		$req = $objAccesoDatos->PrepararConsulta("SELECT pp.* FROM productos_pedidos pp " .
		"JOIN productos p ON pp.idProducto = p.id " .
		"WHERE pp.estado = 0 AND p.sector = :sector");
		$req->bindValue(':sector', $sector, PDO::PARAM_INT);
		$req->execute();
		
		return $req->fetchAll(PDO::FETCH_CLASS, 'ProductoPedido');
	}
	
	public static function ModificarDuracion($idProducto, $idPedido, $duracion)
	{
		$objAccesoDatos = AccesoDatos::ObtenerInstancia();
		$req = $objAccesoDatos->PrepararConsulta("UPDATE productos_pedidos SET minutos=:duracion ". 
				"WHERE idProducto=:idProducto AND idPedido=:idPedido");
        $req->bindValue(':idProducto', $idProducto, PDO::PARAM_INT);
        $req->bindValue(':idPedido', $idPedido, PDO::PARAM_STR);
		$req->bindValue(':duracion', $duracion, PDO::PARAM_INT);
		$req->execute();
		
        return $objAccesoDatos->ObtenerUltimoId();
	}

	public static function ProductoListo($idProducto, $idPedido)
	{
		$objAccesoDatos = AccesoDatos::ObtenerInstancia();
		$req = $objAccesoDatos->PrepararConsulta("UPDATE productos_pedidos SET estado=1, minutos=0 WHERE idProducto=:idProducto AND idPedido=:idPedido");
        $req->bindValue(':idProducto', $idProducto, PDO::PARAM_INT);
		$req->bindValue(':idPedido', $idPedido, PDO::PARAM_STR);
		$req->execute();

        return $objAccesoDatos->ObtenerUltimoId();
	}

	public static function CantPendientes($idPedido)
	{
		$objAccesoDatos = AccesoDatos::ObtenerInstancia();

		$req = $objAccesoDatos->PrepararConsulta("SELECT COUNT(*) FROM productos_pedidos WHERE idPedido=:idPedido AND estado=0");
		$req->bindValue(':idPedido', $idPedido, PDO::PARAM_STR);
		$req->execute();
		
		return $req->fetchColumn();
	}
}