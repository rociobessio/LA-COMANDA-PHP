<?php
    include_once "./models/Uploader.php";
    include_once "./models/Pedido.php";
    include_once "./models/Mesa.php";
    include_once "./models/Producto.php";
    include_once "./models/PedidoProducto.php";
    require_once "./interfaces/IApiUsable.php";

    class PedidoController extends Pedido implements IApiUsable{
        //-->Estados disponibles para los pedidos.
        public static $estadosPedido = array("pendiente", "listo para servir", "en preparacion","entregado");

        public static function CargarUno($request, $response, $args){ 
             $files = $request->getUploadedFiles();

            $parametros = $request->getParsedBody();
            $idMesa = Mesa::obtenerUno(intval($parametros['idMesa'])); 
            // var_dump(intval($parametros['idMesa']));
            
            $nombreCliente = $parametros['nombreCliente'];
            $tiempoEstimado = 0;
            $totalPedido = 0;
            //-->Tengo que pasar el string d productos a array:
            $productosRecibidos =  json_decode($parametros['productos'], true);
            // var_dump(is_array($productosRecibidos));
            // var_dump($productosRecibidos);

            //-->Valido la existencia de la mesa
            if($idMesa !== false){
                $pedido = new Pedido();
                $pedido->setNombreCliente($nombreCliente);
                $pedido->setIDMesa($idMesa->getIdMesa());  
                $pedido->setEstado(self::$estadosPedido[0]);//-->Pendiente
                $pedido->setTiempoInicio(0);
                $pedido->setCodigoPedido(CrearCodigo(5));
                                
                //-->Obtengo el precio total y el tiempo mayor de preparacion entre productos.
                foreach ($productosRecibidos as $prod) {
                    // echo "producto recibido:";
                    // var_dump($prod);

                    $productoExistente = Producto::obtenerUno($prod['idProducto']);
                    
                    if($productoExistente !== false){//-->Quiere decir que existe
                        if($productoExistente->getTiempoPreparacion() > $tiempoEstimado){
                            $tiempoEstimado = $productoExistente->getTiempoPreparacion();//-->Almacena el tiempo estimado mayor
                        }
                        
                        //-->Se acumulan los totales.
                        $totalPedido += $productoExistente->getPrecio();
                        
                        $productos[] = $productoExistente;//-->Lo guardo en el array
                    }
                    else{
                        $payload = json_encode(array("Mensaje" => "El producto ingresado no se encuentra disponible."));
                    }
                }
                var_dump($totalPedido);
                $pedido->setTiempoEstimado($tiempoEstimado);
                $pedido->setCostoTotal($totalPedido);

                //-->Guardo la imagen
                // var_dump($files);
                if (isset($files['fotoMesa'])) {
                    $ruta = './imgs/' . date_format(new DateTime(), 'Y-m-d_H-i-s') . '_' . $nombreCliente . '_Mesa_' . $idMesa->getIdMesa() . '.jpg';
                    $files['fotoMesa']->moveTo($ruta);
                    $pedido->setFotoMesa($ruta);
                }

                Pedido::crear($pedido);

                //-->Voy a la tabla intermedia
                foreach ($productos as $product) {
                    echo 'llegue al foreach, producto:';
                    // var_dump($product);
                    $pedidoProducto = new PedidoProducto();
                    $pedidoProducto->setCodPedido($pedido->getCodigoPedido());
                    $pedidoProducto->setEstado(self::$estadosPedido[0]);
                    $pedidoProducto->setTiempoEstimado($product->getTiempoPreparacion());
                    $pedidoProducto->setIdProducto($product->getIdProducto());
                    $pedidoProducto->setIdEmpleado(0);

                    PedidoProducto::crear($pedidoProducto);
                }
              
                //-->Cambio el estado de la mesa
                if($idMesa->getEstado() == "cerrada"){
                    $idMesa->setEstado("con cliente esperando pedido");
                    Mesa::modificar($idMesa);
                }

                $payload = json_encode(array("Mensaje" => "Pedido creado con éxito, su codigo es: " . $pedido->getCodigoPedido()));
            }
            else{
                $payload = json_encode(array("Mensaje" => "La mesa asignada no existe!"));
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
            $idPedidoProducto = $args['id'];
            $iniciado = false;

            //-->Me traigo el array relacionado a la tabla intermedia y el pedido
            $pedidosProductos = PedidoProducto::obtenerTodosLosPedidos($idPedidoProducto);
            $pedido = Pedido::obtenerUnoPorCodigoPedido($idPedidoProducto);

            //-->Obtengo el rol del empleado:
            $header = $request->getHeaderLine(("Authorization"));
            $token = trim(explode("Bearer", $header)[1]);
            $data = AutentificadorJWT::ObtenerData($token);

            //-->Hay un problema que es que al hacer el rol y sector, si supuestamente,
            //yo tengo un array de productos y un unico empleado es el que inicializa el pedido
            //hay sectores y tipos que no coincidiran. Salvo que solo se tomen aquellos pedidos 
            //relacionados al preparador y luego se van turnando para el resto de coincidencias.


            // var_dump($pedidosProductos);
            // var_dump($data);

            $tiempoInicio = new DateTime();

            //-->Vamos a tener que mediante el cod de pedido buscar en la tabla intermedia,
            //ir cambiando el estado de los productos relacionados al pedido
            //Al pedido asignarle un tiempo de inicio y cambiarle el estado al pedido y la tabla intermedia
            //-->A su vez abria que asignar el id del empleado a cargo del pedido.
            
            foreach ($pedidosProductos as $pedidoProducto) {
                //-->Valido que el estado sea pendiente de ese pedido y que el sector del producto coincida
                //con el sector del que inicia el pedido:
                if($pedidoProducto->getEstado() == "pendiente" &&
                Producto::obtenerUno($pedidoProducto->getIdProducto())->getSector() == Producto::ValidarPedido($data->rol)){
                    // echo 'entre';
                    $pedido->setTiempoInicio($tiempoInicio->format('H:i:sa'));//-->Al pedido le asigno el tiempo de inicio
                    $pedido->setEstado("En preparacion");

                    Pedido::modificar($pedido);//-->Solo modifico su estado y inicio de preparacion

                    //-->En la tabla intermedia cambio el estado y asigno el id del empleado a cargo
                    $pedidoProducto->setEstado("En preparacion");
                    $pedidoProducto->setIdEmpleado($data->id);
                    // var_dump($pedidoProducto);
                    PedidoProducto::modificar($pedidoProducto);
                    
                    $iniciado = true;
               }
            }
            if($iniciado){$payload = json_encode(array("mensaje" => "Pedido iniciado correctamente!"));}
            else{$payload = json_encode(array("mensaje" => "No se ha podido iniciar el pedido, puede ser error en rol!"));}
        
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
            $finalizado = false;
            //-->Obtengo el rol del empleado:
            $header = $request->getHeaderLine(("Authorization"));
            $token = trim(explode("Bearer", $header)[1]);
            $data = AutentificadorJWT::ObtenerData($token);

            $idPedidoProducto = $args['id'];
            
            //-->Me traigo el array relacionado a la tabla intermedia y el pedido
            $pedidosProductos = PedidoProducto::obtenerTodosLosPedidos($idPedidoProducto);
            $pedido = Pedido::obtenerUnoPorCodigoPedido($idPedidoProducto);
            $tiempoFinalizacion = new DateTime();

            if($pedidosProductos){
            //-->Recorro la tabla intermedia y cambio el estado, 
            //me fijo que el estado del producto en el pedido al menos sea "En preparacion" y el rol del empleado
            //-->El tiempo de finalizacion de un pedido se asigna cuando termino el producto o todos estan finalizados
                foreach($pedidosProductos as $pedidoProducto){
                    if($pedidoProducto->getEstado() == "En preparacion" &&
                    Producto::obtenerUno($pedidoProducto->getIdProducto())->getSector() == Producto::ValidarPedido($data->rol)){
                        $pedido->setTiempoFin($tiempoFinalizacion->format('H:i:sa'));//-->Setteo el tiempo de finalizacion
                        $pedido->setEstado("listo para servir");

                        Pedido::modificar($pedido);//-->Lo modifico

                        //-->En la tabla intermedia cambio el estado y asigno el id del empleado a cargo
                        $pedidoProducto->setEstado("listo para servir");
                        $pedidoProducto->setIdEmpleado($data->id);
                        // var_dump($pedidoProducto);
                        PedidoProducto::modificar($pedidoProducto);
                        
                        $finalizado = true;
                    }
                }
                
                if($finalizado){$payload = json_encode(array("mensaje" => "Producto/s finalizados, listos para entregar!"));}
                else{$payload = json_encode(array("mensaje" => "Error en querer finalizar la preparacion de un produto, es posible que no este en preparacion o haya error con el rol!"));}
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
         * Ademas deberian de estar TODOS los productos
         * de un pedido listos para servirse para poder
         * entregarlo.
         * 
         * Por ultimo los modifico en sus tablas.
         * 
         * SPRINT II.
         */
        public static function EntregarPedido($request, $response, $args){
            $entregado = false;
            $idPedidoProducto = $args['id'];
            
            //-->Me traigo el array relacionado a la tabla intermedia y el pedido
            $pedidosProductos = PedidoProducto::obtenerTodosLosPedidos($idPedidoProducto);
            $pedido = Pedido::obtenerUnoPorCodigoPedido($idPedidoProducto);
            $mesa = Mesa::obtenerUno($pedido->getIdMesa());

            //-->Para entregar un pedido tendria que validar que todos los productos
            //asignados a un pedido esten listos para servir
            if($pedidosProductos){
                //-->Valido que todos los productos esten listos para servir.
                $todosListosParaServir = true;
                foreach ($pedidosProductos as $pedidoProd) {
                    if ($pedidoProd->getEstado() != "listo para servir") {
                        $todosListosParaServir = false;
                        break;
                    }
                }

                //-->Si lo estan modifico.
                if($todosListosParaServir){
                    $pedido->setEstado("entregado");
                    Pedido::modificar($pedido);//-->Lo modifico

                    //-->Recorro en la tabla intermedia y cambio el estado
                    foreach ($pedidosProductos as $pedidoProd) {
                        $pedidoProd->setEstado("entregado");
                        // var_dump($pedidoProducto);
                        PedidoProducto::modificar($pedidoProd);
                    }

                    //-->Cambio el estado de la mesa
                    if($mesa && $mesa->getEstado() == "con cliente esperando pedido"){
                        $mesa->setEstado("con cliente comiendo");
                        Mesa::modificar($mesa);
                    }

                    $entregado = true;
                }

                if($entregado){$payload = json_encode(array("mensaje" => "Pedido entregado al cliente!"));}
                else{$payload = json_encode(array("mensaje" => "Error en querer entregar el pedido, es posible que aun no esten todos los productos disponible para servirse!"));}
            }else {
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