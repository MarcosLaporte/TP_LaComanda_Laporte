<?php
class ProductoOrden
{
	public $idPedido;
	public $idProducto;
	public $idMesa;
	public $descripcion;
	public $estado;
	public $tiempo;
	public $idEmpleado;

	public function CrearProductoOrden()
	{
		$objAccesoDatos = AccesoDatos::ObtenerInstancia();

		$req = $objAccesoDatos->PrepararConsulta("INSERT INTO productoOrden (idPedido, idProducto, idMesa, descripcion, estado, tiempo, idEmpleado) " .
												"VALUES (:idPedido, :idProducto, :idMesa, :descripcion, :estado, :tiempo, :idEmpleado)");
		$req->bindValue(':idPedido', $this->idPedido, PDO::PARAM_INT);
		$req->bindValue(':idProducto', $this->idProducto, PDO::PARAM_INT);
		$req->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
		$req->bindValue(':descripcion', $this->descripcion, PDO::PARAM_STR);
		$req->bindValue(':estado', $this->estado, PDO::PARAM_INT);
		$req->bindValue(':tiempo', $this->tiempo, PDO::PARAM_STR);
		$req->bindValue(':idEmpleado', $this->idEmpleado, PDO::PARAM_INT);

		$req->execute();

		return $objAccesoDatos->ObtenerUltimoId();
	}

	public function TraerTodos()
	{
		$objAccesoDatos = AccesoDatos::ObtenerInstancia();
        $req = $objAccesoDatos->PrepararConsulta("SELECT * from productoOrden");
        $req->execute();
        return $req->fetchAll(PDO::FETCH_CLASS, 'ProductoOrden');
	}
}