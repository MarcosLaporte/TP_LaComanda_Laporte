<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class MwIdMesa
{
	public function __invoke(Request $request, RequestHandler $handler): Response
	{
		$response = new Response();
		$params = $request->getParsedBody();

		if (isset($params['idMesa'])) {
			$mesa = Mesa::TraerMesa($params['idMesa']);
			if (!empty($mesa)) {
				if ($mesa[0]->estado == MESA_VACIA || $mesa[0]->estado == MESA_ESPERANDO || $mesa[0]->estado == MESA_COMIENDO) {
					$response = $handler->handle($request);
				} else {
					$response->getBody()->write(json_encode(array("msg" => "La mesa no puede estar cerrada ni pagando!")));
				}
			} else {
				$response->getBody()->write(json_encode(array("msg" => "Revise el ID de la mesa!")));
			}
		} else {
			$response->getBody()->write(json_encode(array("msg" => "Ingrese el ID de la mesa!")));
		}


		return $response;
	}
}

class MwIdPedido
{
	public function __invoke(Request $request, RequestHandler $handler): Response
	{
		$response = new Response();
		$params = $request->getParsedBody();
		$pedidosId = Pedido::TraerTodosId();

		if (isset($params['idPedido'])) {
			if (in_array($params['idPedido'], $pedidosId)) {
				$response = $handler->handle($request);
			} else {
				$response->getBody()->write(json_encode(array("msg" => "Revise el ID del pedido!")));
			}
		} else {
			$response->getBody()->write(json_encode(array("msg" => "Ingrese el ID del pedido!")));
		}


		return $response;
	}
}

class MwIdProducto
{
	public function __invoke(Request $request, RequestHandler $handler): Response
	{
		$response = new Response();
		$params = $request->getParsedBody();
		$productosId = Producto::TraerTodosId();
		if (isset($params['idProducto'])) {
			if (in_array($params['idProducto'], $productosId)) {
				$response = $handler->handle($request);
			} else {
				$response->getBody()->write(json_encode(array("msg" => "Revise el ID del producto!")));
			}
		} else {
			$response->getBody()->write(json_encode(array("msg" => "Ingrese el ID del producto!")));
		}


		return $response;
	}
}