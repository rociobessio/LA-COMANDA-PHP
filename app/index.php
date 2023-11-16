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

require_once "./middlewares/MWSocios.php"; 
require_once "./middlewares/MWMozos.php"; 
require_once "./middlewares/MWPreparador.php"; 
require_once "./middlewares/MWToken.php"; 
require_once "./middlewares/Logger.php";

require_once './db/accesoDB.php'; 
require_once './controllers/PedidoController.php';
require_once './controllers/EmpleadoController.php';
require_once './controllers/ProductoController.php';
require_once "./controllers/MesaController.php"; 

date_default_timezone_set('America/Argentina/Buenos_Aires');

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();


// Instantiate App
$app = AppFactory::create();

$app->addBodyParsingMiddleware();

$errorMiddleware = $app->addErrorMiddleware(true, true, true);

//-->Mesas:
$app->group('/mesas', function (RouteCollectorProxy $group) {
    $group->get('[/]',\MesaController::class . '::TraerTodos')->add(new MWSocios());
    $group->put('/cambiarEstado', \MesaController::class . '::CambiarEstadoMesa')->add(new MWMozos());
    $group->get('/{id}',\MesaController::class . '::TraerUno')->add(new MWSocios());
    $group->post('[/]', \MesaController::class . '::CargarUno')->add(new MWSocios());
    $group->delete('/{id}', \MesaController::class . '::BorrarUno')->add(new MWSocios());
})->add(new MWToken());

//-->Productos
$app->group('/productos',function (RouteCollectorProxy $group){
    $group->get('[/]',\ProductoController::class . '::TraerTodos')->add(new MWSocios());
    $group->get('/{id}',\ProductoController::class . '::TraerUno')->add(new MWSocios());
    $group->post('[/]', \ProductoController::class . '::CargarUno')->add(new MWSocios());
    $group->put('/{id}', \ProductoController::class . '::ModificarUno')->add(new MWSocios());
    $group->delete('/{id}', \ProductoController::class . '::BorrarUno')->add(new MWSocios());
})->add(new MWToken());

// -->Empleados
$app->group('/empleados',function (RouteCollectorProxy $group){
    $group->get('[/]',\EmpleadoController::class . '::TraerTodos')->add(new MWSocios());
    $group->get('/exportarCSV', \EmpleadoController::class . '::ExportarEmpleados')->add(new MWSocios());
    $group->post('/importarCSV', \EmpleadoController::class . '::ImportarEmpleados')->add(new MWSocios())->add(\CSV::class . '::ValidarArchivo');
    $group->get('/{id}',\EmpleadoController::class . '::TraerUno')->add(new MWSocios());
    $group->post('[/]', \EmpleadoController::class . '::CargarUno')->add(new MWSocios());
    $group->put('/{id}', \EmpleadoController::class . '::ModificarUno')->add(new MWSocios());
    $group->delete('/{id}', \EmpleadoController::class . '::BorrarUno')->add(new MWSocios());
})->add(new MWToken());

// -->Pedidos
$app->group('/pedidos',function (RouteCollectorProxy $group){
    $group->get('/consultarDemoraPedido/{idMesa,codPedido}', \PedidoController::class . '::ConsultarDemoraPedido');//->add(new MWPreparador());
    $group->get('[/]',\PedidoController::class . '::TraerTodos')->add(new MWSocios());
    $group->get('/{id}',\PedidoController::class . '::TraerUno')->add(new MWMozos());
    $group->post('[/]', \PedidoController::class . '::CargarUno')->add(new MWMozos());
    $group->put('/{id}', \PedidoController::class . '::ModificarUno')->add(new MWMozos());
    $group->delete('/{id}', \PedidoController::class . '::BorrarUno')->add(new MWMozos());
    $group->post('/iniciar/{id}', \PedidoController::class . '::IniciarPedido')->add(new MWPreparador());
    $group->post('/finalizar/{id}', \PedidoController::class . '::FinalizarPedido')->add(new MWPreparador());
    $group->post('/entregar/{id}', \PedidoController::class . '::EntregarPedido')->add(new MWPreparador());
    $group->get('/consultarPedidosPendientes/[/]', \PedidoController::class . '::ConsultarPedidosPendientes')->add(new MWPreparador());
})->add(new MWToken());

//-->Tabla intermedia Pedido-Producto

//-->Login para conseguir token
$app->group('/login', function (RouteCollectorProxy $group) {
    $group->post('[/]', \EmpleadoController::class . '::LoguearEmpleado')->add(\Logger::class . '::ValidarEmpleado');
});
  
$app->get('[/]', function (Request $request, Response $response) {
    $payload = json_encode(array("TP" => "Comanda"));
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});
  
$app->run();