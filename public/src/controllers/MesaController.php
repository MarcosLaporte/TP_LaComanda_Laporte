<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
include_once __DIR__ . "/../models/Mesa.php";

class MesaController extends Mesa
{
	public static function CargarMesa(Request $request, Response $response, array $args)
	{
		$params = $request->getParsedBody();

		$mesa = new Mesa();
		$mesa->estado = $params['estado'];
		$mesa->CrearMesa();

		$payload = json_encode(array("msg" => "Mesa creada con exito"));
	    $response->getBody()->write($payload);

		return $response->withHeader('Content-Type', 'application/json');
	}

	public static function TraerMesas(Request $request, Response $response, array $args)
	{
		$mesas = Mesa::TraerTodos();
		
		$payload = json_encode(array("list" => $mesas));
		$response->getBody()->write($payload);

		return $response->withHeader('Content-Type', 'application/json');
	}
}