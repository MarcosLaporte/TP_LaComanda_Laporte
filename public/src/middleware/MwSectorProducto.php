<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class MwSectoresProductos
{
	public function __invoke(Request $request, RequestHandler $handler): Response
	{
		$response = new Response();
		$params = $request->getParsedBody();

		if (isset($params['sector'])) {
			if (intval($params['sector']) >= 1 && intval($params['sector']) <= 4) {
				$response = $handler->handle($request);
			} else {
				$response->getBody()->write(json_encode(array("msg" => "Revise el sector de producto ingresado! (1-4)")));
			}
		} else {
			$response->getBody()->write(json_encode(array("msg" => "Ingrese el sector al que pertenece el producto!")));
		}


		return $response;
	}
}