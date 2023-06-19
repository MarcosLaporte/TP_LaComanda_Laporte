<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
include_once __DIR__ . "/../models/Pedido.php";

class PedidoController extends Pedido
{
	public static function CargarPedido(Request $request, Response $response, array $args)
	{
		$params = $request->getParsedBody();

		$pedido = new Pedido();
		$pedido->idMesa = $params['idMesa'];
		$pedido->idProducto = $params['idProducto'];
		$pedido->estado = PEDIDO_PREPARACION;
		$pedido->cliente = $params['cliente'];
		Archivo::GuardarImagenDePeticion("public/src/Fotos_Mesas/", $pedido->cliente, 'foto');
		$pedido->foto = "public/src/Fotos_Mesas/$pedido->cliente.jpg";
		$pedido->CrearPedido();

		$payload = json_encode(array("msg" => "Pedido creado con exito"));
	    $response->getBody()->write($payload);

		return $response->withHeader('Content-Type', 'application/json');
	}

	public static function TraerPedidos(Request $request, Response $response, array $args)
	{
		$pedidos = Pedido::TraerTodos();
		
		$payload = json_encode(array("list" => $pedidos));
		$response->getBody()->write($payload);

		return $response->withHeader('Content-Type', 'application/json');
	}
}