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

require_once "./middlewares/Verificador.php"; 

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
// $app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();

$errorMiddleware = $app->addErrorMiddleware(true, true, true);

//-->Mesas:
$app->group('/mesas',function (RouteCollectorProxy $group){
    $group->get('[/]',\MesaController::class . '::TraerTodos')->add(\Verificador::class . '::ValidarSocio');
    $group->get('/{id}',\MesaController::class . '::TraerUno')->add(\Verificador::class . '::ValidarSocio');
    $group->post('[/]', \MesaController::class . '::CargarUno')->add(\Verificador::class . '::ValidarSocio');
    $group->put('/{id}', \MesaController::class . '::ModificarUno')->add(\Verificador::class . '::ValidarSocio');
    $group->delete('/{id}', \MesaController::class . '::BorrarUno')->add(\Verificador::class . '::ValidarSocio');
});

//-->Productos
$app->group('/productos',function (RouteCollectorProxy $group){
    $group->get('[/]',\ProductoController::class . '::TraerTodos')->add(\Verificador::class . '::ValidarSocio');
    $group->get('/{id}',\ProductoController::class . '::TraerUno')->add(\Verificador::class . '::ValidarSocio');
    $group->post('[/]', \ProductoController::class . '::CargarUno')->add(\Verificador::class . '::ValidarSocio');
    $group->put('/{id}', \ProductoController::class . '::ModificarUno')->add(\Verificador::class . '::ValidarSocio');
    $group->delete('/{id}', \ProductoController::class . '::BorrarUno')->add(\Verificador::class . '::ValidarSocio');
});

// -->Empleados
$app->group('/empleados',function (RouteCollectorProxy $group){
    $group->get('[/]',\EmpleadoController::class . '::TraerTodos')->add(\Verificador::class . '::ValidarSocio');
    $group->get('/{id}',\EmpleadoController::class . '::TraerUno')->add(\Verificador::class . '::ValidarSocio');
    $group->post('[/]', \EmpleadoController::class . '::CargarUno')->add(\Verificador::class . '::ValidarSocio');
    $group->put('/{id}', \EmpleadoController::class . '::ModificarUno')->add(\Verificador::class . '::ValidarSocio');
    $group->delete('/{id}', \EmpleadoController::class . '::BorrarUno')->add(\Verificador::class . '::ValidarSocio');
});

// -->Pedidos
$app->group('/pedidos',function (RouteCollectorProxy $group){
    $group->get('[/]',\PedidoController::class . '::TraerTodos')->add(\Verificador::class . '::ValidarSocio');
    $group->get('/{id}',\PedidoController::class . '::TraerUno')->add(\Verificador::class . '::ValidarMozo');
    $group->post('[/]', \PedidoController::class . '::CargarUno')->add(\Verificador::class . '::ValidarMozo');
    $group->put('/{id}', \PedidoController::class . '::ModificarUno')->add(\Verificador::class . '::ValidarMozo');
    $group->delete('/{id}', \PedidoController::class . '::BorrarUno')->add(\Verificador::class . '::ValidarMozo');
    $group->post('/iniciar/{id}', \PedidoController::class . '::IniciarPedido')->add(\Verificador::class . '::ValidarPreparador');
    $group->post('/finalizar/{id}', \PedidoController::class . '::FinalizarPedido')->add(\Verificador::class . '::ValidarPreparador');
    $group->post('/entregar/{id}', \PedidoController::class . '::EntregarPedido')->add(\Verificador::class . '::ValidarPreparador');
    $group->post('/consultarDemoraPedido/{idMesa,idProducto}', \PedidoController::class . '::ConsultarDemoraPedido');//->add(\Verificador::class . '::ValidarPreparador');
});

$app->get('[/]', function (Request $request, Response $response) {
    $payload = json_encode(array("TP" => "Comanda"));
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  });
  
  $app->run();