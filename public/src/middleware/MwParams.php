<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class MwUsuario
{
	public function __invoke(Request $request, RequestHandler $handler): Response
	{
		$response = new Response();
		$params = $request->getParsedBody();

		if (isset($params['usuario']) && isset($params['clave']) && isset($params['rol'])) {
			if (
				!empty($params['usuario'])
				&& !empty($params['clave'])
				&& !empty($params['rol'])
			) {
				$response = $handler->handle($request);
			} else {
				$response->getBody()->write(json_encode(array("msg" => "Revise los datos ingresados!")));
			}
		} else {
			$response->getBody()->write(json_encode(array("msg" => "Ingrese los datos del usuario!")));
		}

		return $response;
	}
}

class MwProducto
{
	public function __invoke(Request $request, RequestHandler $handler): Response
	{
		$response = new Response();
		$params = $request->getParsedBody();

		if (isset($params['sector']) && isset($params['descripcion']) && isset($params['precio'])) {
			if (
				!empty($params['sector'])
				&& !empty($params['descripcion'])
				&& floatval($params['precio']) > 0
			) {
				$response = $handler->handle($request);
			} else {
				$response->getBody()->write(json_encode(array("msg" => "Revise los datos ingresados!")));
			}
		} else {
			$response->getBody()->write(json_encode(array("msg" => "Ingrese los datos del producto!")));
		}

		return $response;
	}
}

class MwPedido
{
	public function __invoke(Request $request, RequestHandler $handler): Response
	{
		$response = new Response();
		$params = $request->getParsedBody();

		if (isset($params['idMesa']) && isset($params['idProducto']) && isset($params['cliente'])) {
			if (
				!empty(intval($params['idMesa']))
				&& !empty(intval($params['idProducto']))
				&& !empty($params['cliente'])
			) {
				$response = $handler->handle($request);
			} else {
				$response->getBody()->write(json_encode(array("msg" => "Revise los datos ingresados!")));
			}
		} else {
			$response->getBody()->write(json_encode(array("msg" => "Ingrese los datos del pedido!")));
		}

		return $response;
	}
}

class MwFoto
{
	public function __invoke(Request $request, RequestHandler $handler): Response
	{
		$response = new Response();
		$params = $request->getParsedBody();
		$paramFiles = $request->getUploadedFiles();

		if (isset($params['idPedido']) && isset($paramFiles['foto'])) {
			if (
				!empty($params['idPedido'])
				&& $paramFiles['foto']->getError() == UPLOAD_ERR_OK
			) {
				$response = $handler->handle($request);
			} else {
				$response->getBody()->write(json_encode(array("msg" => "Revise los datos ingresados!")));
			}
		} else {
			$response->getBody()->write(json_encode(array("msg" => "Ingrese el ID del pedido y la imagen!")));
		}

		return $response;
	}
}

class MwDuracion
{
	public function __invoke(Request $request, RequestHandler $handler): Response
	{
		$response = new Response();
		$params = $request->getParsedBody();

		if (isset($params['idProducto']) && isset($params['idPedido']) && isset($params['minutos'])) {
			if (
				!empty(intval($params['idProducto']))
				&& !empty($params['idPedido'])
				&& intval($params['minutos'] > 0)
			) {
				$response = $handler->handle($request);
			} else {
				$response->getBody()->write(json_encode(array("msg" => "Revise los datos ingresados!")));
			}
		} else {
			$response->getBody()->write(json_encode(array("msg" => "Ingrese el ID del pedido y los minutos restantes para el pedido!")));
		}

		return $response;
	}
}

class MwMesa
{
	public function __invoke(Request $request, RequestHandler $handler): Response
	{
		$response = new Response();
		$params = $request->getParsedBody();

		if (isset($params['estado'])) {
			if (intval($params['estado']) >= 0 && intval($params['estado']) <= 4) {
				$response = $handler->handle($request);
			} else {
				$response->getBody()->write(json_encode(array("msg" => "Revise el estado de mesa ingresado! (1-4)")));
			}
		} else {
			$response->getBody()->write(json_encode(array("msg" => "Ingrese el estado de la mesa!")));
		}


		return $response;
	}
}

class MwEncuesta
{
	public function __invoke(Request $request, RequestHandler $handler): Response
	{
		$response = new Response();
		$params = $request->getParsedBody();

		if (isset($params['idMesa']) && isset($params['idPedido']) && isset($params['puntMesa']) && isset($params['puntRestaurante']) && isset($params['puntMozo']) && isset($params['puntCocina']) && isset($params['comentarios'])) {
			if (
				!empty(intval($params['idMesa']))
				&& !empty($params['idPedido'])
				&& self::PuntuacionValida($params['puntMesa'])
				&& self::PuntuacionValida($params['puntRestaurante'])
				&& self::PuntuacionValida($params['puntMozo'])
				&& self::PuntuacionValida($params['puntCocina'])
				&& !empty($params['comentarios'])
			) {
				$response = $handler->handle($request);
			} else {
				$response->getBody()->write(json_encode(array("msg" => "Revise los datos ingresados!")));
			}
		} else {
			$response->getBody()->write(json_encode(array("msg" => "Ingrese los datos de la encuesta!")));
		}

		return $response->withHeader('Content-Type', 'application/json');
	}

	private static function PuntuacionValida($punt)
	{
		return intval($punt) >= 1 && intval($punt) <= 10;
	}
}