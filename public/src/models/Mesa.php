<?php
define('MESA_VACIA', 0);
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

	public static function ModificarEstado($id, $estado)
	{
		$objAccesoDatos = AccesoDatos::ObtenerInstancia();
		$req = $objAccesoDatos->PrepararConsulta("UPDATE mesas SET estado=:estado WHERE id=:id");
        $req->bindValue(':estado', $estado, PDO::PARAM_INT);
        $req->bindValue(':id', $id, PDO::PARAM_INT);
		$req->execute();

        return $objAccesoDatos->ObtenerUltimoId();
	}

	public static function ParseEstado(int $numEstado)
	{
		switch ($numEstado) {
			case MESA_VACIA:
				return "VACÍA";
			case MESA_ESPERANDO:
				return "ESPERANDO";
			case MESA_COMIENDO:
				return "COMIENDO";
			case MESA_PAGANDO:
				return "PAGANDO";
			case MESA_CERRADA:
				return "CERRADA";
			default:
				return "N/A";
		}
	}
}