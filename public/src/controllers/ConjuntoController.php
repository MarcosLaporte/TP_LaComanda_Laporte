<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
include_once __DIR__ . "\..\models\PedidoConjunto.php";
include_once __DIR__ . "\..\interfaces\IPdo.php";

class ConjuntoController extends PedidoConjunto
{
	public static function Add(Request $request, Response $response, array $args)
	{
		$params = $request->getParsedBody();

		$pedidoConjunto = new PedidoConjunto();
		$pedidoConjunto->id = substr(uniqid(), -5);
		$pedidoConjunto->idMesa = $params['idMesa'];
		$pedidoConjunto->minutos = intval($params['minutos']);
		$pedidoConjunto->CrearPedido();

		return $response;
	}

	public static function GetAll(Request $request, Response $response, array $args)
	{
		$pedidos = PedidoConjunto::TraerTodos();
		
		$payload = json_encode(array("list" => $pedidos));
		$response->getBody()->write($payload);

		return $response->withHeader('Content-Type', 'application/json');
	}

	public static function GetOne(Request $request, Response $response, array $args)
	{
		$pedidoConjunto = PedidoConjunto::TraerPorId($args['id']);

		$payload = json_encode(array("obj" => $pedidoConjunto));
		$response->getBody()->write($payload);

		return $response->withHeader('Content-Type', 'application/json');
	}
}