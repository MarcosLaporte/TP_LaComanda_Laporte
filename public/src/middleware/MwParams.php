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
		$paramFiles = $request->getUploadedFiles();

		if (isset($params['idMesa']) && isset($params['idProducto']) && isset($params['cliente']) && isset($paramFiles['foto'])) {
			if (
				!empty(intval($params['idMesa']))
				&& !empty(intval($params['idProducto']))
				&& !empty($params['cliente'])
				&& $paramFiles['foto']->getError() == UPLOAD_ERR_OK
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

class MwMesa
{
	public function __invoke(Request $request, RequestHandler $handler): Response
	{
		$response = new Response();
		$params = $request->getParsedBody();

		if (isset($params['estado'])) {
			if (intval($params['estado']) >= 1 && intval($params['estado']) <= 4) {
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

class MwLogin
{
	public function __invoke(Request $request, RequestHandler $handler): Response
	{
		$response = new Response();
		$params = $request->getParsedBody();

		if (isset($params['id']) && isset($params['usuario']) && isset($params['clave'])) {
			$idUser = $params['id'];
			$usuario = Usuario::TraerPorId($idUser);
			if (!empty($usuario)) {
				if (
					!strcasecmp($usuario[0]->usuario, $params['usuario'])
					&& password_verify($params['clave'], $usuario[0]->clave)
				) {
					$response = $handler->handle($request);
				} else {
					$response->getBody()->write(json_encode(array("msg" => "El usuario y la clave no coinciden!")));
				}
			} else {
				$response->getBody()->write(json_encode(array("msg" => "No existe un usuario con ese id!")));
			}
		} else {
			$response->getBody()->write(json_encode(array("msg" => "Ingrese los datos para el login!")));
		}

		return $response->withHeader('Content-Type', 'application/json');
	}
}