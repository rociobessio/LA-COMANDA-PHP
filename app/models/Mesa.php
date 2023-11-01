<?php

    require_once "./app/models/CrearCodigo.php";
    require_once "./app/interfaces/ICrud.php";

    /**
     * La clase Mesa implementara la interfaz
     * ICrud.
     */
    class Mesa implements ICrud{
//********************************************** ATRIBUTOS *************************************************************
        private $_id;
        private $_estado;
        private $_codigoMesa;//-->False esta BAJA, true esta activa
//********************************************** GETTERS *************************************************************
        public function getEstado(){
            return $this->_estado;
        }
        public function getID(){
            return $this->_id;
        }
        public function getCodigoMesa(){
            return $this->_codigoMesa;
        }
//********************************************** SETTERS *************************************************************
        public function setCodigoMesa($cod){
            if(isset($cod)){
                $this->_codigoMesa = $cod;
            }
        }
        public function setEstado($estado){
            if(isset($estado)){
                $this->_estado = $estado;
            }
        }
//********************************************** FUNCIONES *************************************************************
        
        /**
         * Esta function me permitira guardar una mesa en 
         * la tabla mesas.
         * 
         * @param Mesa $mesa un obj del tipo Mesa
         */
        public static function crear($mesa){
            $objAccessoDB = AccesoDatos::obtenerObjetoAcceso();
            $mesa->setCodigoMesa(CrearCodigo(5));//-->La mesa tiene un codigo de long 5
            $consulta = $objAccessoDB->retornarConsulta("INSERT INTO mesas (estado,codigoMesa) VALUES (:estado,:codigoMesa)");
            $consulta->bindValue(':estado', $mesa->getEstado(), PDO::PARAM_STR);
            $consulta->bindValue(':codigoMesa', $mesa->getCodigoMesa(), PDO::PARAM_STR);
            $consulta->execute();

            return $objAccessoDB->retornarUltimoInsertado();
        }

        /**
         * Me permite traerme todas la data de la tabla
         * mesas.
         */
        public static function obtenerTodos()
        {
            $objAccessoDB = AccesoDatos::obtenerObjetoAcceso();
            $consulta = $objAccessoDB->retornarConsulta("SELECT * FROM mesas");
            $consulta->execute();

            return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
        }

        /**
         * Me permite obtener un obj Mesa mediante
         * la coincidencia del ID.
         */
        public static function obtenerUno($value){
            $objAccessoDB = AccesoDatos::obtenerObjetoAcceso();
            $consulta = $objAccessoDB->retornarConsulta("SELECT idMesa,codigoMesa,estado FROM mesas WHERE idMesa = :valor");
            $consulta->bindValue(':valor', $value, PDO::PARAM_STR);
            $consulta->execute();

            return $consulta->fetchObject('Mesa');
        }

        /**
         * Me permite obtener un obj Mesa mediante
         * la coincidencia del codigo de mesa.
         * 
         * @param string $valor
         * @return Mesa el objeto mesa.
         */
        // public static function obtenerUnoPorCodigo($valor)
        // {
        //     $objAccessoDB = AccesoDatos::obtenerObjetoAcceso();
        //     $consulta = $objAccessoDB->retornarConsulta("SELECT idMesa, codigoMesa, estado FROM mesas WHERE codigoMesa = :valor");
        //     $consulta->bindValue(':valor', $valor, PDO::PARAM_STR);
        //     $consulta->execute();
    
        //     return $consulta->fetchObject('Mesa');
        // }

        /**
         * Me permitira modificar el estado de una mesa en la
         * tabla mesas mediante su id.
         * 
         * @param Mesa $mesa el obj de tipo Mesa
         */
        public static function modificar($mesa){
            $objAccessoDB = AccesoDatos::obtenerObjetoAcceso();
            $consulta = $objAccessoDB->retornarConsulta("UPDATE mesas SET estado = :estado WHERE idMesa = :id");
            $consulta->bindValue(':id', $mesa->getID(), PDO::PARAM_INT);
            $consulta->bindValue(':estado', $mesa->getEstado(), PDO::PARAM_STR);
            return $consulta->execute();
        }

        /**
         * Para implementar el crud completo, se podrá dar
         * de baja a una mesa, no eliminarla, solo cambiar su estado
         * a baja.
         */
        public static function borrar($id){
            $objAccesoDato = AccesoDatos::obtenerObjetoAcceso();
            $consulta = $objAccesoDato->retornarConsulta("UPDATE mesas SET estado = :estado WHERE id = :id");
            // $fecha = new DateTime(date("d-m-Y"));
            $consulta->bindValue(':id', $id, PDO::PARAM_STR);
            $consulta->bindValue(':estado', 'BAJA', PDO::PARAM_STR);//-->Se da de baja
            return $consulta->execute();
        }

}