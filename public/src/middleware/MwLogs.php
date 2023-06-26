<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

include_once __DIR__ . "\..\models\AutentificadorJWT.php";

class MwSocio
{
	public function __invoke(Request $request, RequestHandler $handler): Response
	{
		$response = new Response();
		
		if (isset($_COOKIE['token'])) {
			$token = $_COOKIE['token'];
			try {
				AutentificadorJWT::VerificarToken($token);
				$dataJWT = AutentificadorJWT::ObtenerData($token);
				if ($dataJWT->rol == "socio") {
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

class MwMozo
{
	public function __invoke(Request $request, RequestHandler $handler): Response
	{
		$response = new Response();
		
		if (isset($_COOKIE['token'])) {
			$token = $_COOKIE['token'];
			try {
				AutentificadorJWT::VerificarToken($token);
				$dataJWT = AutentificadorJWT::ObtenerData($token);
				if ($dataJWT->rol == "mozo") {
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

class MwEmpleado
{
	public function __invoke(Request $request, RequestHandler $handler): Response
	{
		$response = new Response();
		
		if (isset($_COOKIE['token'])) {
			try {
				AutentificadorJWT::VerificarToken($_COOKIE['token']);
				$response = $handler->handle($request);
			} catch (Exception $ex) {
				$response->getBody()->write(json_encode(array("msg" => "Token invalido. Inicie sesion de nuevo.\n")));
				$response->getBody()->write($ex->getMessage());
			}
		} else {
			$response->getBody()->write(json_encode(array("msg" => "No hay un token registrado. Inicie sesion.")));
		}

		return $response;
	}
}