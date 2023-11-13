<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class MWSocios{
    public function __invoke(Request $request,RequestHandler $handler) : Response {
        $parametros = $request->getQueryParams();
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