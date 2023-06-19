<?php

include_once "Producto.php";
include_once "Archivos.php";

define('PEDIDO_PREPARACION', 0);
define('PEDIDO_LISTO', 1);
class Pedido
{
	public $id;
	public $idMesa;
	public $idProducto;
	public $estado;
	public $cliente;
	public $foto;

	public function CrearPedido()
    {
        $objAccesoDatos = AccesoDatos::ObtenerInstancia();
        $req = $objAccesoDatos->PrepararConsulta("INSERT INTO pedidos (idMesa, idProducto, estado, cliente, foto) " .
												"VALUES (:idMesa, :idProducto, 0, :nombreCliente, :foto)");
		
		$req->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
		$req->bindValue(':idProducto', $this->idProducto, PDO::PARAM_INT);
		$req->bindValue(':nombreCliente', $this->cliente, PDO::PARAM_STR);
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
}