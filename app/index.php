<?php
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;
use Slim\Cookie\Cookie;


require __DIR__ . '/../vendor/autoload.php';

require_once './db/accesoDB.php'; 
require_once './controllers/PedidoController.php';
require_once './controllers/EmpleadoController.php';
require_once './controllers/ProductoController.php';
require_once "./controllers/MesaController.php"; 

date_default_timezone_set('America/Argentina/Buenos_Aires');

//-->No funciona el dotenv
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();


// Instantiate App
$app = AppFactory::create();

// $app->setBasePath('/Comanda');
// echo 'aaaa';
$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();

$errorMiddleware = $app->addErrorMiddleware(true, true, true);

//-->Mesas:
$app->group('/mesas',function (RouteCollectorProxy $group){
    $group->get('[/]',\MesaController::class . '::TraerTodos');
    $group->get('/{id}',\MesaController::class . '::TraerUno');
    $group->post('[/]', \MesaController::class . '::CargarUno');
    $group->put('/{id}', \MesaController::class . '::ModificarUno');
    $group->delete('/{id}', \MesaController::class . '::BorrarUno');
});

//-->Productos
$app->group('/productos',function (RouteCollectorProxy $group){
    $group->get('[/]',\ProductoController::class . '::TraerTodos');
    $group->get('/{id}',\ProductoController::class . '::TraerUno');
    $group->post('[/]', \ProductoController::class . '::CargarUno');
    $group->put('/{id}', \ProductoController::class . '::ModificarUno');
    $group->delete('/{id}', \ProductoController::class . '::BorrarUno');
});

// -->Empleados
$app->group('/empleados',function (RouteCollectorProxy $group){
    $group->get('[/]',\EmpleadoController::class . '::TraerTodos');
    $group->get('/{id}',\EmpleadoController::class . '::TraerUno');
    $group->post('[/]', \EmpleadoController::class . '::CargarUno');
    $group->put('/{id}', \EmpleadoController::class . '::ModificarUno');
    $group->delete('/{id}', \EmpleadoController::class . '::BorrarUno');
});

// -->Pedidos
$app->group('/pedidos',function (RouteCollectorProxy $group){
    $group->get('[/]',\PedidoController::class . '::TraerTodos');
    $group->get('/{id}',\PedidoController::class . '::TraerUno');
    $group->post('[/]', \PedidoController::class . '::CargarUno');
    $group->put('/{id}', \PedidoController::class . '::ModificarUno');
    $group->delete('/{id}', \PedidoController::class . '::BorrarUno');
});

$app->get('[/]', function (Request $request, Response $response) {
    $payload = json_encode(array("TP" => "Comanda"));
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  });
  
  $app->run();