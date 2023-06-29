<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

include_once __DIR__ . "\..\models\Producto.php";
include_once __DIR__ . "\..\models\Pedido.php";
include_once __DIR__ . "\..\models\ProductoPedido.php";
include_once __DIR__ . "\..\models\Mesa.php";
include_once __DIR__ . "\..\models\Archivos.php";
include_once __DIR__ . "\..\interfaces\IPdo.php";

class PedidoController extends Pedido implements IPdo
{
	public static function Add(Request $request, Response $response, array $args)
	{
		$params = $request->getParsedBody();
		$producto = Producto::TraerPorId($params['idProducto'])[0];

		$nombreCookie = "Mesa" . $params['idMesa'];
		$esNuevoPedido = isset($_COOKIE[$nombreCookie]) ? false : true;
		if ($esNuevoPedido) {
			$pedido = new Pedido();
			$pedido->id = substr(uniqid(), -5);
			$pedido->idMesa = $params['idMesa'];
			$pedido->precio = floatval($producto->precio);
			$pedido->estado = PEDIDO_PREPARACION;
			$pedido->CrearPedido();

			$idPedido = $pedido->id;
			$jwtMesa = AutentificadorJWT::CrearToken(array('idPedido' => $idPedido, 'fecha' => date('Y-m-d'), 'hora' => date('H:i:s')));
			setcookie($nombreCookie, $jwtMesa, 0, '/', "localhost", false, true);
		} else {
			$jwtMesa = AutentificadorJWT::ObtenerData($_COOKIE[$nombreCookie]);
			$pedido = Pedido::TraerPorId($jwtMesa->idPedido)[0];
			Pedido::IncrementarPrecio($jwtMesa->idPedido, $producto->precio);
		}

		self::AddProducto($params['idProducto'], $pedido->idMesa, $pedido->id, $params['cliente']);
		Mesa::ModificarEstado($pedido->idMesa, MESA_ESPERANDO);

		$payload = json_encode(array("msg" => "Pedido creado con exito"));
		$response->getBody()->write($payload);

		return $response->withHeader('Content-Type', 'application/json');
	}

	private static function AddProducto($idProducto, $idMesa, $idPedido, $cliente)
	{
		$pedidoProd = new ProductoPedido();
		$pedidoProd->idProducto = $idProducto;
		$pedidoProd->idMesa = $idMesa;
		$pedidoProd->idPedido = $idPedido;
		$pedidoProd->cliente = $cliente;

		$pedidoProd->CrearProductoPedido();
	}

	public static function AddPic(Request $request, Response $response, array $args)
	{
		$params = $request->getParsedBody();
		$pedido = Pedido::TraerPorId($params['idPedido'])[0];
		$uriFoto = Archivo::GuardarArchivoPeticion("src/FotosMesas/", "M{$pedido->idMesa}_{$params['idPedido']}", 'foto', '.jpg');

		Pedido::AgregarUriFoto($params['idPedido'], $uriFoto);
		$payload = json_encode(array("msg" => "Foto agregada con exito"));
		$response->getBody()->write($payload);

		return $response->withHeader('Content-Type', 'application/json');
	}

	public static function MinutesLeft(Request $request, Response $response, array $args)
	{
		$params = $request->getParsedBody();
		$pedido = Pedido::TraerPorId($params['idPedido']);
		$producto = Producto::TraerPorId($params['idProducto']);

		if (!empty($pedido)) {
			ProductoPedido::ModificarDuracion($params['idProducto'], $params['idPedido'], $params['minutos']);
			Pedido::ModificarDuracion($params['idPedido']);
			$payload = json_encode(array("msg" => "'{$producto[0]->descripcion}' de Pedido #{$params['idPedido']}: {$params['minutos']} minutos restantes."));
		} else {
			$payload = json_encode(array("msg" => "No existe un pedido con ese ID!"));
		}

		$response->getBody()->write($payload);
		return $response->withHeader('Content-Type', 'application\json');
	}

	public static function GetAll(Request $request, Response $response, array $args)
	{
		$pedidos = Pedido::TraerTodos();

		$payload = json_encode(array("list" => $pedidos));
		$response->getBody()->write($payload);

		return $response->withHeader('Content-Type', 'application/json');
	}

	public static function GetOne(Request $request, Response $response, array $args)
	{
		$pedido = Pedido::TraerPorId($args['id']);

		$payload = json_encode(array("obj" => $pedido));
		$response->getBody()->write($payload);

		return $response->withHeader('Content-Type', 'application/json');
	}

	public static function Delete(Request $request, Response $response, array $args)
	{

	}

	public static function Modify(Request $request, Response $response, array $args)
	{
		$params = $request->getParsedBody();
		$idProducto = $params['idProducto'];
		$producto = Producto::TraerPorId($idProducto);

		if (isset($params['idPedido'])) {
			$idPedido = $params['idPedido'];
			$prodPedido = ProductoPedido::BuscarEnPedido($idProducto, $idPedido);
			$productoExisteEnPedido = empty($prodPedido) ? false : true;

			if ($productoExisteEnPedido) {
				ProductoPedido::ProductoListo($idProducto, $idPedido);
				$payload = json_encode(array("msg" => "'{$producto[0]->descripcion}' de Pedido #{$idPedido}: LISTO"));

				if (ProductoPedido::CantPendientes($idPedido) == 0) {
					Pedido::PedidoListo($idPedido);
					$payload = json_encode(array("msg" => "Pedido #{$idPedido} listo para servir!"));
				}
			} else {
				$payload = json_encode(array("msg" => "Pedido #{$idPedido} no tiene asignado '{$producto[0]->descripcion}'"));
			}
		} else {
			$payload = json_encode(array("msg" => "Ingrese el ID del pedido!"));
		}


		$response->getBody()->write($payload);
		return $response->withHeader('Content-Type', 'application\json');
	}
}