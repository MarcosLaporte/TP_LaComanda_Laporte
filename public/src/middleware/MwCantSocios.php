<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class MwCantSocios
{
	public function __invoke(Request $request, RequestHandler $handler): Response
	{
		$response = new Response();
		$params = $request->getParsedBody();


		if (isset($params['rol'])) {
			if (!strcasecmp($params['rol'], "socio")) {
				if (count(Usuario::TraerPorRol("socio")) < 3) {
					$response = $handler->handle($request);
				} else {
					$response->getBody()->write(json_encode(array("msg" => "No se pueden ingresar mas socios!")));
				}
			} else {
				$response = $handler->handle($request);
			}
		} else {
			$response->getBody()->write(json_encode(array("msg" => "Ingrese el rol del empleado!")));
		}


		return $response;
	}
}