<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class MwFormasDePago
{
	public function __invoke(Request $request, RequestHandler $handler): Response
	{
		$response = new Response();
		$params = $request->getParsedBody();
		$formasDePago = ["efectivo", "transferencia", "debito", "credito"];

		if (isset($params['formaDePago'])) {
			if (in_array($params['formaDePago'], $formasDePago, true)) {
				$response = $handler->handle($request);
			} else {
				$response->getBody()->write(json_encode(array("msg" => "Revise la forma de pago ingresada!")));
			}
		} else {
			$response->getBody()->write(json_encode(array("msg" => "Ingrese la forma de pago!")));
		}

		return $response;
	}
}