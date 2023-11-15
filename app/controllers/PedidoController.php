<?php
    include_once "./models/Uploader.php";
    include_once "./models/Pedido.php";
    include_once "./models/Mesa.php";
    include_once "./models/Producto.php";
    require_once "./interfaces/IApiUsable.php";

    class PedidoController extends Pedido implements IApiUsable{
        public static $estadosPedido = array("pendiente", "listo para servir", "en preparacion","entregado");

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
                $pedido->setTiempoInicio(0);
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

        /**
         * Me permitira iniciar un pedido ingresado por ID.
         * 
         * Primero se fija que exista el producto mediante su ID,
         * luego valida el rol y que su estado sea pendiente.
         * 
         * Por ultimo modifica el tiempo de inicio, el tiempo estimado
         * de preparacion y su estado.
         * 
         * Sprint II.
         */
        public static function IniciarPedido($request, $response, $args){
            $parametros = $request->getParsedBody();
            $idPedido = $args['id'];
        
            $tiempoEstimadoPreparacion = new DateTime($parametros['tiempoEstimadoPreparacion']);
            $pedido = Pedido::obtenerUno(intval($idPedido));
            $rol = $parametros['rol'];
            $tiempoInicio = new DateTime();
            $cantidad = $pedido->getCantidad();
        
            if($pedido && $pedido->getEstado() == "pendiente" &&
               Producto::obtenerUno($pedido->getIDProducto())->getSector() == Pedido::ValidarPedido($rol)){
    
                $pedido->setTiempoEstimado($tiempoEstimadoPreparacion->format('H:i:sa'));
        
                $pedido->setTiempoInicio($tiempoInicio->format('H:i:sa'));
                $pedido->setEstado("En preparacion");
        
                //-->Mesa, con cliente esperando pedido?
                Pedido::modificar($pedido);
                $payload = json_encode(array("mensaje" => "Pedido iniciado correctamente!"));
            } else {
                $payload = json_encode(array("mensaje" => "No se ha podido iniciar el pedido!"));
            }
        
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }        

        /**
         * Me permitira cambiarle el estado a un pedido 
         * por el de Listo para servir.
         * 
         * Se fija que exista, que el estado de este sea 
         * pendiente y valida el rol.
         * 
         * SPRINT II.W
         */
        public static function FinalizarPedido($request, $response, $args){
            $parametros = $request->getParsedBody();
            $idPedido = $args['id'];       
            $pedido = Pedido::obtenerUno(intval($idPedido));
            $rol = $parametros['rol'];
            $tiempoFinalizacion = new DateTime();

            if($pedido){
                var_dump($pedido);
                if($pedido->getEstado() == "En preparacion" &&
                Producto::obtenerUno($pedido->getIDProducto())->getSector() == Pedido::ValidarPedido($rol)){
                    $pedido->setTiempoFin($tiempoFinalizacion->format('H:i:sa'));//-->Se asigna el tiempo de finalizacion
                    $pedido->setEstado("listo para servir");
                    Pedido::modificar($pedido);
                    $payload = json_encode(array("mensaje" => "Pedido ha finalizado de prepararse, ya se puede entregar!"));
                }
                else
                    $payload = json_encode(array("mensaje" => "Error en querer finalizar el, es posible que no este en preparacion!"));
            }
            else {
            $payload = json_encode(array("mensaje" => "ID no coinciden con ningun Pedido!"));
          }
          $response->getBody()->write($payload);
          return $response
            ->withHeader('Content-Type', 'application/json');
        }

        /**
         * Me permitira entregar un pedido
         *  a una mesa y cambiar sus estados.
         * 
         * Me fijo que exista el pedido y que su estado
         * sea listo para servir.
         * Cambio el estado del pedido y el estado de la
         * mesa.
         * 
         * Por ultimo los modifico en sus tablas.
         * 
         * SPRINT II.
         */
        public static function EntregarPedido($request, $response, $args){
            $parametros = $request->getParsedBody();
            $idPedido = $args['id'];       
            $pedido = Pedido::obtenerUno(intval($idPedido));
            $rol = $parametros['rol'];

            if($pedido){
                $mesa = Mesa::obtenerUno($pedido->getIdMesa());
                if($pedido->getEstado() == "listo para servir"){
                    $pedido->setEstado("entregado");
                    Pedido::modificar($pedido);

                    //-->Modifico el estado de la mesa.
                    if($mesa->getEstado() == "con cliente esperando pedido"){
                        $mesa->setEstado("con cliente comiendo");
                        Mesa::modificar($mesa);
                    }
                    $payload = json_encode(array("mensaje" => "Pedido entregado al cliente!"));
                }
                else{$payload = json_encode(array("mensaje" => "Error en querer entregar el pedido, es posible que aun no este disponible para servirse!"));}
            }
            else {
                $payload = json_encode(array("mensaje" => "ID no coinciden con ningun Pedido!"));
            }

            $response->getBody()->write($payload);
            return $response
              ->withHeader('Content-Type', 'application/json');
        }

        /**
         *  El cliente ingresa el código de la mesa junto con el número de pedido y ve el tiempo de
         *  demora de su pedido.
         */
        public static function ConsultarDemoraPedido($request,$response,$args){
            $parametros = $request->getQueryParams();
            
            if(isset($parametros['idMesa']) && isset($parametros['idPedido'])){
                $idMesa = intval($parametros['idMesa']);
                $codigoPedido = $parametros['codPedido'];//-->Es el alfanumerico
                $listaPedidos = Pedido::ObtenerDemoraPedido($idMesa,$codigoPedido);
                if(count($listaPedidos) > 0){
                    $payload = json_encode(array("Pedidos" => $listaPedidos));
                    $response->getBody()->write($payload);
                }
                else{$response->getBody()->write("No hay concordancia de pedido y mesa ingresados.");}

            }
            else{$response->getBody()->write("Se deben de ingresar todos los campos.");}

            return $response->withHeader('Content-Type', 'application/json');
        }
        
        /**
         * Me permitira consultar los pedidos listos por el rol/sector
         * del empleado.
         */
        public static function ConsultarPedidosPendientes($request, $response, $args)
        {
            $parametros = $request->getQueryParams();//-->Directamente del que ingreso 
            $rol = isset($parametros['rol']) ? $parametros['rol'] : null;
            var_dump($rol);
            if ($rol !== null) {
                $lista = Pedido::GetPedidosPendientes($rol);
                if (count($lista) > 0) {
                    $payload = json_encode(array("Pedidos" => $lista));
                    $response->getBody()->write($payload);
                } else {
                    $response->getBody()->write("No se encontraron pedidos pendientes.");
                }
            }

            return $response->withHeader('Content-Type', 'application/json');
        }



    }