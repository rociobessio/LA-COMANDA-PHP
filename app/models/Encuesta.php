<?php
    require_once "./interfaces/ICrud.php";  
    class Encuesta implements ICrud{
//********************************************** ATRIBUTOS *************************************************************        
        public $idEncuesta;
        public $codPedido;
        public $comentario;
        public $puntuacionMesa;
        public $puntuacionMozo;
        public $puntuacionRestaurante;
        public $puntuacionCocinero;
        public $codMesa;
//********************************************** SETTERS *************************************************************        
        public function setCodPedido($cod){
            if(isset($cod) && !empty($cod)){
                $this->codPedido = $cod;
            }
        }
        public function setCodMesa($cod){
            if(isset($cod) && !empty($cod)){
                $this->codMesa = $cod;
            }
        }
        public function setComentario($comentario){
            if(isset($comentario) && !empty($comentario)){
                $this->comentario = $comentario;
            }
        }
        public function setPuntuacionMesa($valor){
            if(isset($valor) && is_int($valor) && self::validarPuntaje($valor)){
                $this->puntuacionMesa = $valor;
            }
        }
        public function setPuntuacionMozo($valor){
            if(isset($valor) && is_int($valor) && self::validarPuntaje($valor)){
                $this->puntuacionMozo = $valor;
            }
        }
        public function setPuntuacionRestaurante($valor){
            if(isset($valor) && is_int($valor) && self::validarPuntaje($valor)){
                $this->puntuacionRestaurante = $valor;
            }
        }
        public function setPuntuacionCocinero($valor){
            if(isset($valor) && is_int($valor) && self::validarPuntaje($valor)){
                $this->puntuacionCocinero = $valor;
            }
        }
//********************************************** GETTERS *************************************************************        
        public function getIdEncuesta(){
            return $this->idEncuesta;
        }
        public function getCodPedido(){
            return $this->codPedido;
        }
        public function getComentario(){
            return $this->comentario;
        }
        public function getPuntuacionMesa(){
            return $this->puntuacionMesa;
        }
        public function getPuntuacionMozo(){
            return $this->puntuacionMozo;
        }
        public function getPuntuacionRestaurante(){
            return $this->puntuacionRestaurante;
        }
        public function getPuntuacionCocinero(){
            return $this->puntuacionCocinero;
        }
        public function getCodMesa(){
            return $this->codMesa;
        }
//********************************************** FUNCIONES *************************************************************        
        public static function crear($encuesta)
        {
            $objAccesoDB = AccesoDatos::obtenerObjetoAcceso();
            $consulta = $objAccesoDB->retornarConsulta("INSERT INTO encuestas (codMesa, codPedido, comentario,
            puntuacionMesa,puntuacionMozo,puntuacionRestaurante,puntuacionCocinero) 
            VALUES (:codMesa, :codPedido, :comentario, :puntuacionMesa,:puntuacionMozo,:puntuacionRestaurante,:puntuacionCocinero)");
            $consulta->bindValue(':codMesa', $encuesta->getCodMesa(), PDO::PARAM_STR);
            $consulta->bindValue(':codPedido', $encuesta->getCodPedido(), PDO::PARAM_STR);
            $consulta->bindValue(':comentario', $encuesta->getComentario(), PDO::PARAM_STR);
            $consulta->bindValue(':puntuacionMesa', $encuesta->getPuntuacionMesa(), PDO::PARAM_INT);
            $consulta->bindValue(':puntuacionMozo', $encuesta->getPuntuacionMozo(), PDO::PARAM_INT);
            $consulta->bindValue(':puntuacionRestaurante', $encuesta->getPuntuacionRestaurante(), PDO::PARAM_INT);
            $consulta->bindValue(':puntuacionCocinero', $encuesta->getPuntuacionCocinero(), PDO::PARAM_INT);
            $consulta->execute();

            return $objAccesoDB->retornarUltimoInsertado();
        }


        public static function obtenerTodos(){
            $objAccessoDB = AccesoDatos::obtenerObjetoAcceso();
            $consulta = $objAccessoDB->retornarConsulta("SELECT * FROM encuestas");
            $consulta->execute();
    
            return $consulta->fetchAll(PDO::FETCH_CLASS, 'Encuesta');
        }

        public static function obtenerUno($id)
        {
            $objAccessoDB = AccesoDatos::obtenerObjetoAcceso();
            $consulta = $objAccessoDB->retornarConsulta("SELECT idEncuesta,codPedido,codMesa,comentario,puntuacionMesa,
            puntuacionMozo,puntuacionCocinero,puntuacionRestaurante FROM encuestas WHERE idEncuesta = :valor");
            $consulta->bindValue(':valor', $id, PDO::PARAM_INT);
            $consulta->execute();
    
            return $consulta->fetchObject('Facturacion');
        }
    
        public static function obtenerMejoresComentarios()
        {
            $objAccessoDB = AccesoDatos::obtenerObjetoAcceso();
            $consulta = $objAccessoDB->retornarConsulta("SELECT idFacturacion, codMesa, codPedido, puntuacionMesa,
            puntuacionRestaurante, puntuacionMozo, puntuacionCocinero, comentario,
            (puntuacionMesa + puntuacionRestaurante + puntuacionMozo + puntuacionCocinero)/4 AS promedio
            FROM encuestas ORDER BY promedio DESC");//-->Se ordenan los  mejores comentarios sumando los puntajes y sacando promedio
            $consulta->execute();
    
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        }

        public static function modificar($encuesta){
            $objAccessoDB = AccesoDatos::obtenerObjetoAcceso();
            $consulta = $objAccessoDB->retornarConsulta("UPDATE encuestas SET codPedido = :codigoPedido, codMesa = :codMesa,
            comentario = :comentario, pagada = :pagada WHERE idFacturacion = :id");
            $consulta->bindValue(':id', $encuesta->getIdEncuesta(), PDO::PARAM_INT);
            $consulta->bindValue(':codPedido', $encuesta->getCodPedido(), PDO::PARAM_STR);
            $consulta->bindValue(':codMesa', $encuesta->getCodMesa(), PDO::PARAM_STR);
            $consulta->bindValue(':comentario', $encuesta->getComentario(), PDO::PARAM_STR);
            //-->Faltarian los puntajes
    
            $consulta->execute();
        }

        public static function borrar($id){}

        /**
         * Valida que el puntaje este entre 1 y 10.
         * @param int $puntaje el puntaje es entero
         * @return bool false si no cumple, true si 
         * lo hace.
         */
        public static function validarPuntaje($puntaje){
            return $puntaje >= 1 && $puntaje <= 10;
        }
}