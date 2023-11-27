<?php

    include_once "./models/empleado.php";
    include_once "./models/CSV.php";
    // require_once "./models/Empleado.php";
    require_once "./interfaces/IApiUsable.php";
    require_once "./middlewares/AutentificadorJWT.php";


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
            $clave = $parametros['clave'];
            // var_dump($rol);
            
            if(in_array($rol,self::$roles)){
                $empleado = new Empleado();
                $empleado->setNombre($nombre);
                $empleado->setRol($rol);
                $empleado->setClave($clave);
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
                
                if(isset($parametros['nombre'])){$empleado->setNombre($parametros['nombre']);}
                if(isset($parametros['clave'])){$empleado->setClave($parametros['clave']);} 
        
                if(isset($parametros['rol'])){//-->Valido el rol
                    if(in_array($parametros['rol'],self::$roles)){
                        if(isset($parametros['rol'])){$empleado->setRol($parametros['rol']);}
                    }
                    else{$payload = json_encode(array("mensaje" => "Rol ingresado no válido!")); }
                }
        
                // var_dump($empleado);
                Empleado::modificar($empleado);
                $payload = json_encode(array("mensaje" => "El empleado se ha podido modificar correctamente!")); 
            }
            else
                $payload = json_encode(array("mensaje" => "No hay coincidencia de empleado con ID:" . $id ." !"));
        
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
        

//======================================== SPRINT III ===============================================================
        /**
         * Me permitira loguear un usuario y asignarle un 
         * token.
         */
        public static function LoguearEmpleado($request,$response,$args){
            $parametros = $request->getParsedBody();
            $nombre = $parametros['nombre'];
            $clave = $parametros['clave'];

            $empleado = Empleado::obtenerUnoPorUsuario($nombre,$clave);
            if($empleado){
                $data = array('empleado' => $empleado->getNombre(), 'rol' => $empleado->getRol(), 'clave' => $empleado->getClave(), 'id' =>$empleado->getIDEmpleado());
                $creacionToken = AutentificadorJWT::CrearToken($data);
                
                $response = $response->withHeader('Set-Cookie', 'token=' . $creacionToken['jwt']);
                
                $payload = json_encode(array("mensaje" => "Usuario logueado correctamente", "token" => $creacionToken['jwt']));
            }
            else{$payload = json_encode(array("mensaje" => "Error al loguear el empleado"));}

            $response->getBody()->write($payload);
            return $response
            ->withHeader('Content-Type', 'application/json');
        }

        /**
         * Me permtira exportar la lista de empleados.
         */
        public static function ExportarEmpleados($request,$response,$args){
            try{
                $archivo = CSV::ExportarCSV("empleados.csv");
                if(file_exists($archivo) && filesize($archivo) > 0){
                    $payload = json_encode(array("Archivo creado" => $archivo));
                }
                else{
                    $payload = json_encode(array("Error" => "Datos ingresados invalidos."));
                }
                $response->getBody()->write($payload);
            }
            catch (Exception $e){
                echo $e;
            }
            finally{
                return $response->withHeader('Content-Type', 'text/csv');
            }
        }

        public static function ImportarEmpleados($request,$response,$args){
            try{
                $archivo = ($_FILES["file"]);
                Empleado::CargarCSV($archivo["tmp_name"]);
                $payload = json_encode(array("Mensaje" => "Empleados cargados!"));
            }
            catch(Throwable $e){
                $payload = json_encode(array("Error" => $e->getMessage()));
            }
            finally{
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'text/csv');
            }
        }

    }