<?php

error_reporting(-1);
ini_set('display_errors', 1);

require __DIR__ . '/../vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;
use Slim\Cookie\Cookie;
use Dotenv\Dotenv;




require_once './db/accesoDB.php';

require_once './controllers/MesaController.php';

date_default_timezone_set('America/Argentina/Buenos_Aires');

//-->No funciona el dotenv
// $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Instantiate App
$app = AppFactory::create();

//-->Mesas:
$app->group('/mesas',function (RouteCollectorProxy $group){
    $group->get('[/]',\MesaController::class . '::TraerTodos');
    $group->post('[/]', \MesaController::class . '::CargarUno');
    $group->put('/{id}', \MesaController::class . '::ModificarUno');
    $group->delete('/{id}', \MesaController::class . '::BorrarUno');
});