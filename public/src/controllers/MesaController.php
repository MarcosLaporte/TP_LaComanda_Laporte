<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

include_once(__DIR__ . "\..\models\Mesa.php");
include_once(__DIR__ . "\..\interfaces\IPdo.php");

class MesaController extends Mesa implements IPdo
{
	public static function Add(Request $request, Response $response, array $args)
	{
		$params = $request->getParsedBody();

		$mesa = new Mesa();
		$mesa->estado = $params['estado'];
		$mesa->CrearMesa();

		$payload = json_encode(array("msg" => "Mesa creada con exito"));
		$response->getBody()->write($payload);

		return $response->withHeader('Content-Type', 'application\json');
	}

	public static function GetAll(Request $request, Response $response, array $args)
	{
		$mesas = Mesa::TraerTodos();

		$payload = json_encode(array("list" => $mesas));
		$response->getBody()->write($payload);

		return $response->withHeader('Content-Type', 'application\json');
	}
	public static function GetOne(Request $request, Response $response, array $args)
	{

	}

	public static function Delete(Request $request, Response $response, array $args)
	{

	}

	public static function Modify(Request $request, Response $response, array $args)
	{
		$params = $request->getParsedBody();
		$mesa = Mesa::TraerMesa($args['id']);

		if (!empty($mesa)) {
			$mesa = Mesa::ModificarEstado($args['id'], $params['estado']);
			$estadoStr = Mesa::ParseEstado($params['estado']);
			$payload = json_encode(array("msg" => "Mesa #{$args['id']}: $estadoStr"));
		} else {
			$payload = json_encode(array("msg" => "No existe una mesa con ese ID!"));
		}

		$response->getBody()->write($payload);
		return $response->withHeader('Content-Type', 'application\json');
	}

}