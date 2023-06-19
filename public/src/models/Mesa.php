<?php
define('MESA_ESPERANDO', 1);
define('MESA_COMIENDO', 2);
define('MESA_PAGANDO', 3);
define('MESA_CERRADA', 4);

class Mesa
{
	public $id;
	public $estado;

	public function CrearMesa()
    {
        $objAccesoDatos = AccesoDatos::ObtenerInstancia();
		
        $req = $objAccesoDatos->PrepararConsulta("INSERT INTO mesas (estado) VALUES (:estado)");
        $req->bindValue(':estado', $this->estado, PDO::PARAM_INT);
        $req->execute();

        return $objAccesoDatos->ObtenerUltimoId();
    }

	public static function TraerTodos()
	{
		$objAccesoDatos = AccesoDatos::ObtenerInstancia();
        $req = $objAccesoDatos->PrepararConsulta("SELECT * FROM mesas");
        $req->execute();
        return $req->fetchAll(PDO::FETCH_CLASS, 'Mesa');
	}
	
	public static function TraerMesa($id)
	{
		$objAccesoDatos = AccesoDatos::ObtenerInstancia();
		$req = $objAccesoDatos->PrepararConsulta("SELECT * FROM mesas WHERE id=:id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
		$req->execute();
		return $req->fetchAll(PDO::FETCH_CLASS, 'Mesa');
	}
}