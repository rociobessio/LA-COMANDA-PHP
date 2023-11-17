<?php

    require_once "./interfaces/ICrud.php";    
    require_once "./models/CrearCodigo.php";

    class Pedido implements ICrud{
//********************************************** ATRIBUTOS *************************************************************        
        public $idPedido;
        public $codigoPedido;
        public $nombreCliente; 
        public $estado;//“pendiente”,“en preparación”,“listo para servir”,
        public $tiempoEstimadoPreparacion;
        public $tiempoInicio;//-->Cuando inicia
        public $tiempoFin;//-->se queda
        public $idMesa;
        public $fotoMesa;
        public $pedidoFacturado;//-->false no se facturo aun, true si.
        public $costoTotal;
//********************************************** GETTERS *************************************************************        
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
        public function getCostoTotal(){
            return $this->costoTotal;
        }
        public function getCodigoPedido(){
            return $this->codigoPedido;
        }
        public function getPedidoFacturado(){
            return $this->pedidoFacturado;
        }
//********************************************** SETTERS *************************************************************        
        public function setCostoTotal($costoTotal){
            if(isset($costoTotal)){
                $this->costoTotal = $costoTotal;
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
            $objAccesoDB = AccesoDatos::obtenerObjetoAcceso();
            $consulta = $objAccesoDB->retornarConsulta("INSERT INTO pedidos 
            (nombreCliente, estado, tiempoEstimadoPreparacion, idMesa, fotoMesa, codigoPedido, pedidoFacturado,costoTotal)
            VALUES ( :nombreCliente, :estado, :tiempoEstimadoPreparacion, :idMesa, :fotoMesa, :codigoPedido, :pedidoFacturado, :costoTotal)");

            $consulta->bindValue(':nombreCliente', $pedido->getNombreCliente(), PDO::PARAM_STR);
            $consulta->bindValue(':estado', $pedido->getEstado(), PDO::PARAM_STR);
            $consulta->bindValue(':tiempoEstimadoPreparacion', $pedido->getTiempoEstimadoPreparacion(), PDO::PARAM_INT);
            $consulta->bindValue(':idMesa', $pedido->getIDMesa(), PDO::PARAM_INT);
            $consulta->bindValue(':fotoMesa', $pedido->getFotoMesa());
            $consulta->bindValue(':codigoPedido', $pedido->getCodigoPedido(), PDO::PARAM_STR);
            $consulta->bindValue(':pedidoFacturado', false, PDO::PARAM_BOOL);//-->Si se creo aun no esta facturado
            $consulta->bindValue(':costoTotal', $pedido->getCostoTotal(), PDO::PARAM_INT);
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
            $consulta = $objAccessoDB->retornarConsulta("SELECT idPedido,estado,tiempoEstimadoPreparacion,tiempoInicio,tiempoFin,idMesa,fotoMesa,
            nombreCliente,codigoPedido,pedidoFacturado FROM pedidos WHERE idPedido = :valor");
            $consulta->bindValue(':valor', $valor, PDO::PARAM_INT);
            $consulta->execute();

            return $consulta->fetchObject('Pedido');
        }

        public static function modificar($pedido){
            // var_dump($pedido);
            $objAccessoDB = AccesoDatos::obtenerObjetoAcceso();
            $consulta = $objAccessoDB->retornarConsulta("UPDATE pedidos SET codigoPedido = :codigoPedido, fotoMesa = :fotoMesa,
            idMesa = :idMesa, nombreCliente = :nombreCliente, estado = :estado, tiempoEstimadoPreparacion = :tiempoEstimadoPreparacion,
            tiempoInicio = :tiempoInicio,tiempoFin = :tiempoFin, pedidoFacturado = :pedidoFacturado, costoTotal = :costoTotal WHERE idPedido = :id");
            $consulta->bindValue(':id', $pedido->getIDPedido(), PDO::PARAM_INT);
            $consulta->bindValue(':codigoPedido', $pedido->getCodigoPedido(), PDO::PARAM_STR);
            $consulta->bindValue(':fotoMesa', $pedido->getFotoMesa(), PDO::PARAM_STR);
            $consulta->bindValue(':idMesa', $pedido->getIDMesa(), PDO::PARAM_INT);
            $consulta->bindValue(':nombreCliente', $pedido->getNombreCliente(), PDO::PARAM_STR);
            $consulta->bindValue(':estado', $pedido->getEstado(), PDO::PARAM_STR);
            $consulta->bindValue(':tiempoEstimadoPreparacion', $pedido->getTiempoEstimadoPreparacion(), PDO::PARAM_STR);
            $consulta->bindValue(':tiempoInicio', $pedido->getTiempoInicio(), PDO::PARAM_STR);
            $consulta->bindValue(':tiempoFin', $pedido->getTiempoFin(), PDO::PARAM_STR);
            $consulta->bindValue(':pedidoFacturado', $pedido->getPedidoFacturado(), PDO::PARAM_BOOL);
            $consulta->bindValue(':costoTotal', $pedido->getCostoTotal(), PDO::PARAM_INT);
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

        /**
         * @param int $idMesa el id de la mesa.
         * @param string $codigoPedido el codigo
         * del pedido a buscar.
         */
        public static function ObtenerDemoraPedido($idMesa, $codigoPedido){
            $objAccessoDB = AccesoDatos::obtenerObjetoAcceso();
            $consulta = $objAccessoDB->retornarConsulta("
                SELECT 
                    p.tiempoEstimado AS demoraEstimada,
                    p.estado AS estadoPedido,
                    pr.nombre AS nombreProducto
                FROM pedidos AS p
                INNER JOIN productos AS pr ON p.idProducto = pr.idProducto
                WHERE p.idMesa = :idMesa AND p.codigoPedido = :codigoPedido
            ");
            $consulta->bindValue(':idMesa', $idMesa, PDO::PARAM_INT);
            $consulta->bindValue(':codigoPedido', $codigoPedido, PDO::PARAM_STR);
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        }
        
        /**
         * Listar los pedidos pendientes del tipo de empleado
         */
        public static function GetPedidosPendientes($rol)
        {
            $sector = Producto::ValidarPedido($rol);

            $objAccessoDB = AccesoDatos::obtenerObjetoAcceso();
            $consulta = $objAccessoDB->retornarConsulta("SELECT pedidos.*
            FROM pedidos
            INNER JOIN productos ON pedidos.idProducto = productos.idProducto
            WHERE pedidos.estado = :estado AND productos.sector = :sector");
            $consulta->bindValue(':estado', "pendiente");
            $consulta->bindValue(':sector', $sector );
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
        }

        /**
         * Me permitira traerme todos los pedidos
         * cuyo estado sea igual a 'listo para servir'
         */
        public static function obtenerPedidosListos(){
            $objAccesoDatos = AccesoDatos::obtenerObjetoAcceso();
            $consulta = $objAccesoDatos->retornarConsulta("SELECT * FROM pedidos WHERE estado = :estado");
            $consulta->bindValue(':estado', "listo para servir");
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
        }
}
