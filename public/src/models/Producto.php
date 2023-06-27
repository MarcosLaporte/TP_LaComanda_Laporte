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

	public static function CsvToProducto($rutaArchivo)
	{
		$refArchivo = fopen($rutaArchivo, "r");
		$arrayAtr = array();
		$datos = array();

		if ($refArchivo) {
			while (!feof($refArchivo)) {
				$arrayAtr = fgetcsv($refArchivo);
				if (!empty($arrayAtr)) {
					$producto = new Producto();
					$producto->id = intval($arrayAtr[0]);
					$producto->sector = intval($arrayAtr[1]);
					$producto->descripcion = str_ireplace("_", ",", $arrayAtr[2]);
					$producto->precio = doubleval($arrayAtr[3]);
					array_push($datos, $producto);
				}
			}
			fclose($refArchivo);
		}

		return $datos;
	}
	
	public static function SubirDatosCsv()
	{
		$archivo = Archivo::GuardarArchivoPeticion("src/db/", "productos", 'csv', '.csv');
		if ($archivo != "N/A") {
			$arrayProd = self::CsvToProducto($archivo);
			foreach ($arrayProd as $producto) {
				$producto->CrearProducto();
			}
			return true;
		}
		
		return false;
	}
	
	public static function DbToCsv($rutaArchivo)
	{
		$productos = self::TraerTodos();

		if (!empty($productos)) {
			$refArchivo = fopen($rutaArchivo, "w");
			if ($refArchivo) {
				$arrayCsv = array();
				foreach ($productos as $producto) {
					$producto->descripcion = str_ireplace(",", "_", $producto->descripcion);
					$attr = get_object_vars($producto);
					$strProd = implode(',', $attr) . PHP_EOL;

					fwrite($refArchivo, $strProd);
					array_push($arrayCsv, $strProd);
				}
				fclose($refArchivo);
				return $arrayCsv;
			}
		}

		return false;
	}

}