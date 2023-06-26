<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
include_once __DIR__ . "\..\models\Encuesta.php";
include_once __DIR__ . "\..\interfaces\IPdo.php";

class EncuestaController extends Encuesta implements IPdo
{
	public static function Add(Request $request, Response $response, array $args)
	{
		$params = $request->getParsedBody();
		
		$encuesta = new Encuesta();
		$encuesta->idMesa = intval($params['idMesa']);
		$encuesta->idPedido = $params['idPedido'];
		$encuesta->puntMesa = intval($params['puntMesa']);
		$encuesta->puntRestaurante = intval($params['puntRestaurante']);
		$encuesta->puntMozo = intval($params['puntMozo']);
		$encuesta->puntCocina = intval($params['puntCocina']);
		$encuesta->comentarios = $params['comentarios'];
		$encuesta->CrearEncuesta();

		$payload = json_encode(array("msg" => "Encuesta subida con exito!"));
	    $response->getBody()->write($payload);

		return $response->withHeader('Content-Type', 'application\json');
	}
	public static function GetAll(Request $request, Response $response, array $args)
	{
		$encuestas = Encuesta::TraerTodos();
		
		$payload = json_encode(array("list" => $encuestas));
		$response->getBody()->write($payload);

		return $response->withHeader('Content-Type', 'application/json');
	}

	public static function GetOne(Request $request, Response $response, array $args)
	{

	}

	
	public static function Delete(Request $request, Response $response, array $args)
	{

	}

	public static function Modify(Request $request, Response $response, array $args)
	{

	}

}