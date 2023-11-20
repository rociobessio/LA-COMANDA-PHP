<?php
    include_once "./models/Facturacion.php";

    class FacturacionController extends Facturacion{

        // public function CargarFactura($request,$handler,$args){
        //     $parametros = $request->getParsedBody();
        //     $codigoPedido = $parametros['codigoPedido'];
        // }
        public static function MostrarFacturas($request, $response, $args)
        {
            $lista = Facturacion::obtenerTodos();
            $payload = json_encode(array("Facturas" => $lista));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }