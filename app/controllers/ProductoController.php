<?php

    include_once "./models/Producto.php";
    require_once "./interfaces/IApiUsable.php";
    
    class ProductoController extends Producto implements IApiUsable{

        public static $tiposValidos = array("Bebida","Cerveza","Comida","Postre","Trago");
        public static $sectores = array("Vinoteca","Cocina","CandyBar","Cerveceria","Barra");

//********************************************** FUNCIONES *************************************************************
	    /**
         * Me permitira guardar un producto en la tabla
         * productos de la base de datos.
         */
        public static function CargarUno($request, $response, $args){
            $params = $request->getParsedBody();
            $nombre = $params['nombre'];
            $precio= $params['precio'];
            $sector = $params['sector'];
            $tipo = $params['tipo'];
            $tiempoEstimadoPreparacion = new DateTime($params['tiempoEstimadoPreparacion']);
            
            //-->Valido el tipo y sector del producto
            if(in_array($sector,self::$sectores) && in_array($tipo,self::$tiposValidos)){
                $producto = new Producto();
                $producto->setNombre($nombre);
                $producto->setPrecio(floatval($precio));
                $producto->setTipo($tipo);
                $producto->setSector($sector);
                $producto->setTiempoEstimado($tiempoEstimadoPreparacion->format('H:i:sa'));
                // var_dump($producto);

                Producto::crear($producto);
                $payload = json_encode(array("Mensaje" => "El producto se ha generado correctamente!"));
            }
            else{
                $payload = json_encode(array("Error"=>"No es un sector o tipo valido"));
            }
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        } 

        /**
         * Me permitira listar todos los registros 
         * de la tabla productos.
         */
	    public static function TraerTodos($request, $response, $args){
            $listado = Producto::obtenerTodos();
            $payload = json_encode(array("Productos" => $listado));
            $response->getBody()->write($payload);
            return $response
            ->withHeader('Content-Type','application/json');
        }

        public static function TraerUno($request, $response, $args){
            echo'traer uno: ';
            $val = $args['id'];
            $producto = Producto::obtenerUno(intval($val));//-->Me traigo uno.
            $payload = json_encode($producto);

            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        
        }

        /**
         * Borra un producto de la tabla productos
         * mediante la coincidencia de un ID.
         */
	    public static function BorrarUno($request, $response, $args){
            $id = $args['id'];

            if(Producto::obtenerUno(intval($id))){
                Producto::borrar(intval($id));
                $payload = json_encode(array("mensaje" => "Se ha dado de baja al producto."));
            }
            else
                $payload = json_encode(array("mensaje" => "El ID:" . $id . " no esta asignado a ningun producto."));
                
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }

        /**
         * Me permitira modificar un producto de la 
         * tabla productos.
         * 
         * #1: Busca que exista el producto.
         * #2: Obtiene los valores ingresados (podrian ser no pasados).
         * #3: Se asignan los valores.
         * #4: Se modifica.
         */
	    public static function ModificarUno($request, $response, $args){
            $id = $args['id'];

            $producto = Producto::obtenerUno(intval($id));
            
            if($producto !== false){
                $parametros = $request->getParsedBody(); 

                if(isset($parametros['nombre'])){$producto->setNombre($parametros['nombre']);} 
                if(isset($parametros['precio'])){$producto->setPrecio(floatval($parametros['precio']));} 

                if(isset($parametros['sector'])){
                    if(in_array($parametros['sector'],self::$sectores)){ 
                        if(isset($parametros['sector'])){$producto->setSector($parametros['sector']);}  
                    }
                    else
                    $payload = json_encode(array("Error"=>"No es un sector valido."));
                }

                if(isset($parametros['tipo'])){
                    if(in_array($parametros['tipo'],self::$tiposValidos)){
                        if(isset($parametros['tipo'])){$producto->setTipo($parametros['tipo']);} 
                    }
                }
                
                Producto::modificar($producto);
                
                $payload = json_encode(array("mensaje" => "Producto modificado correctamente!")); 
            }
            else {
                $payload = json_encode(array("mensaje" => "El ID:" . $id . " no esta asignado a ningun producto."));
            }
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }