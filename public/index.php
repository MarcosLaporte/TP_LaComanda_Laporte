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

require(__DIR__ . '\..\vendor\autoload.php');

include_once(__DIR__ . '\src\controllers\UsuarioController.php');
include_once(__DIR__ . '\src\controllers\ProductoController.php');
include_once(__DIR__ . '\src\controllers\MesaController.php');
include_once(__DIR__ . '\src\controllers\PedidoController.php');
include_once(__DIR__ . '\src\controllers\ReciboController.php');
include_once(__DIR__ . '\src\controllers\EncuestaController.php');

include_once(__DIR__ . '\src\middleware\MwParams.php');
include_once(__DIR__ . '\src\middleware\MwRolUsuario.php');
include_once(__DIR__ . '\src\middleware\MwCantSocios.php');
include_once(__DIR__ . '\src\middleware\MwSectorProducto.php');
include_once(__DIR__ . '\src\middleware\MwEstadoPedido.php');
include_once(__DIR__ . '\src\middleware\MwIds.php');
include_once(__DIR__ . '\src\middleware\MwTareasABM.php');
include_once(__DIR__ . '\src\middleware\MwDesactivarPedido.php');
include_once(__DIR__ . '\src\middleware\MwFormasDePago.php');
include_once(__DIR__ . '\src\middleware\MwLogs.php');

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
	$group->put('/estado', \UsuarioController::class . ':Modify')->add(new MwIdUsuario())->add(new MwEsSocio());
})->add(new MwEsSocio());

$app->group('/productos', function (RouteCollectorProxy $group) {
	$group->post('[/]', \ProductoController::class . ':Add')->add(new MwSectoresProductos())->add(new MwProducto())->add(new MwEsEmpleado());
	$group->get('[/]', \ProductoController::class . ':GetAll')->add(new MwEsEmpleado());
	$group->post('/csv', \ProductoController::class . ':CargarCsv')->add(new MwEsSocio());
	$group->get('/csv', \ProductoController::class . ':DescargarCsv')->add(new MwEsSocio());
});

$app->group('/mesas', function (RouteCollectorProxy $group) {
	$group->post('[/]', \MesaController::class . ':Add')->add(new MwMesa())->add(new MwEsSocio());
	$group->get('[/]', \MesaController::class . ':GetAll')->add(new MwEsEmpleado());
	$group->put('/{id}', \MesaController::class . ':Modify')->add(new MwEstadoMesas())->add(new MwMesa())->add(new MwEsEmpleado());
	$group->get('/mas', \PedidoController::class . ':GetTables')->add(new MwEsSocio());
});

$app->group('/pedidos', function (RouteCollectorProxy $group) {
	$group->post('[/]', \PedidoController::class . ':Add')->add(new MwIdProducto())->add(new MwIdMesa())->add(new MwPedido())->add(new MwEsMozo());
	$group->post('/foto', \PedidoController::class . ':AddPic')->add(new MwIdPedido())->add(new MwFoto())->add(new MwEsMozo());
	$group->get('[/]', \PedidoController::class . ':GetAll')->add(new MwEsEmpleado());
	$group->get('/{id}', \PedidoController::class . ':GetOne');
	$group->put('/mins', \PedidoController::class . ':MinutesLeft')->add(new MwRolHabilitado())->add(new MwIdProducto())->add(new MwPedidoActivo())->add(new MwIdPedido())->add(new MwDuracion());
	$group->put('/listo', \PedidoController::class . ':Modify')->add(new MwRolHabilitado())->add(new MwPedidoActivo())->add(new MwIdPedido())->add(new MwEsEmpleado());
	$group->post('/cuenta', \ReciboController::class . ':Add')->add(new MwDesactivarPedido())
		->add(new MwMesaComiendo())
		->add(new MwPedidoActivo())
		->add(new MwIdPedido())
		->add(new MwFormasDePago())
		->add(new MwRecibo())
		->add(new MwEsMozo());
	$group->post('/pagar', \ReciboController::class . ':Pay')->add(new MwIdRecibo());
});

$app->group('/encuestas', function (RouteCollectorProxy $group) {
	$group->post('[/]', \EncuestaController::class . ':Add')->add(new MwIdMesa())->add(new MwIdPedido())->add(new MwEncuesta());
	$group->get('[/]', \EncuestaController::class . ':GetAll')->add(new MwEsSocio());
});

$app->run();