<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

include_once __DIR__ . "\..\models\Pedido.php";
include_once __DIR__ . "\..\models\Recibo.php";

class MwDesactivarPedido
{
	public function __invoke(Request $request, RequestHandler $handler): Response
	{
		$response = $handler->handle($request);

		if ($response->getStatusCode() == 200) {
			$params = $request->getParsedBody();
			$recibo = Recibo::TraerPorId($params['numeroPedido'])[0];
			
			$idPedido = $recibo->idPedido;
			Pedido::PedidoPago($idPedido);

			$response = new Response();
			$response->getBody()->write(json_encode(array("msg" => "Pedido desactivado!")));
		}

		return $response;
	}
}