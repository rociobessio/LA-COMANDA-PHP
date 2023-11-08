<?php

    require_once "./interfaces/ICrud.php";    
    require_once "./models/CrearCodigo.php";

    class Pedido implements ICrud{
//********************************************** ATRIBUTOS *************************************************************        
        public $idPedido;
        public $codigoPedido;
        public $idEmpleado;
        public $nombreCliente; 
        public $estado;//“pendiente”,“en preparación”,“listo para servir”,
        public $tiempoEstimadoPreparacion;
        public $tiempoInicio;
        public $tiempoFin;
        public $idMesa;
        public $fotoMesa;
        public $idProducto;
        public $cantidad; 
        public $pedidoFacturado;//-->false no se facturo aun, true si.
//********************************************** GETTERS *************************************************************        
        public function getIDEmpleado(){
            return $this->idEmpleado;
        }
        public function getIDPedido(){
            return $this->idPedido;
        }
        public function getNombreCliente(){
            return $this->nombreCliente;
        } 
        public function getEstado(){
            return $this->estado;
        }
        public function getTiempoEstimadoPreparacion(){
            return $this->tiempoEstimadoPreparacion;
        }
        public function getTiempoInicio(){
            return $this->tiempoInicio;
        }
        public function getTiempoFin(){
            return $this->tiempoFin;
        }
        public function getIDMesa(){
            return $this->idMesa;
        }
        public function getFotoMesa(){
            return $this->fotoMesa;
        } 
        public function getIDProducto(){
            return $this->idProducto;
        }
        public function getCantidad(){
            return $this->cantidad;
        }
        public function getCodigoPedido(){
            return $this->codigoPedido;
        }
        public function getPedidoFacturado(){
            return $this->pedidoFacturado;
        }
//********************************************** SETTERS *************************************************************        
        public function setIDEmpleado($idEmpleado){
            if(isset($idEmpleado) && is_int($idEmpleado)){
                $this->idEmpleado = $idEmpleado;
            }
        }
        public function setIDProducto($idProducto){
            if(isset($idProducto) && is_int($idProducto)){
                $this->idProducto = $idProducto;
            }
        } 
        public function setIDMesa($idMesa){
            if(isset($idMesa) && is_int($idMesa)){
                $this->idMesa = $idMesa;
            }
        }
        public function setNombreCliente($nombreCliente){
            if(isset($nombreCliente) && !empty($nombreCliente)){
                $this->nombreCliente = $nombreCliente;
            }
        } 
        public function setEstado($estado){
            if(isset($estado)){
                $this->estado = $estado;
            }
        }
        public function setTiempoEstimado($tiempoEstimado){
            if(isset($tiempoEstimado)){
                $this->tiempoEstimadoPreparacion = $tiempoEstimado;
            }
        }
        public function setTiempoInicio($tiempoInicio){
            if(isset($tiempoInicio)){
                $this->tiempoInicio = $tiempoInicio;
            }
        }
        public function setTiempoFin($tiempoFin){
            if(isset($tiempoFin)){
                $this->tiempoFin = $tiempoFin;
            }
        }
        public function setFotoMesa($fotoMesa){
            if(isset($fotoMesa)){
                $this->fotoMesa = $fotoMesa;
            }
        }
        public function setCantidad($cantidad){
            if(isset($cantidad)){
                $this->cantidad = $cantidad;
            }
        } 
        public function setCodigoPedido($codigoPedido){
            if(isset($codigoPedido)){
                $this->codigoPedido = $codigoPedido;
            }
        } 
        public function setPedidoFacturado($facturado){
            if(isset($facturado)){
                $this->pedidoFacturado = $facturado;
            }
        }
//********************************************** FUNCIONES *************************************************************        
                
        public static function crear($pedido){ 
            // $tiempoInicio = $pedido->getTiempoInicio();   
            // var_dump($pedido);

            $objAccesoDB = AccesoDatos::obtenerObjetoAcceso();
            $consulta = $objAccesoDB->retornarConsulta("INSERT INTO pedidos 
            (idEmpleado, idProducto, nombreCliente, estado, tiempoEstimadoPreparacion,, idMesa, fotoMesa, cantidad, codigoPedido, pedidoFacturado)
            VALUES (:idEmpleado, :idProducto, :nombreCliente, :estado, :tiempoEstimadoPreparacion, :idMesa, :fotoMesa, :cantidad, :codigoPedido, :pedidoFacturado)");

            $consulta->bindValue(':idEmpleado', $pedido->getIDEmpleado(), PDO::PARAM_INT);
            $consulta->bindValue(':idProducto', $pedido->getIDProducto(), PDO::PARAM_INT);
            $consulta->bindValue(':nombreCliente', $pedido->getNombreCliente(), PDO::PARAM_STR);
            $consulta->bindValue(':estado', $pedido->getEstado(), PDO::PARAM_STR);
            $consulta->bindValue(':tiempoEstimadoPreparacion', $pedido->getTiempoEstimadoPreparacion(), PDO::PARAM_INT);
            // $consulta->bindValue(':tiempoInicio', $tiempoInicio->format('H:i:sa'));  
            $consulta->bindValue(':idMesa', $pedido->getIDMesa(), PDO::PARAM_INT);
            $consulta->bindValue(':fotoMesa', $pedido->getFotoMesa());
            $consulta->bindValue(':cantidad', $pedido->getCantidad(), PDO::PARAM_INT); 
            $consulta->bindValue(':codigoPedido', $pedido->getCodigoPedido(), PDO::PARAM_STR);
            $consulta->bindValue(':pedidoFacturado', false, PDO::PARAM_BOOL);//-->Si se creo aun no esta facturado
            $consulta->execute();

            return $objAccesoDB->retornarUltimoInsertado();
        }

        public static function obtenerTodos(){
            $objAccessoDB = AccesoDatos::obtenerObjetoAcceso();
            $consulta = $objAccessoDB->retornarConsulta("SELECT * FROM pedidos");
            $consulta->execute();

            return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
        }

        public static function obtenerUno($valor){
            $objAccessoDB = AccesoDatos::obtenerObjetoAcceso();
            $consulta = $objAccessoDB->retornarConsulta("SELECT idPedido,idEmpleado,estado,tiempoEstimadoPreparacion,tiempoInicio,tiempoFin,idMesa,fotoMesa,
            idProducto,cantidad,nombreCliente,codigoPedido,pedidoFacturado FROM pedidos WHERE idPedido = :valor");
            $consulta->bindValue(':valor', $valor, PDO::PARAM_INT);
            $consulta->execute();

            return $consulta->fetchObject('Pedido');
        }

        public static function modificar($pedido){
            var_dump($pedido);
            $objAccessoDB = AccesoDatos::obtenerObjetoAcceso();
            $consulta = $objAccessoDB->retornarConsulta("UPDATE pedidos SET codigoPedido = :codigoPedido, fotoMesa = :fotoMesa,
            idMesa = :idMesa, idProducto = :idProducto, nombreCliente = :nombreCliente, estado = :estado, tiempoEstimadoPreparacion = :tiempoEstimadoPreparacion,
            tiempoInicio = :tiempoInicio,tiempoFin = :tiempoFin, pedidoFacturado = :pedidoFacturado WHERE idPedido = :id");
            $consulta->bindValue(':id', $pedido->getIDPedido(), PDO::PARAM_INT);
            $consulta->bindValue(':codigoPedido', $pedido->getCodigoPedido(), PDO::PARAM_STR);
            $consulta->bindValue(':fotoMesa', $pedido->getFotoMesa(), PDO::PARAM_STR);
            $consulta->bindValue(':idMesa', $pedido->getIDMesa(), PDO::PARAM_INT);
            $consulta->bindValue(':idProducto', $pedido->getIDProducto(), PDO::PARAM_INT);
            $consulta->bindValue(':nombreCliente', $pedido->getNombreCliente(), PDO::PARAM_STR);
            $consulta->bindValue(':estado', $pedido->getEstado(), PDO::PARAM_STR);
            $consulta->bindValue(':tiempoEstimadoPreparacion', $pedido->getTiempoEstimadoPreparacion(), PDO::PARAM_STR);
            $consulta->bindValue(':tiempoInicio', $pedido->getTiempoInicio(), PDO::PARAM_STR);
            $consulta->bindValue(':tiempoFin', $pedido->getTiempoFin(), PDO::PARAM_STR);
            $consulta->bindValue(':pedidoFacturado', $pedido->getPedidoFacturado(), PDO::PARAM_BOOL);

            $consulta->execute();
        }

        /**
         * No se elimina 
         * se cambia el estado a
         * si esta pagado o no.
         */
        public static function borrar($id){
            $objAccessoDB = AccesoDatos::obtenerObjetoAcceso();
            $consulta = $objAccessoDB->retornarConsulta("UPDATE pedidos SET pedidoFacturado = :pedidoFacturado WHERE idPedido = :id");
            $consulta->bindValue(':id', $id, PDO::PARAM_STR);
            $consulta->bindValue(':pedidoFacturado',true,PDO::PARAM_BOOL);
            $consulta->execute();
        }

        public static function ValidarPedido($rol){
            $sector = "vacio";
            switch ($rol)
            {
                case "Bartender":
                    $sector = "Vinoteca";
                    break;
                case "Cervecero":
                    $sector = "Cerveceria";
                    break;
                case "Cocinero":
                    $sector = "Cocina";
                    break;
                case "Candybar"://-->Pastelero no esta certificado en el enunciado
                    $sector = "CandyBar";
                break;
            }
            return $sector;
        
        }

}
