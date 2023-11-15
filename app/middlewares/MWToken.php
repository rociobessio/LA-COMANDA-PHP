<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class MWToken{
    public function __invoke(Request $request,RequestHandler $handler)
    {
        $header = $request->getHeaderLine(("Authorization"));//-->Donde estara el token
        $token = trim(explode("Bearer", $header)[1]);
        $response = new Response();

        try
        {   //-->Verifico el token.
            // echo 'todo ok en token';
            json_encode(array("Token" => AutentificadorJWT::VerificarToken($token)));
            $response = $handler->handle($request);
        }
        catch(Exception $ex){
            $response->getBody()->write(json_encode(array("Error" => $ex->getMessage())));
        }
        return $response->withHeader('Content-Type', 'application/json');
    }
}