<?php

    include_once "./models/empleado.php";
    // require_once "./models/Empleado.php";
    require_once "./interfaces/IApiUsable.php";


    class EmpleadoController extends Empleado implements IApiUsable{
        //-->Los roles disponibles para UN EMPLEADO son:
        public static $roles = array("Cocinero", "Cervezero", "Bartender", "Mozo", "Socio");

        /**
         * Sprint 1,
         * Me permitira cargar un empleado.
         */
        public static function CargarUno($request, $response, $args){
            $parametros = $request->getParsedBody();
            $nombre = $parametros['nombre'];
            $rol = $parametros['rol'];
            var_dump($rol);
            
            if(in_array($rol,self::$roles)){
                $empleado = new Empleado();
                $empleado->setNombre($nombre);
                $empleado->setRol($rol);
                Empleado::crear($empleado);    
                $payload = json_encode(array("Mensaje" => "El empleado se ha cargado correctamente!"));
            }
            else{
                $payload = json_encode(array("Mensaje" => "El rol ingresado para el empleado no es valido!"));
            }
            $response->getBody()->write($payload);
            return $response
            ->withHeader('Content-Type','application/json');
        } 
        public static function TraerTodos($request, $response, $args){
            $listado = Empleado::obtenerTodos();
            $payload = json_encode(array("Empleados" => $listado));
            $response->getBody()->write($payload);
            return $response
            ->withHeader('Content-Type','application/json');
        }

        public static function TraerUno($request, $response, $args){
            $val = $args['id'];
            $empleado = Empleado::obtenerUno(intval($val));//-->Me traigo uno.
            $payload = json_encode($empleado);

            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }

        /**
         * Me permitira llamar al metodo de la clase
         * Empleado para dar una baja logica.
         */
	    public static function BorrarUno($request, $response, $args){
            $id = $args['id'];

            if(Empleado::obtenerUno(intval($id))){
                Empleado::borrar(intval($id));
                $payload = json_encode(array("mensaje" => "Se ha dado de baja al empleado."));
            }
            else
                $payload = json_encode(array("mensaje" => "El ID:" . $id . " no esta asignado a ningun empleado."));
                
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }

	    public static function ModificarUno($request, $response, $args){
            $id = $args['id'];

            $empleado = Empleado::obtenerUno(intval($id));
            if($empleado !== false){
                $parametros = $request->getParsedBody();

                if(isset($parametros['nombre']) && isset($parametros['rol'])){
                        if(in_array($parametros['rol'],self::$roles)){
                            $empleado->setNombre($parametros['nombre']);
                            $empleado->setRol($parametros['rol']);
                            Empleado::modificar($empleado);
                            $payload = json_encode(array("mensaje" => "El empleado se ha podido modificar correctamente!"));
                        }
                        else
                            $payload = json_encode(array("mensaje" => "Rol ingresado no valido!"));
                    }
                else
                    $payload = json_encode(array("mensaje" => "Se necesita el ingreso de todos los parametros!"));
            }
            else
                $payload = json_encode(array("mensaje" => "No hay coincidencia de empleado con ID:" . $id ." !"));

            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }