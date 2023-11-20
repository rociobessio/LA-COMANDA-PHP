<?php
    require_once "./models/Encuesta.php";
    class EncuestaController extends Encuesta{


        public static function MostrarMejores($request, $response, $args)
        {
            $lista = Encuesta::obtenerMejoresComentarios();
            $payload = json_encode(array("Mejores encuestas" => $lista));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }

        public static function TraerTodos($request, $response, $args)
        {
            $lista = Producto::obtenerTodos();
            $payload = json_encode(array("lista" => $lista));

            $response->getBody()->write($payload);
            return $response
            ->withHeader('Content-Type', 'application/json');
        }
    }