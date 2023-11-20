<?php
    require_once "./interfaces/ICrud.php";    
    class Facturacion implements ICrud{
//********************************************** ATRIBUTOS *************************************************************        
    public $idFacturacion;
    public $pagada;
    public $total;
    public $idMesa;
    public $metodoPago;
    public $fechaFacturacion;
    public $codPedido;
//********************************************** SETTERS *************************************************************        
    public function setPagada($pagada){
        if(isset($pagada) && is_bool($pagada)){
            $this->pagada = $pagada;
        }
    }
    public function setTotal($total){
        if(isset($total)){
            $this->total = $total;
        }
    }
    public function setIdMesa($idMesa){
        if(isset($idMesa) && is_int($idMesa)){
            $this->idMesa = $idMesa;
        }
    }
    public function setMetodoPago($metodoPago){
        if(isset($metodoPago)){
            $this->metodoPago = $metodoPago;
        }
    }
    public function setFechaFacturacion($fecha){
        if(isset($fecha)){
            $this->fechaFacturacion = $fecha;
        }
    }
    public function setCodPedido($codPedido){
        if(isset($codPedido)){
            $this->codPedido = $codPedido;
        }
    }
//********************************************** GETTERS *************************************************************  
    public function getIdFactura(){
        return $this->idFacturacion;
    }      
    public function getPagada(){
        return $this->pagada;
    }
    public function getTotal(){
        return $this->total;
    }
    public function getMetodoPago(){
        return $this->metodoPago;
    }
    public function getIdMesa(){
        return $this->idMesa;
    }
    public function getFechaFacturacion(){
        return $this->fechaFacturacion;
    }
    public function getCodPedido(){
        return $this->codPedido;
    }
//********************************************** FUNCTIONS *************************************************************  
    public static function crear($factura){
        $objAccesoDB = AccesoDatos::obtenerObjetoAcceso();
        $consulta = $objAccesoDB->retornarConsulta("INSERT INTO facturaciones (total, idMesa, metodoPago, fechaFacturacion,
        pagada,codPedido) VALUES (:total, :idMesa, :metodoPago, :fechaFacturacion,:pagada,:codPedido)");
        $consulta->bindValue(':total', $factura->getTotal(), PDO::PARAM_INT);
        $consulta->bindValue(':idMesa', $factura->getIdMesa(), PDO::PARAM_INT);
        $consulta->bindValue(':metodoPago', $factura->getMetodoPago(), PDO::PARAM_STR);
        $consulta->bindValue(':fechaFacturacion', $factura->getFechaFacturacion(), PDO::PARAM_STR);
        $consulta->bindValue(':pagada', $factura->getPagada(), PDO::PARAM_BOOL);
        $consulta->bindValue(':codPedido', $factura->getCodPedido(), PDO::PARAM_STR);

        $consulta->execute();

        return $objAccesoDB->retornarUltimoInsertado();
    }

    public static function obtenerTodos(){
        $objAccessoDB = AccesoDatos::obtenerObjetoAcceso();
        $consulta = $objAccessoDB->retornarConsulta("SELECT * FROM facturaciones");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Facturacion');
    }
    
    public static function obtenerUno($id){
        $objAccessoDB = AccesoDatos::obtenerObjetoAcceso();
        $consulta = $objAccessoDB->retornarConsulta("SELECT idFacturacion,total,idMesa,metodoPago,fechaFacturacion,
        pagada,codPedido FROM facturaciones WHERE idFacturacion = :valor");
        $consulta->bindValue(':valor', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Facturacion');
    }

    public static function modificar($factura){
        $objAccessoDB = AccesoDatos::obtenerObjetoAcceso();
        $consulta = $objAccessoDB->retornarConsulta("UPDATE facturaciones SET codPedido = :codigoPedido, idMesa = :idMesa,
        metodoPago = :metodoPago, pagada = :pagada WHERE idFacturacion = :id");
        $consulta->bindValue(':id', $factura->getIdFactura(), PDO::PARAM_INT);
        $consulta->bindValue(':codPedido', $factura->getCodPedido(), PDO::PARAM_STR);
        $consulta->bindValue(':idMesa', $factura->getIdMesa(), PDO::PARAM_INT);
        $consulta->bindValue(':metodoPago', $factura->getMetodoPago(), PDO::PARAM_STR);
        $consulta->bindValue(':pagada', $factura->getPagada(), PDO::PARAM_BOOL);

        $consulta->execute();
    }

    public static function borrar($id){
        $objAccessoDB = AccesoDatos::obtenerObjetoAcceso();
        $consulta = $objAccessoDB->retornarConsulta("UPDATE facturaciones SET pagada = :pagada WHERE idFacturacion = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->bindValue(':pagada',true,PDO::PARAM_BOOL);
        $consulta->execute();
    }

    /**
     * Me permtira recibir una lista e ir
     * sumando para retornar el total del
     * pedido.
     */
    public function CalcularPrecioFinal($lista){
        $precioFinal = 0;
        foreach ($lista as $item)
        {
            $precioFinal = $precioFinal + ($item[0] * $item[1]);
        }
        return $precioFinal;
    }
}