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
		$estados = [PEDIDO_PREPARACION, PEDIDO_LISTO];

		if (isset($params['estado'])) {
			if (intval($params['estado']) == 0 || intval($params['estado'] == 1)) {
				$response = $handler->handle($request);
			} else {
				$response->getBody()->write("Revise el estado de pedido! (0-1)");
			}
		} else {
			$response->getBody()->write("Ingrese el estado del pedido!");
		}


		return $response;
	}
}