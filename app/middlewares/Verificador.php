<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class Verificador {

    public static function ValidarPreparador(Request $request, RequestHandler $handler) {
        $params = $request->getParsedBody();
        // $params = $request->getQueryParams();//-->Para getk
        $response = new Response();
        
        echo 'en validar PREPARADOR DE PEDIDO!';
        if(isset($params['rol'])){
            $rol = $params['rol'];
            if ($rol === 'Mozo' || $rol === 'Socio' ||$rol === 'Cocinero' ||$rol === 'Bartender' ||
                $rol === 'Cervecero' || $rol === 'Candybar') {
                return $handler->handle($request);
            } 
            else {
                $response->getBody()->write("No puede realizar la accion NO es un preparador valido.");
                $response = $response->withStatus(403);
            }
        }else
            return json_encode(array("Mensaje" => "Se necesita el ingreso del rol"));

        return $response;
    }

    public static function ValidarMozo(Request $request, RequestHandler $handler) {
        // $parametros = $request->getQueryParams();
        // $existingContent = json_decode($response->getBody());
        $parametros = $request->getQueryParams();
        echo 'en validar mozo!';
        if (isset($parametros['rol'])) {
            $rol = $parametros['rol'];
            if ($rol === 'mozo') {
                return $handler->handle($request);
            } else {
                $response = new Response();
                $payload = json_encode(array('mensaje' => 'No sos mozo no podes realizar la accion'));
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/json');
            }
        } else {
            return $handler->handle($request);
        }
    }

    public static function ValidarSocio(Request $request, RequestHandler $handler) {
        $parametros = $request->getQueryParams();//-->Para get
        // $parametros = $request->getParsedBody();//-->Para POST
        // $existingContent = json_decode($response->getBody());
        echo 'en validar socio!';
        if(isset($parametros['rol'])){ 
            $rol = $parametros['rol'];
            if ($rol === 'socio') {
                return $handler->handle($request);
            } 
            else {
                $response = new Response();
                $payload = json_encode(array('mensaje' => 'No sos SOCIO no podes realizar la accion'));
                $response->getBody()->write($payload);
            }
        }else
            return json_encode(array("Mensaje" => "Se necesita el ingreso del rol"));

        return $response->withHeader('Content-Type', 'application/json');
    }
}
