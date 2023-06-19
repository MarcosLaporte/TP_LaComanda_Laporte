<?php
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';

include_once './src/controllers/UsuarioController.php';
include_once './src/controllers/ProductoController.php';
include_once './src/controllers/MesaController.php';
include_once './src/controllers/PedidoController.php';
// include_once './src/controllers/ProductoPedidoController.php';

include_once './src/middleware/MwParams.php';
include_once './src/middleware/MwRolUsuario.php';
include_once './src/middleware/MwCantSocios.php';
include_once './src/middleware/MwSectorProducto.php';
include_once './src/middleware/MwIdsPedido.php';

// Instantiate App
$app = AppFactory::create();
$app->setBasePath('/public');
$app->addErrorMiddleware(true, true, true);
$app->addBodyParsingMiddleware();

$app->get('[/]', function (Request $request, Response $response, array $args) {
	$payload = json_encode(array("msg" => "Marcos Laporte - La Comanda"));
    $response->getBody()->write($payload);

	return $response->withHeader('Content-Type', 'application/json');
});

$app->group('/usuarios', function (RouteCollectorProxy $group) {
	$group->post('[/]', \UsuarioController::class . ':CargarUsuario')->add(new MwCantSocios())->add(new MwRolUsuario())->add(new MwUsuario());
	$group->get('[/]', \UsuarioController::class . ':TraerUsuarios');
});

$app->group('/productos', function (RouteCollectorProxy $group) {
	$group->post('[/]', \ProductoController::class . ':CargarProducto')->add(new MwSectoresProductos())->add(new MwProducto());
	$group->get('[/]', \ProductoController::class . ':TraerProductos');
});

$app->group('/mesas', function (RouteCollectorProxy $group) {
	$group->post('[/]', \MesaController::class . ':CargarMesa')->add(new MwMesa());
	$group->get('[/]', \MesaController::class . ':TraerMesas');
});

$app->group('/pedidos', function (RouteCollectorProxy $group) {
	$group->post('[/]', \PedidoController::class . ':CargarPedido')->add(new MwIdProdPedido())->add(new MwIdMesaPedido())->add(new MwPedido());
	$group->get('[/]', \PedidoController::class . ':TraerPedidos');
});

$app->run();
