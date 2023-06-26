<?php
define('SECTOR_TRAGOS', 1);
define('SECTOR_CERVEZAS', 2);
define('SECTOR_COCINA', 3);
define('SECTOR_CANDY', 4);

class Producto
{
	public $id;
    public $sector;
    public $descripcion;
    public $precio;

	public function CrearProducto()
    {
        $objAccesoDatos = AccesoDatos::ObtenerInstancia();
        $req = $objAccesoDatos->PrepararConsulta("INSERT INTO productos (sector, descripcion, precio) VALUES (:sector,:descripcion,:precio)");

        $req->bindValue(':sector', $this->sector, PDO::PARAM_INT);
        $req->bindValue(':descripcion', $this->descripcion, PDO::PARAM_STR);
        $req->bindValue(':precio', (string)$this->precio, PDO::PARAM_STR);
        $req->execute();

        return $objAccesoDatos->ObtenerUltimoId();
    }

	public static function TraerTodos()
	{
		$objAccesoDatos = AccesoDatos::ObtenerInstancia();
        $req = $objAccesoDatos->PrepararConsulta("SELECT * FROM productos");
        $req->execute();
        return $req->fetchAll(PDO::FETCH_CLASS, 'Producto');
	}

	public static function TraerTodosId()
	{
		$objAccesoDatos = AccesoDatos::ObtenerInstancia();
		$req = $objAccesoDatos->PrepararConsulta("SELECT id FROM productos");
		$req->execute();
		return $req->fetchAll(PDO::FETCH_COLUMN);
	}

	public static function TraerPorId($id)
	{
		$objAccesoDatos = AccesoDatos::ObtenerInstancia();
		$req = $objAccesoDatos->PrepararConsulta("SELECT * from productos WHERE id LIKE :id");
		$req->bindValue(':id', $id, PDO::PARAM_STR);
		$req->execute();

		return $req->fetchAll(PDO::FETCH_CLASS, 'Producto');
	}

}