<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class MwEstadoPedido
{
	public function __invoke(Request $request, RequestHandler $handler): Response
	{
		$response = new Response();
		$params = $request->getParsedBody();

		if (isset($params['estado'])) {
			if (intval($params['estado']) == PEDIDO_PREPARACION || intval($params['estado'] == PEDIDO_LISTO)) {
				$response = $handler->handle($request);
			} else {
				$response->getBody()->write(json_encode(array("msg" => "Revise el estado de pedido! (0-1)")));
			}
		} else {
			$response->getBody()->write(json_encode(array("msg" => "Ingrese el estado del pedido!")));
		}


		return $response;
	}
}