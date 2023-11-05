<?php

    require_once "./models/CrearCodigo.php";
    require_once "./interfaces/ICrud.php"; 
    /**
     * La clase Mesa implementara la interfaz
     * ICrud.
     */
    class Mesa implements ICrud{
//********************************************** ATRIBUTOS *************************************************************
        public $idMesa;
        public $estado;//-->False esta BAJA, true esta activa
        public $codigoMesa;
//********************************************** GETTERS *************************************************************
        public function getEstado(){
            return $this->estado;
        }

        public function getIdMesa(){
            return $this->idMesa;
        }
        public function getCodigoMesa(){
            return $this->codigoMesa;
        }
//********************************************** SETTERS *************************************************************
        public function setCodigoMesa($cod){
            if(isset($cod)){
                $this->codigoMesa = $cod;
            }
        }
        public function setEstado($estado){
            if(isset($estado)){
                $this->estado = $estado;
            }
        }
        public function setIdMesa($id){
            if(isset($id)){
                $this->idMesa = $id;
            }
        }
//********************************************** CONSTRUCTOR *************************************************************
        public function __construct(){}
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
            $consulta = $objAccessoDB->retornarConsulta("SELECT idMesa,estado,codigoMesa FROM mesas");
            $consulta->execute();
            // var_dump($consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa'));
            return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
        }

        /**
         * Me permite obtener un obj Mesa mediante
         * la coincidencia del ID.
         */
        public static function obtenerUno($value){
            var_dump($value);
            $objAccessoDB = AccesoDatos::obtenerObjetoAcceso();
            $consulta = $objAccessoDB->retornarConsulta("SELECT idMesa,codigoMesa,estado FROM mesas WHERE idMesa = :valor");
            $consulta->bindValue(':valor', $value, PDO::PARAM_INT);
            $consulta->execute();

            return $consulta->fetchObject('Mesa');
        }

        /**
         * Me permitira modificar el estado de una mesa en la
         * tabla mesas mediante su id.
         * 
         * @param Mesa $mesa el obj de tipo Mesa
         */
        public static function modificar($mesa){
            // var_dump($mesa->getEstado());
            // var_dump($mesa->getIdMesa());
            $objAccessoDB = AccesoDatos::obtenerObjetoAcceso();
            $consulta = $objAccessoDB->retornarConsulta("UPDATE mesas SET estado = :estado WHERE idMesa = :id");
            $consulta->bindValue(':id', $mesa->getIdMesa(), PDO::PARAM_INT);
            $consulta->bindValue(':estado', $mesa->getEstado(), PDO::PARAM_STR);
            return $consulta->execute();
        }

        /**
         * Para implementar el crud completo, se podrá dar
         * de baja a una mesa
         */
        public static function borrar($id) {
            $objAccesoDato = AccesoDatos::obtenerObjetoAcceso();
            $consulta = $objAccesoDato->retornarConsulta("UPDATE mesas SET estado = :estado WHERE idMesa = :id"); 
            $consulta->bindValue(':id', $id, PDO::PARAM_INT);
            $consulta->bindValue(':estado', "BAJA", PDO::PARAM_STR);
            return $consulta->execute();
        }
        
}