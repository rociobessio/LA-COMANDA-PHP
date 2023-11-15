<?php

    require_once "../app/models/Empleado.php";
    
    class Logger{
        /**
         * Esta funcion me permitira validar un usuario
         * para su ingreso a la aplicacion.
         * 
         * SPRINT III.
         */
        public static function ValidarEmpleado($request,$handler){
            $parametros = $request->getParsedBody();
            $nombre = $parametros['nombre'];
            $clave = $parametros['clave'];
            $usuario = Empleado::obtenerUnoPorUsuario($nombre,$clave);

            if($usuario != false){
                return $handler->handle($request);
            }

            throw new Exception("Usuario y/o clave erroneos");
        }
    }