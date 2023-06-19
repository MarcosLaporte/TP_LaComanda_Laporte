<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class MwRolUsuario
{
	public function __invoke(Request $request, RequestHandler $handler): Response
	{
		$response = new Response();
		$params = $request->getParsedBody();
		$roles = ['bartender', 'cervecero', 'cocinero', 'mozo', 'socio'];

		if (isset($params['rol'])) {
			if (in_array($params['rol'], $roles, true)) {
				$response = $handler->handle($request);
			} else {
				$response->getBody()->write("Revise el rol ingresado!");
			}
		} else {
			$response->getBody()->write("Ingrese el rol del usuario!");
		}

		return $response;
	}
}