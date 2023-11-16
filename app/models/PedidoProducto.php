<?php
    /**
     * Clase para la tabla intermedia
     */
    class PedidoProducto{
//********************************************** ATRIBUTOS *************************************************************        
        public $id;
        public $codPedido;
        public $idProducto;
        public $tiempoEstimado;
        public $estado;//-->A nivel producto.
        public $idEmpleado;//-->Aquel empleado que se asigna la preparacion del producto.

//********************************************** GETTERS *************************************************************        
        public function getID(){
            return $this->id;
        }
        public function getCodPedido(){
            return $this->codPedido;
        }
        public function getIdProducto(){
            return $this->idProducto;
        }
        public function getEstado(){
            return $this->estado;
        }
        public function getIdEmpleado(){
            return $this->idEmpleado;
        }
//********************************************** SETTERS *************************************************************     
        public function setCodPedido($codPedido){
            if(isset($codPedido) && !empty($codPedido)){
                $this->codPedido = $codPedido;
            }
        }
        public function setIdProducto($idProducto){
            if(isset($idProducto) && is_int($idProducto)){
                $this->idProducto = $idProducto;
            }
        }
        public function setTiempoEstimado($tiempoEstimado){
            if(isset($tiempoEstimado)){
                $this->tiempoEstimado = $tiempoEstimado;
            }
        }
        public function setEstado($estado){
            if(isset($estado)){
                $this->estado = $estado;
            }
        }
        public function setIdEmpleado($idEmpleado){
            if(isset($idEmpleado) && is_int($idEmpleado)){
                $this->idEmpleado = $idEmpleado;
            }
        }
//********************************************** FUNCIONES *************************************************************        
        public static function crear($pedidoProducto){
            $objAccesoDB = AccesoDatos::obtenerObjetoAcceso();
            $consulta = $objAccesoDB->retornarConsulta("INSERT INTO pedidos_productos 
            (codPedido,idProducto,tiempoEstimado,estado,idEmpleado) VALUES ( :codPedido,:idProducto,:tiempoEstimado,:estado,:idEmpleado) ");

            $consulta->bindValue(':codPedido', $pedidoProducto->getCodPedido(), PDO::PARAM_STR);
            $consulta->bindValue(':idProducto', $pedidoProducto->getIdProducto(), PDO::PARAM_INT);
            $consulta->bindValue(':tiempoEstimado', $pedidoProducto->getTiempoEstimado());
            $consulta->bindValue(':estado', $pedidoProducto->getEstado(), PDO::PARAM_STR);
            $consulta->bindValue(':idEmpleado', $pedidoProducto->getIdEmpleado(), PDO::PARAM_INT);
            $consulta->execute();

            return $objAccesoDB->retornarUltimoInsertado();
        }

        public static function obtenerTodos(){
            $objAccessoDB = AccesoDatos::obtenerObjetoAcceso();
            $consulta = $objAccessoDB->retornarConsulta("SELECT * FROM pedidos_productos");
            $consulta->execute();

            return $consulta->fetchAll(PDO::FETCH_CLASS, 'PedidoProducto');
        }

        public static function obtenerUno($valor){
            $objAccessoDB = AccesoDatos::obtenerObjetoAcceso();
            $consulta = $objAccessoDB->retornarConsulta("SELECT id,codPedido,idProducto,tiempoEstimado,estado,idEmpleado 
            FROM pedidos_productos WHERE id = :valor");
            $consulta->bindValue(':valor', $valor, PDO::PARAM_INT);
            $consulta->execute();

            return $consulta->fetchObject('Pedido');
        }
    
        public static function modificar($pedidoProducto){
            // var_dump($pedidoProducto);
            $objAccessoDB = AccesoDatos::obtenerObjetoAcceso();
            $consulta = $objAccessoDB->retornarConsulta("UPDATE pedidos_productos SET codPedido = :codPedido,estado = :estado,
            tiempoEstimado = :tiempoEstimado, idEmpleado = :idEmpleado, idProducto = :idProducto
            WHERE idPedido = :id");
            $consulta->bindValue(':codPedido', $pedidoProducto->getCodPedido(), PDO::PARAM_STR);
            $consulta->bindValue(':estado', $pedidoProducto->getEstado(), PDO::PARAM_STR);
            $consulta->bindValue(':tiempoEstimado', $pedidoProducto->getTiempoEstimado());
            $consulta->bindValue(':idProducto', $pedidoProducto->getIdProducto(), PDO::PARAM_INT);
            $consulta->bindValue(':idEmpleado', $pedidoProducto->getIdEmpleado(), PDO::PARAM_INT);

            $consulta->execute();
        }
    }