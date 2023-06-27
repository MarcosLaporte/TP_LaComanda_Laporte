<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
include_once __DIR__ . "\..\models\Pedido.php";
include_once __DIR__ . "\..\models\Archivos.php";
include_once __DIR__ . "\..\interfaces\IPdo.php";

class PedidoController extends Pedido implements IPdo
{
	public static function Add(Request $request, Response $response, array $args)
	{
		$params = $request->getParsedBody();

		$pedido = new Pedido();
		$pedido->id = substr(uniqid(), -5);
		$pedido->idMesa = $params['idMesa'];
		$pedido->idProducto = $params['idProducto'];
		$pedido->estado = PEDIDO_PREPARACION;
		$pedido->cliente = $params['cliente'];
		$pedido->minutos = intval($params['minutos']);
		$pedido->CrearPedido();

		$payload = json_encode(array("msg" => "Pedido creado con exito"));
	    $response->getBody()->write($payload);

		return $response->withHeader('Content-Type', 'application/json');
	}

	public static function AddPic(Request $request, Response $response, array $args)
	{
		$params = $request->getParsedBody();
		$pedido = Pedido::TraerPorId($params['idPedido'])[0];
		$uriFoto = Archivo::GuardarArchivoPeticion("src/FotosMesas/", "{$pedido->cliente}_Mesa{$pedido->idMesa}", 'foto', '.jpg');

		Pedido::AgregarUriFoto($params['idPedido'], $uriFoto);
		$payload = json_encode(array("msg" => "Foto agregada con exito"));
	    $response->getBody()->write($payload);

		return $response->withHeader('Content-Type', 'application/json');
	}

	public static function GetAll(Request $request, Response $response, array $args)
	{
		$pedidos = Pedido::TraerTodos();
		
		$payload = json_encode(array("list" => $pedidos));
		$response->getBody()->write($payload);

		return $response->withHeader('Content-Type', 'application/json');
	}

	public static function GetOne(Request $request, Response $response, array $args)
	{
		$pedido = Pedido::TraerPorId($args['id']);

		$payload = json_encode(array("obj" => $pedido));
		$response->getBody()->write($payload);

		return $response->withHeader('Content-Type', 'application/json');
	}

	public static function Delete(Request $request, Response $response, array $args)
	{

	}

	public static function Modify(Request $request, Response $response, array $args)
	{
		$params = $request->getParsedBody();
		$pedido = Pedido::TraerPorId($params['id']);
		
		if (!empty($pedido)) {
			Pedido::PedidoListo($params['id']);
			$payload = json_encode(array("msg" => "Pedido #{$params['id']}: LISTO"));
		} else {
			$payload = json_encode(array("msg" => "No existe un pedido con ese ID!"));
		}
		
		$response->getBody()->write($payload);
		return $response->withHeader('Content-Type', 'application\json');
	}

}