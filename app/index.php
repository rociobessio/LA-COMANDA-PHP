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
require_once './controllers/FacturacionController.php';
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
    $group->get('/mesaMasUsada',  \MesaController::class . '::ConsultarMesaMesaMasUsada')->add(new MWSocios());
    $group->get('[/]',\MesaController::class . '::TraerTodos')->add(new MWSocios());
    // $group->put('/cambiarEstado', \MesaController::class . '::CambiarEstadoMesa')->add(new MWMozos());
    $group->get('/{id}',\MesaController::class . '::TraerUno')->add(new MWSocios());
    $group->post('[/]', \MesaController::class . '::CargarUno')->add(new MWSocios());
    $group->post('/mostrarCuentaMesa', \MesaController::class . '::MostrarCuentaMesa');//-->Me permtiria ver el total de una mesa
    $group->post('/cobrarMesa', \MesaController::class . '::CobrarMesa')->add(new MWMozos());//-->La moza cobra la mesa
    $group->post('/cerrarMesa', \MesaController::class . '::CerrarMesa')->add(new MWSocios());
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
    $group->get('[/]',\EmpleadoController::class . '::TraerTodos');
    $group->get('/exportarCSV', \EmpleadoController::class . '::ExportarEmpleados');
    $group->post('/importarCSV', \EmpleadoController::class . '::ImportarEmpleados')->add(\CSV::class . '::ValidarArchivo');
    $group->get('/{id}',\EmpleadoController::class . '::TraerUno');
    $group->post('[/]', \EmpleadoController::class . '::CargarUno');
    $group->put('/{id}', \EmpleadoController::class . '::ModificarUno');
    $group->delete('/{id}', \EmpleadoController::class . '::BorrarUno');
})->add(new MWToken())->add(new MWSocios());

// -->Pedidos
$app->group('/pedidos',function (RouteCollectorProxy $group){
    $group->get('/ConsultarDemoraPedidoCliente[/{codMesa}[/{codPedido}]]', \PedidoController::class . '::ConsultarDemoraPedidoCliente');
    $group->get('[/]',\PedidoController::class . '::TraerTodos')->add(new MWSocios());
    $group->get('/{id}',\PedidoController::class . '::TraerUno')->add(new MWMozos());
    $group->post('[/]', \PedidoController::class . '::CargarUno')->add(new MWMozos());
    $group->put('/{id}', \PedidoController::class . '::ModificarUno')->add(new MWSocios());
    $group->delete('/{id}', \PedidoController::class . '::BorrarUno')->add(new MWMozos());
    $group->post('/agregarProductosPedido/{codPedido}', \PedidoController::class . '::AgregarProductosAPedido')->add(new MWMozos());
    $group->post('/iniciar/{id}', \PedidoController::class . '::IniciarPedido')->add(new MWPreparador());
    $group->post('/finalizar/{id}', \PedidoController::class . '::FinalizarPedido')->add(new MWPreparador());
    $group->post('/entregar/{id}', \PedidoController::class . '::EntregarPedido')->add(new MWMozos());//-->Solo el mozo podrá entregar el pedido
    $group->get('/consultarPedidosPendientes/[/]', \PedidoController::class . '::ConsultarPedidosPendientes')->add(new MWPreparador());
    $group->get('/consultarDemoraPedidosSocios/[/]', \PedidoController::class . '::ConsultarPedidosDemoraSocio')->add(new MWSocios());
})->add(new MWToken());

//-->Facturaciones
// $app->group('/facturaciones',function (RouteCollectorProxy $group){
//     $group->get('[/]',\FacturacionController::class . '::MostrarFacturas')->add(new MWSocios());
// })->add(new MWToken());

//-->Encuestas
// $app->group('/encuestas',function (RouteCollectorProxy $group){
//     $group->get('[/]',\EncuestaController::class . '::MostrarMejores')->add(new MWSocios());
//     $group->get('[/]',\EncuestaController::class . '::TraerTodos')->add(new MWSocios());
// })->add(new MWToken());


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