<?php

    require_once "./interfaces/ICrud.php";   

    class Empleado implements ICrud{
//********************************************** ATRIBUTOS *************************************************************
        public $idEmpleado;
        public $rol;
        public $nombre;
        public $fechaAlta;
        public $fechaBaja;
        public $clave;
//********************************************** GETTERS *************************************************************
        public function getNombre(){
            return $this->nombre;
        }
        public function getRol(){
            return $this->rol;
        }
        public function getFechaAlta(){
            return $this->fechaAlta;
        }
        public function getFechaBaja(){
            return $this->fechaBaja;
        }
        public function getIDEmpleado(){
            return $this->idEmpleado;
        }
        public function getClave(){
            return $this->clave;
        }
//********************************************** SETTERS *************************************************************
        public function setNombre($nombre){
            if(isset($nombre) && !empty($nombre)){
                $this->nombre = $nombre;
            }
        }
        public function setRol($rol){
            if(isset($rol) && !empty($rol)){
                $this->rol = $rol;
            }
        }
        public function setClave($clave){
            if(isset($clave) && !empty($clave)){
                $this->clave = $clave;
            }
        }
//********************************************** FUNCIONES *************************************************************
        /**
         * Me permitira guardar una instancia de 
         * un empleado en la tabla 'empleados'
         * de la db.
         */
        public static function crear($empleado) {
            $fechaAlta = new DateTime(date("d-m-Y"));//-->Le asigno la fecha de alta
            $fechaBaja = null; //-->Si se crea no se asigna la baja
            $objAccesoDB = AccesoDatos::obtenerObjetoAcceso();
            $consulta = $objAccesoDB->retornarConsulta("INSERT INTO empleados (rol, nombre, fechaAlta, fechaBaja,clave) VALUES (:rol, :nombre, :fechaAlta, :fechaBaja,:clave)");
            $consulta->bindValue(':rol', $empleado->getRol(), PDO::PARAM_STR);
            $consulta->bindValue(':nombre', $empleado->getNombre(), PDO::PARAM_STR);
            $consulta->bindValue(':fechaAlta', date_format($fechaAlta, "Y-m-d"), PDO::PARAM_STR);
            $consulta->bindValue(':fechaBaja', $fechaBaja, PDO::PARAM_STR);
            $consulta->bindValue(':clave', $empleado->getClave(), PDO::PARAM_STR);
            $consulta->execute();
            return $objAccesoDB->retornarUltimoInsertado();
        }
        
        /**
         * Sprint 1:
         * Me traigo todos los registros de la tabla empleados.
         */
        public static function obtenerTodos(){
            $objAccesoDB = AccesoDatos::obtenerObjetoAcceso();
            $consulta = $objAccesoDB->retornarConsulta("SELECT idEmpleado,rol,nombre,fechaAlta,fechaBaja,clave FROM empleados");
            $consulta->execute();

            return $consulta->fetchAll(PDO::FETCH_CLASS, 'Empleado');
        }

        
        public static function obtenerUnoPorUsuario($nombre,$clave){
            $objAccessoDB = AccesoDatos::obtenerObjetoAcceso();
            $consulta = $objAccessoDB->retornarConsulta("SELECT idEmpleado,rol,nombre,clave FROM empleados WHERE nombre = :nombre AND clave = :clave");
            $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
            $consulta->bindValue(':clave', $clave, PDO::PARAM_STR);
            $consulta->execute();

            return $consulta->fetchObject('Empleado');
        }

        /**
         * Me permite obtener un Empleado
         * de la tabla Empleados mediante su 
         * id.
         */
        public static function obtenerUno($valor){
            $objAccessoDB = AccesoDatos::obtenerObjetoAcceso();
            $consulta = $objAccessoDB->retornarConsulta("SELECT idEmpleado,rol,nombre,fechaAlta,fechaBaja,clave FROM empleados WHERE idEmpleado = :valor");
            $consulta->bindValue(':valor', $valor, PDO::PARAM_INT);
            $consulta->execute();

            return $consulta->fetchObject('Empleado');
        }

        /**
         * Podre modificar de un empleado
         * su rol y nombre.
         */
        public static function modificar($empleado){
            $objAccessoDB = AccesoDatos::obtenerObjetoAcceso();
            $consulta = $objAccessoDB->retornarConsulta("UPDATE empleados SET nombre = :nombre, rol = :rol, clave = :clave WHERE idEmpleado = :id");
            $consulta->bindValue(':id', $empleado->getIDEmpleado(), PDO::PARAM_INT);
            $consulta->bindValue(':nombre', $empleado->getNombre(), PDO::PARAM_STR);
            $consulta->bindValue(':rol', $empleado->getRol(), PDO::PARAM_STR);
            $consulta->bindValue(':rol', $empleado->getClave(), PDO::PARAM_STR);
            // $consulta->bindValue(':fechaAlta', $empleado->getFechaAlta(), PDO::PARAM_INT);
            // $consulta->bindValue(':fechaBaja', $empleado->getFechaBaja(), PDO::PARAM_STR);
            return $consulta->execute();
        }

        /**
         * Me permitira dar una baja logica, es decir,
         * asignarle una fecha de baja al empleado
         * correspondiente del id buscado.
         */
        public static function borrar($id){
            $fechaBaja = new DateTime(date("d-m-Y"));
            $objAccesoDato = AccesoDatos::obtenerObjetoAcceso();
            $consulta = $objAccesoDato->retornarConsulta("UPDATE empleados SET fechaBaja = :fechabaja WHERE idEmpleado = :id"); 
            $consulta->bindValue(':id', $id, PDO::PARAM_INT);
            $consulta->bindValue(':fechabaja',date_format($fechaBaja, 'Y-m-d'));
            return $consulta->execute();
        }
    }