<?php

    include_once "./models/Mesa.php";
    require_once "./interfaces/IApiUsable.php";

    class MesaController extends Mesa implements IApiUsable{

        //-->Los estados de la mesa pueden ser:
        public static $estados = array("con cliente esperando pedido", "con cliente comiendo", "con cliente pagando", "pagado" ,"cerrada",);

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
            echo 'traer todos';
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
            echo'traer uno';
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

            if(Mesa::obtenerUno(intval($idEliminar))){//-->Me fijo si existe.
                Mesa::borrar(intval($idEliminar));
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

            $mesa = Mesa::obtenerUno(intval($idModificar));
            // var_dump($mesa);
            if($mesa !== false){
                $parametros = $request->getParsedBody();

                $pudoActualizar = false;
                if (isset($parametros['estado'])) {
                  $pudoActualizar = true;
                //   $mesa->setEstado($parametros['estado']);
                $mesa->setEstado($parametros['estado']);
                }
                if ($pudoActualizar) {
                  Mesa::modificar($mesa);
                  $payload = json_encode(array("mensaje" => "Mesa modificada correctamente"));
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

        /**
         * Me permitira cambiarle el estado a la mesa 
         * de aquellos pedidos listos para servir
         * a su mesa se le cambia el estado por
         * 'con cliente comiendo'.
         * 
         * La moza se fija los pedidos que están listos para servir , cambia el estado de la mesa,
         */
        // public static function CambiarEstadoMesa($request, $response, $args)
        // {
        //     $parametros = $request->getParsedBody();
        //     $mesa = Mesa::obtenerUno(intval($parametros['idMesa']));
        //     $pudoCambiar = false;
        //     // echo 'aca';
        //     $listaPedidos = Pedido::obtenerPedidosListos();
        //     // var_dump($listaPedidos);
        //     foreach ($listaPedidos as $pedido)
        //     {   //-->Solo se podrá cambiar el estado si hay coincidencias de id's y el pedido fue entregado.
        //         if($pedido->getIdMesa() == $mesa->getIdMesa() && $pedido->getEstado() == "listo para servir")
        //         {
        //             $mesa->setEstado( "con cliente comiendo");
        //             // var_dump($mesa);
        //             Mesa::modificar($mesa);
        //             $pedido->setEstado("entregado");
        //             Pedido::modificar($pedido);//-->Modifico el pedido.
        //             $response->getBody()->write("Se ha modificado el estado de la mesa con exito!\n");
        //             $pudoCambiar = true;
        //             break;
        //         }
        //     }

        //     if($pudoCambiar){//-->Cambia el estado de la tabla intermedia.
        //         $pedidoProductos = PedidoProducto::obtenerTodosLosPedidos($pedido->getCodigoPedido());
        //         foreach($pedidoProductos as $pedProd){
        //             if($pedido->getCodigoPedido() && $pedProd->getCodPedido()){
        //                 $pedProd->setEstado("entregado");
        //                 PedidoProducto::modificar($pedProd);
        //             }
        //         }
        //     }

        //     if(!$pudoCambiar){
        //         $response->getBody()->write("Para cambiar el estado de la mesa el pedido debe ser entregado!\n");
        //     }
        //     return $response->withHeader('Content-Type', 'application/json');
        // }

        /**
         * Me permitira cobrar una mesa.
         * 
         * La moza cobra la cuenta.
         * 
         * Cambio el estado de la mesa
         * y al pedido lo pongo como facturado.
         */
        public static function CobrarMesa($request, $response, $args){
            $codPedido = $args['codPedido'];

            $pedido = Pedido::obtenerUno($codPedido); 

            if($pedido !== false){
                $mesa = Mesa::obtenerUno($pedido->getIdMesa());
                //-->Si esta pagando
                if($mesa && $mesa->getEstado() === "con cliente pagando"){
                    $cuenta = Mesa::ObtenerCuenta($codPedido);
                    $mesa->setEstado(self::$estados[3]);//-->pagado
                    Mesa::modificar($mesa);

                    $pedido->setPedidoFacturado(true);//-->Pongo el pedido como facturado
                    Pedido::modificar($pedido);

                    $response->getBody()->write("Se ha cobrado la mesa " . $cuenta[0]['idMesa']);
                }
                else{
                    $response->getBody()->write("Ocurrio un error al querer cobrar la mesa!" );
                }
            }
            return $response->withHeader('Content-Type', 'application/json');
        }

        /**
         * Servira para cambiarle el estado a la
         * mesa y que el estado de esta pase a estar
         * "con cliente pagando", se le muestra el total
         * del pedidos.
         */
        public static function MostrarCuentaMesa($request, $response, $args){
            $codPedido = $args['codPedido'];
            $pedido = Pedido::obtenerUno($codPedido); 
            if($pedido){
                $mesa = Mesa::obtenerUno($pedido->getIdMesa());
                if($mesa){
                    $cuenta = Mesa::ObtenerCuenta($codPedido);
                    $mesa->setEstado(self::$estados[2]);//-->Con cliente pagando
                    Mesa::modificar($mesa);

                    $response->getBody()->write("El total del pedido es: " . $cuenta[0]['SUM(pr.precio)']);
                }
            }else{
                $response->getBody()->write("Ocurrio un error al querer cobrar la mesa!" );
            }

            return $response->withHeader('Content-Type', 'application/json');
        }

        /**
         * Permitira que alguno de los socios
         * cierren la mesa.
         */
        public static function CerrarMesa($request, $response, $args){
            $parametros = $request->getParsedBody();
            $mesa = Mesa::obtenerUno(intval($parametros['idMesa']));
            $mesa->setEstado(self::$estados[4]);//-->Cerrada
            Mesa::modificar($mesa);
            $response->getBody()->write("Mesa cerrada con exito!");
            return $response->withHeader('Content-Type', 'application/json');
        }

    }