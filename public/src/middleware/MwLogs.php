<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

include_once(__DIR__ . "\..\util\AutentificadorJWT.php");

class MwEsSocio
{
	public function __invoke(Request $request, RequestHandler $handler): Response
	{
		$response = new Response();

		if (isset($_COOKIE['token'])) {
			$token = $_COOKIE['token'];
			try {
				AutentificadorJWT::VerificarToken($token);
				$dataJWT = AutentificadorJWT::ObtenerData($token);
				if (!strcasecmp($dataJWT->rol, "socio")) {
					$response = $handler->handle($request);
				} else {
					$response->getBody()->write(json_encode(array("msg" => "Solo los socios pueden realizar esta accion!")));
				}
			} catch (Exception $ex) {
				$response->getBody()->write($ex->getMessage());
			}
		} else {
			$response->getBody()->write(json_encode(array("msg" => "No hay un token registrado. Inicie sesion.")));
		}

		return $response;
	}
}

class MwEsMozo
{
	public function __invoke(Request $request, RequestHandler $handler): Response
	{
		$response = new Response();

		if (isset($_COOKIE['token'])) {
			$token = $_COOKIE['token'];
			try {
				AutentificadorJWT::VerificarToken($token);
				$dataJWT = AutentificadorJWT::ObtenerData($token);
				if (!strcasecmp($dataJWT->rol, "mozo")) {
					$response = $handler->handle($request);
				} else {
					$response->getBody()->write(json_encode(array("msg" => "Solo los mozos pueden realizar esta accion!")));
				}
			} catch (Exception $ex) {
				$response->getBody()->write($ex->getMessage());
			}
		} else {
			$response->getBody()->write(json_encode(array("msg" => "No hay un token registrado. Inicie sesion.")));
		}

		return $response;
	}
}

class MwEsEmpleado
{
	public function __invoke(Request $request, RequestHandler $handler): Response
	{
		$response = new Response();

		if (isset($_COOKIE['token'])) {
			try {
				AutentificadorJWT::VerificarToken($_COOKIE['token']);
				$response = $handler->handle($request);
			} catch (Exception $ex) {
				$response->getBody()->write($ex->getMessage());
			}
		} else {
			$response->getBody()->write(json_encode(array("msg" => "No hay un token registrado. Inicie sesion.")));
		}

		return $response;
	}
}