<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class MWMozos{
    public function __invoke(Request $request,RequestHandler $handler) : Response {
        $parametros = $request->getQueryParams();
        echo 'en validar mozo!';
        if (isset($parametros['rol'])) {
            $rol = $parametros['rol'];
            if ($rol === 'mozo') {
                return $handler->handle($request);
            } else {
                $response = new Response();
                $payload = json_encode(array('mensaje' => 'No sos MOZO no podes realizar la accion'));
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/json');
            }
        } else {
            return $handler->handle($request);
        }
    }
}