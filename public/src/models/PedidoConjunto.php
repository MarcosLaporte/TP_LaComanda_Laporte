<?php

class PedidoConjunto
{
	public $id;
	public $idMesa;
	public $estado;
	public $minutos;

	public function CrearPedido()
	{
		$objAccesoDatos = AccesoDatos::ObtenerInstancia();
		$req = $objAccesoDatos->PrepararConsulta("INSERT INTO pedidosconjunto (id, idMesa, minutos, estado) VALUES (:id, :idMesa, :minutos, :estado)");

		$req->bindValue(':id', $this->id, PDO::PARAM_INT);
		$req->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
		$req->bindValue(':minutos', $this->minutos, PDO::PARAM_INT);
		$req->bindValue(':estado', $this->estado, PDO::PARAM_INT);
		$req->execute();

		return $objAccesoDatos->ObtenerUltimoId();
	}

	public static function TraerTodos()
	{
		$objAccesoDatos = AccesoDatos::ObtenerInstancia();
		$req = $objAccesoDatos->PrepararConsulta("SELECT * from pedidosconjunto");
		$req->execute();
		return $req->fetchAll(PDO::FETCH_CLASS, 'PedidoConjunto');
	}

	public static function TraerPorId($id)
	{
		$objAccesoDatos = AccesoDatos::ObtenerInstancia();
		$req = $objAccesoDatos->PrepararConsulta("SELECT * from pedidosconjunto WHERE id=:id");
		$req->bindValue(':id', $id, PDO::PARAM_STR);
		$req->execute();

		return $req->fetchAll(PDO::FETCH_CLASS, 'PedidoConjunto');
	}
}