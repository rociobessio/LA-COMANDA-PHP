<?php
    include_once "./models/Uploader.php";
    include_once "./models/Pedido.php";
    include_once "./models/Mesa.php";
    include_once "./models/Producto.php";
    require_once "./interfaces/IApiUsable.php";

    class PedidoController extends Pedido implements IApiUsable{
        public static $estadosPedido = array("pendiente", "listo para servir", "en preparacion");

        public static function CargarUno($request, $response, $args){ 
            $files = $request->getUploadedFiles();
 
            $parametros = $request->getParsedBody();
            $idProducto = Producto::obtenerUno(intval($parametros['idProducto']));
            $cantidad = intval($parametros['cantidad']);
            $idMesa = Mesa::obtenerUno(intval($parametros['idMesa'])); 
            $nombreCliente = $parametros['nombreCliente'];

            //-->Valido la existencia de la mesa y el prod
            if($idMesa !== null && $idProducto !== null){
                $pedido = new Pedido();
                $pedido->setNombreCliente($nombreCliente);
                $pedido->setIDMesa($idMesa->getIdMesa());  
                $pedido->setEstado(self::$estadosPedido[0]);//-->Pendiente
                $pedido->setIDProducto($idProducto->getIdProducto()); 
                $pedido->setIDEmpleado(0);
                $pedido->setTiempoEstimado(0); 
                $pedido->setTiempoInicio(new DateTime(date("h:i:sa")));
                $pedido->setCodigoPedido(CrearCodigo(5));
                $pedido->setCantidad($cantidad);

                //-->Guardo la imagen
                // var_dump($uploadedFiles);
                if (isset($files['fotoMesa'])) {
                    $ruta = './imgs/' . date_format(new DateTime(), 'Y-m-d_H-i-s') . '_' . $nombreCliente . '_Mesa_' . $idMesa->getIdMesa() . '.jpg';
                    $files['fotoMesa']->moveTo($ruta);
                    $pedido->setFotoMesa($ruta);
                }
              
                //-->Cambio el estado de la mesa
                if($idMesa->getEstado() == "cerrada"){
                    $idMesa->setEstado("con cliente esperando pedido");
                    Mesa::modificar($idMesa);
                }

                Pedido::crear($pedido);

                $payload = json_encode(array("Mensaje" => "Pedido creado con éxito"));
            }
            else{
                $payload = json_encode(array("Mensaje" => "El producto o la mesa no existen!"));
            }
    
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }

	    public static function TraerTodos($request, $response, $args){
            $listado = Pedido::obtenerTodos();
            $payload = json_encode(array("Pedidos" => $listado));
            $response->getBody()->write($payload);
            return $response
            ->withHeader('Content-Type','application/json');
        }

        /**
         * Me permitira encontrar un pedido
         * mediante su ID.
         */
        public static function TraerUno($request, $response, $args){
            $val = $args['id'];
            $pedido = Pedido::obtenerUno($val);//-->Me traigo uno.
            $payload = json_encode($pedido);

            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }

        /**
         * El pedido estara pagado
         */
	    public static function BorrarUno($request, $response, $args){
            $idEliminar = $args['id'];

            if(Pedido::obtenerUno(intval($idEliminar))){//-->Me fijo si existe.
                Pedido::borrar(intval($idEliminar));
                $payload = json_encode(array("Mensaje"=>"El pedido se ha dado de baja correctamente (se ha facturado)!"));
            }
            else{
                $payload = json_encode(array("Mensaje"=>"El ID:" . $idEliminar . " no esta asignado a ningun pedido."));
            }

            $response->getBody()->write($payload);
            return $response
            ->withHeader('Content-Type', 'application/json');
        }

	    public static function ModificarUno($request, $response, $args){
            $parametros = $request->getParsedBody();

            $idModificar = $args['id'];
            $nombre = $parametros['nombreCliente']; 
            $pedido = Pedido::obtenerUno(intval($idModificar));
            if($pedido !== false){
                if(isset($parametros['nombreCliente'])){
                    $pedido->setNombreCliente($nombre); 
                    Pedido::modificar($pedido);
                    $payload = json_encode(array("Mensaje"=>"El pedido se ha modificado correctamente!"));
                }
                else
                    $payload = json_encode(array("mensaje" => "Se deben de ingresar todos los datos para modificar el pedido."));
            }
            else{
                $payload = json_encode(array("mensaje" => "El pedido no existe."));
            } 
        
            $response->getBody()->write($payload);
            return $response
              ->withHeader('Content-Type', 'application/json');
        
        }
    }