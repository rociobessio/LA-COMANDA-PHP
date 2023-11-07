<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class Verificador {
    public static function ValidarMozo(Request $request, RequestHandler $handler) {
        $params = $request->getParsedBody();
        $response = new Response();
        // $existingContent = json_decode($response->getBody());
        echo 'en validar mozo!';
        if(isset($params['rol'])){
            $rol = $params['rol'];
            if ($rol === 'mozo') {
                return $handler->handle($request);
            } 
            else {
                $response->getBody()->write("No puede realizar la accion NO es mozo.");
                $response = $response->withStatus(403);
            }
        }else
            return json_encode(array("Mensaje" => "Se necesita el ingreso del rol"));

        return $response;
    }

    public static function ValidarSocio(Request $request, RequestHandler $handler) {
        $params = $request->getParsedBody();
        $response = new Response();
        // $existingContent = json_decode($response->getBody());
        echo 'en validar socio!';
        if(isset($params['rol'])){
            $rol = $params['rol'];
            if ($rol === 'socio') {
                return $handler->handle($request);
            } 
            else {
                $response->getBody()->write("No puede realizar la accion NO es socio.");
                $response = $response->withStatus(403);
            }
        }else
            return json_encode(array("Mensaje" => "Se necesita el ingreso del rol"));

        return $response;
    }
}
