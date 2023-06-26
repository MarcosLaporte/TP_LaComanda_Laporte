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

require __DIR__ . '\..\vendor\autoload.php';

include_once '.\src\controllers\UsuarioController.php';
include_once '.\src\controllers\ProductoController.php';
include_once '.\src\controllers\MesaController.php';
include_once '.\src\controllers\PedidoController.php';
include_once '.\src\controllers\EncuestaController.php';

include_once '.\src\middleware\MwParams.php';
include_once '.\src\middleware\MwRolUsuario.php';
include_once '.\src\middleware\MwCantSocios.php';
include_once '.\src\middleware\MwSectorProducto.php';
include_once '.\src\middleware\MwIds.php';
include_once '.\src\middleware\MwTareasABM.php';
include_once '.\src\middleware\MwLogs.php';

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

$app->post('/login', \UsuarioController::class . ':Login');

$app->group('/usuarios', function (RouteCollectorProxy $group) {
	$group->post('[/]', \UsuarioController::class . ':Add')->add(new MwCantSocios())->add(new MwRolUsuario())->add(new MwUsuario());
	$group->get('[/]', \UsuarioController::class . ':GetAll');
})->add(new MwSocio());

$app->group('/productos', function (RouteCollectorProxy $group) {
	$group->post('[/]', \ProductoController::class . ':Add')->add(new MwSectoresProductos())->add(new MwProducto())->add(new MwEmpleado());
	$group->get('[/]', \ProductoController::class . ':GetAll')->add(new MwEmpleado());
	$group->post('/csv', \ProductoController::class . ':CargarCsv')->add(new MwSocio());
	$group->get('/csv', \ProductoController::class . ':DescargarCsv')->add(new MwSocio());
});

$app->group('/mesas', function (RouteCollectorProxy $group) {
	$group->post('[/]', \MesaController::class . ':Add')->add(new MwMesa())->add(new MwSocio());
	$group->get('[/]', \MesaController::class . ':GetAll')->add(new MwEmpleado());
	$group->put('/{id}', \MesaController::class . ':Modify')->add(new MwEstadoMesas())->add(new MwMesa())->add(new MwEmpleado());
});

$app->group('/pedidos', function (RouteCollectorProxy $group) {
	$group->post('[/]', \PedidoController::class . ':Add')->add(new MwIdProd())->add(new MwIdMesa())->add(new MwPedido())->add(new MwMozo());
	$group->get('[/]', \PedidoController::class . ':GetAll')->add(new MwEmpleado());
	$group->get('/{id}', \PedidoController::class . ':GetOne');
	$group->put('/{id}', \PedidoController::class . ':Modify')->add(new MwEstadoPedido())->add(new MwEmpleado());
});

$app->group('/encuestas', function (RouteCollectorProxy $group) {
	$group->post('[/]', \EncuestaController::class . ':Add')->add(new MwEncuesta())->add(new MwIdMesa())->add(new MwIdPedido());
	$group->get('[/]', \EncuestaController::class . ':GetAll')->add(new MwSocio());
});

$app->run();
