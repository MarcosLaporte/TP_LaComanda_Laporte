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
			if (!empty($params['usuario']) && !empty($params['clave']) && !empty($params['rol'])) {
				$response = $handler->handle($request);
			} else {
				$response->getBody()->write("Revise los datos ingresados!");
			}
		} else {
			$response->getBody()->write("Ingrese los datos del usuario!");
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
			if (!empty($params['sector']) && !empty($params['descripcion']) && !empty($params['precio'])) {
				$response = $handler->handle($request);
			} else {
				$response->getBody()->write("Revise los datos ingresados!");
			}
		} else {
			$response->getBody()->write("Ingrese los datos del producto!");
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
				$response->getBody()->write("Revise los datos ingresados!");
			}
		} else {
			$response->getBody()->write("Ingrese los datos del pedido!");
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
				$response->getBody()->write("Revise el estado de mesa ingresado! (1-4)");
			}
		} else {
			$response->getBody()->write("Ingrese el estado de la mesa!");
		}


		return $response;
	}
}

class MwProductoOrden
{
	public function __invoke(Request $request, RequestHandler $handler): Response
	{
		$response = new Response();
		$params = $request->getParsedBody();

		if (isset($params['idPedido']) && isset($params['idProducto']) && isset($params['idMesa']) && isset($params['descripcion']) && isset($params['estado']) && isset($params['cliente']) && isset($params['foto'])) {
			if (
				!empty(intval($params['idPedido']))
				&& !empty(intval($params['idProducto']))
				&& !empty(intval($params['idMesa']))
				&& !empty($params['descripcion'])
				&& !empty(intval($params['estado']))
				&& !empty($params['cliente'])
				&& !empty($params['foto'])
			) {
				$response = $handler->handle($request);
			} else {
				$response->getBody()->write("Revise los datos ingresados!");
			}
		} else {
			$response->getBody()->write("Ingrese los datos del producto ordenado!");
		}

		return $response;
	}
}