<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
include_once __DIR__ . "/../models/Producto.php";

class ProductoController extends Producto
{
	public static function CargarProducto(Request $request, Response $response, array $args)
	{
		$params = $request->getParsedBody();

		$producto = new Producto();
		$producto->sector = $params['sector'];
		$producto->descripcion = $params['descripcion'];
		$producto->precio = $params['precio'];
		$producto->CrearProducto();

		$payload = json_encode(array("msg" => "Producto creado con exito"));
	    $response->getBody()->write($payload);

		return $response->withHeader('Content-Type', 'application/json');
	}

	public static function TraerProductos(Request $request, Response $response, array $args)
	{
		$productos = Producto::TraerTodos();
		
		$payload = json_encode(array("list" => $productos));
		$response->getBody()->write($payload);

		return $response->withHeader('Content-Type', 'application/json');
	}
}