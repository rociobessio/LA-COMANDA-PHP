<?php

    require_once "./interfaces/ICrud.php"; 

    class Producto implements ICrud{
//********************************************** ATRIBUTOS *************************************************************
        public $idProducto;
        public $nombre;
        public $sector;
        public $precio;
        public $tipo;
//********************************************** GETTERS *************************************************************
        public function getNombre(){
            return $this->nombre;
        }
        public function getSector(){
            return $this->sector;
        }
        public function getPrecio(){
            return $this->precio;
        }
        public function getTipo(){
            return $this->tipo;
        }
        public function getIdProducto(){
            return $this->idProducto;
        }
//********************************************** SETTERS *************************************************************
        public function setNombre($nombre){
            if(isset($nombre) && !empty($nombre)){
                $this->nombre = $nombre;
            }
        }
        public function setSector($sector){
            if(isset($sector) && !empty($sector)){
                $this->sector = $sector;
            }
        } 
        public function setPrecio($precio){
            if (isset($precio) && is_float($precio)) {
                $this->precio = $precio;
            }
        }  
        public function setTipo($tipo){
            if (isset($tipo) && !empty($tipo)) {
                $this->tipo = $tipo;
            }
        }
        
//********************************************** FUNCIONES *************************************************************
        /**
         * Sprint 1:
         * Me permitira crear un nuevo registro de 
         * un producto en la tabla 'productos'
         */
        public static function crear($producto){
            $objAccesoDB = AccesoDatos::obtenerObjetoAcceso();
            $consulta = $objAccesoDB->retornarConsulta("INSERT INTO productos (nombre,sector,precio,tipo) values (:nombre,:sector,:precio,:tipo)");
            $consulta->bindValue(':nombre', $producto->getNombre(), PDO::PARAM_STR);
            $consulta->bindValue(':sector', $producto->getSector(), PDO::PARAM_STR);
            $consulta->bindValue(':precio', $producto->getPrecio());
            $consulta->bindValue(':tipo', $producto->getTipo(), PDO::PARAM_STR);

            $consulta->execute();
            return $objAccesoDB->retornarUltimoInsertado();
            
        }
        /**
         * Sprint 1:
         * Me permitira obtener todos los registros
         * en la tabla productos.
         * 
         */
        public static function obtenerTodos(){
            $objAccesoDB = AccesoDatos::obtenerObjetoAcceso();
            $consulta = $objAccesoDB->retornarConsulta("SELECT idProducto,nombre,sector,precio,tipo FROM productos");
            $consulta->execute();

            return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
        }
        
        public static function obtenerUno($valor){
            $objAccessoDB = AccesoDatos::obtenerObjetoAcceso();
            $consulta = $objAccessoDB->retornarConsulta("SELECT idProducto,nombre,sector,precio,tipo FROM productos WHERE idProducto = :valor");
            $consulta->bindValue(':valor', $valor, PDO::PARAM_INT);
            $consulta->execute();

            return $consulta->fetchObject('Producto');
        }
        public static function modificar($prod){
            $objAccessoDB = AccesoDatos::obtenerObjetoAcceso();
            $consulta = $objAccessoDB->retornarConsulta("UPDATE productos SET nombre = :nombre, sector = :sector, precio = :precio,
            tipo = :tipo WHERE idProducto = :id");
            $consulta->bindValue(':id', $prod->getIdProducto(), PDO::PARAM_INT);
            $consulta->bindValue(':nombre', $prod->getNombre(), PDO::PARAM_STR);
            $consulta->bindValue(':sector', $prod->getSector(), PDO::PARAM_STR);
            $consulta->bindValue(':precio', $prod->getPrecio(), PDO::PARAM_INT);
            $consulta->bindValue(':tipo', $prod->getTipo(), PDO::PARAM_STR);
            return $consulta->execute();
        }

        /**
         * Me va a permitir asignarle la fecha
         * de baja a un producto de la tabla.
         * 
         * @param int $id el id del producto
         * a dar de baja.
         */
        public static function borrar($id){ 
            $fechaBaja = new DateTime(date("d-m-Y"));
            $objAccesoDato = AccesoDatos::obtenerObjetoAcceso();
            $consulta = $objAccesoDato->retornarConsulta("UPDATE productos SET fechaBaja = :fechabaja WHERE idProducto = :id"); 
            $consulta->bindValue(':id', $id, PDO::PARAM_INT);
            $consulta->bindValue(':fechabaja',date_format($fechaBaja, 'Y-m-d'));
            return $consulta->execute();
        }

}