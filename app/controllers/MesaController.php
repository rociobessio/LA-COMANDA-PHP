<?php

    require_once "./app/models/Mesa.php";
    require_once "./app/interfaces/IApiUsable.php";

    class MesaController extends Mesa implements IApiUsable{

        //-->Los estados de la mesa pueden ser:
        public static $estados = array("con cliente esperando pedido", "con cliente comiendo", "con cliente pagando", "cerrada");

        public static function CargarUno($request, $response, $args)
        {
            $params = $request->getParsedBody();//-->Parseo el body
            $estado = $params['estado'];
            //-->Valido el estado de la mesa:
            if(in_array($estado, self::$estados)){
                $mesa = new Mesa();
                $mesa->setEstado($estado);
                Mesa::crear($mesa);
                $payload = json_encode(array("Mensaje" => "Mesa creado con exito"));
            }
            else{
                $payload = json_encode(array("Mensaje" => "El estado de la mesa no es valido"));
            }

            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }

        /**
         * Me permite traerme una lista de mesas de la tabla
         * 'mesas'.
         */
	    public static function TraerTodos($request, $response, $args){
            $lista = Mesa::obtenerTodos();
            $payload = json_encode(array("listaMesas" => $lista));

            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }

        /**
         * Me permitira traerme un obj Mesa de la tabla 
         * especifico mediante le id de la mesa.
         */
        public static function TraerUno($request, $response, $args){
            $val = $args['valor'];
            $mesa = Mesa::obtenerUno($val);//-->Me traigo uno.
            $payload = json_encode($mesa);

            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }

        /**
         * Me va a permitir dar de baja una mesa en caso de necesitarlo.
         */
        public static function BorrarUno($request, $response, $args){
            $idEliminar = $args['id'];

            if(Mesa::obtenerUno($idEliminar)){//-->Me fijo si existe.
                Mesa::borrar($idEliminar);
                $payload = json_encode(array("Mensaje"=>"La mesa se ha dado de baja correctamente!"));
            }
            else{
                $payload = json_encode(array("Mensaje"=>"El ID:" . $idEliminar . " no esta asignado a ninguna mesa."));
            }

            $response->getBody()->write($payload);
            return $response
            ->withHeader('Content-Type', 'application/json');
        }

	    public static function ModificarUno($request, $response, $args){
            $idModificar = $args['id'];

            $mesa = Mesa::obtenerUno($idModificar);
            if($mesa !== false){
                $parametros = $request->getParsedBody();

                $pudoActualizar = false;
                if (isset($parametros['estado'])) {
                  $pudoActualizar = true;
                  $mesa->estado = $parametros['estado'];
                }
                if ($pudoActualizar) {
                  Mesa::modificar($mesa);
                  $payload = json_encode(array("mensaje" => "Mesa modificada correctamente,"));
                } else {
                  $payload = json_encode(array("mensaje" => "Se deben de ingresar todos los datos para modificar la mesa."));
                }
               
            }else {
                $payload = json_encode(array("mensaje" => "El ID:" . $idModificar . " no esta asignado a ninguna mesa."));
            }

            $response->getBody()->write($payload);
            return $response
            ->withHeader('Content-Type', 'application/json');
        }
    }