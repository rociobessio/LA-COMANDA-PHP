<?php

    class AccesoDatos{
//********************************************** ATRIBUTOS *************************************************************
        private static $_objAccesoDB;
        private $_objPDO;
//********************************************** CONSTRUCTOR *************************************************************
        public function __construct()
        {
            try{
                $this->_objPDO = new PDO('mysql:host='.$_ENV['MYSQL_HOST'].';dbname='.$_ENV['MYSQL_DB'].';charset=utf8', $_ENV['MYSQL_USER'], $_ENV['MYSQL_PASS'], array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
                $this->_objPDO->exec("SET CHARACTER SET utf8");
            }
            catch(PDOException $ex){
                print "ERROR!: " . $ex->getMessage();
                die();
            }
        }
//********************************************** FUNCIONES *************************************************************
        /**
         * Me permitira retornar una consulta PDO.
         */
        public function retornarConsulta($sql)
        { 
            return $this->_objPDO->prepare($sql); 
        }

        /**
         * Me permtiria retornar el ultimo
         * id del la ultima fila insertada insertado
         */
        public function retornarUltimoInsertado()
        { 
            return $this->_objPDO->lastInsertId(); //-->Metodo de PDO
        }
        
        public static function obtenerObjetoAcceso()
        { 
            if (!isset(self::$_objAccesoDB)) {          
                self::$_objAccesoDB = new AccesoDatos(); 
            } 
            return self::$_objAccesoDB;        
        }

        public function __clone()
        { 
            trigger_error('La clonación de este objeto no está permitida', E_USER_ERROR); 
        }
    }