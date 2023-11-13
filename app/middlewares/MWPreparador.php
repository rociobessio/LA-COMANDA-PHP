<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class MWPreparador{
    public function __invoke(Request $request,RequestHandler $handler) : Response {
        $parametros = $request->getQueryParams();
        echo 'en validar socio!';
        echo 'en validar PREPARADOR DE PEDIDO!';
        if(isset($parametros['rol'])){
            $rol = $parametros['rol'];
            if ($rol === 'Mozo' || $rol === 'Socio' ||$rol === 'Cocinero' ||$rol === 'Bartender' ||
                $rol === 'Cervecero' || $rol === 'Candybar') {
                return $handler->handle($request);
            } 
            else {
                $response = new Response();
                $payload = json_encode(array('mensaje' => 'No sos un pereparador valido no podes realizar la accion'));
                $response->getBody()->write($payload);
            }
        }else
            return json_encode(array("Mensaje" => "Se necesita el ingreso del rol"));

        return $response;
    }
}