<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class MWPreparador{
    public function __invoke(Request $request,RequestHandler $handler) : Response {

        $header = $request->getHeaderLine(("Authorization"));
        $token = trim(explode("Bearer", $header)[1]);
        $response = new Response();

        try
        {
            $data = AutentificadorJWT::ObtenerData($token);
            // var_dump($data);
            if($data->getRol() == "Mozo" && $data->getRol() == "Socio" && $data->getRol() == "Cocinero" &&
                 $data->getRol() == "Bartender" && $data->getRol() == "Cervecero" && $data->getRol() == "Candybar")
            {
                $response= $handler->handle($request);
            }
            else
            {
                $response->getBody()->write(json_encode(array('Error' => "Accion reservada solamente para los preparadores/mozos/socios.")));
            }
        }
        catch(Exception $excepcion)
        {
            $response->getBody()->write(json_encode(array("Error" => $excepcion->getMessage())));
        }

        return $response->withHeader('Content-Type', 'application/json');
   }
}