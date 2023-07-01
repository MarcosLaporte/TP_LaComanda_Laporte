<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

include_once(__DIR__ . "\..\models\Mesa.php");
include_once(__DIR__ . "\..\models\Pedido.php");
include_once(__DIR__ . "\..\util\AutentificadorJWT.php");

class MwEstadoMesas
{
	public function __invoke(Request $request, RequestHandler $handler): Response
	{
		$response = new Response();
		$params = $request->getParsedBody();
		$token = $_COOKIE['token'];

		try {
			AutentificadorJWT::VerificarToken($token);
			$dataJWT = AutentificadorJWT::ObtenerData($token);
			switch ($params['estado']) {
				case MESA_VACIA:
				case MESA_CERRADA:
					if (!strcasecmp($dataJWT->rol, "socio")) {
						$response = $handler->handle($request);
					} else {
						$response->getBody()->write(json_encode(array("msg" => "Solo los socios pueden realizar esta accion!")));
					}
					break;
				case MESA_ESPERANDO:
				case MESA_COMIENDO:
				case MESA_PAGANDO:
					if (!strcasecmp($dataJWT->rol, "mozo")) {
						$response = $handler->handle($request);
					} else {
						$response->getBody()->write(json_encode(array("msg" => "Solo los mozos pueden realizar esta accion!")));
					}
					break;
				default:
					$response->getBody()->write(json_encode(array("msg" => "No existe ese estado de mesa.")));
			}
		} catch (Exception $ex) {
			$response->getBody()->write($ex->getMessage());
		}

		return $response;
	}
}

class MwRolHabilitado
{
	public function __invoke(Request $request, RequestHandler $handler): Response
	{
		$response = new Response();
		$params = $request->getParsedBody();

		if (isset($params['idPedido']) && isset($params['idProducto'])) {
			$pedido = Pedido::TraerPorId($params['idPedido']);
			if (!empty($pedido)) {
				$producto = Producto::TraerPorId($params['idProducto'])[0];
				$token = $_COOKIE['token'];
				try {
					AutentificadorJWT::VerificarToken($token);
					$dataJWT = AutentificadorJWT::ObtenerData($token);
					if (Pedido::SectorYEmpleadoValido($producto->sector, $dataJWT->rol)) {
						$response = $handler->handle($request);
					} else {
						$response->getBody()->write(json_encode(array("msg" => "Esta tarea no le corresponde a un $dataJWT->rol.")));
					}
				} catch (Exception $ex) {
					$response->getBody()->write($ex->getMessage());
				}
			} else {
				$response->getBody()->write(json_encode(array("msg" => "No existe un pedido con ese ID.")));
			}
		} else {
			$response->getBody()->write(json_encode(array("msg" => "Ingrese el ID del pedido.")));
		}

		return $response;
	}
}

class MwMesaComiendo
{
	public function __invoke(Request $request, RequestHandler $handler): Response
	{
		$response = new Response();
		$params = $request->getParsedBody();

		if (isset($params['idPedido'])) {
			$pedido = Pedido::TraerPorId($params['idPedido'])[0];
			$mesa = Mesa::TraerMesa($pedido->idMesa)[0];
			if ($mesa->estado == MESA_COMIENDO) {
				$response = $handler->handle($request);
			} else {
				$response->getBody()->write(json_encode(array("msg" => "La mesa debe estar comiendo para pedir la cuenta!")));
			}
		} else {
			$response->getBody()->write(json_encode(array("msg" => "Ingrese el ID del pedido!")));
		}


		return $response;
	}
}