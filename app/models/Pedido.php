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
        public $tiempoFin;//-->Cuando termina de prepararse.
        public $idMesa;
        public $fotoMesa;
        public $pedidoFacturado;//-->false no se facturo aun, true si.
        public $costoTotal;
//********************************************** GETTERS *************************************************************        
        public function getIdPedido(){
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
            if(isset($estado) &&  !empty($estado)){
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

        /**
         * Me permtiria obtener un pedido mediante
         * su codigo de pedido.
         * 
         * @param string $codPedido el codigo del
         * pedido.
         */
        public static function obtenerUnoPorCodigoPedido($codPedido){
            $objAccessoDB = AccesoDatos::obtenerObjetoAcceso();
            $consulta = $objAccessoDB->retornarConsulta("SELECT idPedido,estado,tiempoEstimadoPreparacion,tiempoInicio,tiempoFin,idMesa,fotoMesa,
            nombreCliente,codigoPedido,pedidoFacturado,costoTotal FROM pedidos WHERE codigoPedido = :codPedido");
            $consulta->bindValue(':codPedido', $codPedido, PDO::PARAM_STR);
            $consulta->execute();

            return $consulta->fetchObject('Pedido');
        }

        /**
         * Me permtira obtener un pedido
         * mediante la coincidencia de su codigo de pedido
         * y el codigo de la mesa.
         */
        public static function obtenerUnoPorCodigoPedidoYCodigoMesa($codPedido,$codigoMesa){
            $objAccessoDB = AccesoDatos::obtenerObjetoAcceso();
            $consulta = $objAccessoDB->retornarConsulta("SELECT p.*
            FROM pedidos AS p
            INNER JOIN mesas AS m ON p.idMesa = m.idMesa
            WHERE p.codigoPedido = :codPedido AND m.codigoMesa = :codigoMesa");
            $consulta->bindValue(':codPedido', $codPedido, PDO::PARAM_STR);
            $consulta->bindValue(':codigoMesa', $codigoMesa, PDO::PARAM_STR);
            $consulta->execute();

            return $consulta->fetchObject('Pedido');
        }

        public static function modificar($pedido){
            // var_dump($pedido);
            $objAccessoDB = AccesoDatos::obtenerObjetoAcceso();
            $consulta = $objAccessoDB->retornarConsulta("UPDATE pedidos SET fotoMesa = :fotoMesa,
            idMesa = :idMesa, nombreCliente = :nombreCliente, estado = :estado, tiempoEstimadoPreparacion = :tiempoEstimadoPreparacion,
            tiempoInicio = :tiempoInicio,tiempoFin = :tiempoFin, pedidoFacturado = :pedidoFacturado, costoTotal = :costoTotal WHERE idPedido = :id");
            $consulta->bindValue(':id', $pedido->getIdPedido(), PDO::PARAM_INT);
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
         * Me permtira obtener la demora de los pedidos
         * de una mesa
         * @param int $codigoMesa el codigo de la mesa.
         * @param string $codigoPedido el codigo
         * del pedido a buscar.
        */
        public static function ObtenerDemoraPedido($codigoMesa, $codigoPedido) { 
            $pedido = Pedido::obtenerUnoPorCodigoPedidoYCodigoMesa($codigoPedido,$codigoMesa);

            if($pedido){
                // var_dump($pedido);
                if($pedido->getEstado() === "En preparacion"){
                    $restante = Pedido::calcularTiempoDemoraPedido($pedido->getTiempoInicio(),$pedido->getTiempoEstimadoPreparacion());
                    // var_dump($restante);
                    if($restante > 0){
                        return "El tiempo de demora del pedido es: " . $restante . " minutos!";
                    }
                    else{
                        return "El pedido ya deberia de finalizar, quedan: " . $restante . " minutos.";
                    }
                }
                elseif($pedido->getEstado === "pendiente"){
                    return "El pedido aún no se ha comenzado a preparar.";
                }
            }
        }

        /**
         * En este caso se retornara un arrya con la informacion
         * sobre la demora de los pedidos en preparacion/pendientes.
         */
        public static function ObtenerDemoraPedidosSocios($codigoMesa, $codigoPedido) { 
            $pedido = Pedido::obtenerUnoPorCodigoPedidoYCodigoMesa($codigoPedido, $codigoMesa);
        
            if ($pedido) {
                if ($pedido->getEstado() === "En preparacion") {
                    $restante = Pedido::calcularTiempoDemoraPedido($pedido->getTiempoInicio(), $pedido->getTiempoEstimadoPreparacion());
                    if ($restante > 0) {
                        return array(
                            'codigoPedido' => $pedido->getCodigoPedido(),
                            'demora' => $restante,
                            'nombreCliente' => $pedido->getNombreCliente(), 
                        );
                    } else {
                        return array(
                            'codigoPedido' => $pedido->getCodigoPedido(),
                            'mensaje' => "El pedido ya debería haber finalizado.", 
                        );
                    }
                } elseif ($pedido->getEstado() === "pendiente") {
                    return array(
                        'codigoPedido' => $pedido->getCodigoPedido(),
                        'mensaje' => "El pedido aún no se ha comenzado a preparar.", 
                    );
                }
            }
        }
        
        
        /**
         * Listar los pedidos pendientes del tipo de empleado.
         */
        public static function GetPedidosPendientes($rol){
            $sector = Producto::ValidarPedido($rol);

            $objAccessoDB = AccesoDatos::obtenerObjetoAcceso();
            $consulta = $objAccessoDB->retornarConsulta("
                SELECT pedidos_productos.id, 
                    pedidos_productos.codPedido, 
                    pedidos_productos.idProducto, 
                    pedidos_productos.tiempoEstimado, 
                    pedidos_productos.estado, 
                    pedidos_productos.idEmpleado
                FROM pedidos_productos
                INNER JOIN productos ON pedidos_productos.idProducto = productos.idProducto
                WHERE pedidos_productos.estado = :estado AND productos.sector = :sector
            ");
            $consulta->bindValue(':estado', "pendiente");
            $consulta->bindValue(':sector', $sector );
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_CLASS, 'PedidoProducto');
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

        /**
         * Me va a permitir calcular el tiempo 
         * de demora de un pedido.
         */
        private static function calcularTiempoDemoraPedido($inicio,$tiempoFin){
            $fechaFin = new DateTime($inicio);
            $tiempo_array = explode(":", $tiempoFin);
    
            $minutos = $tiempo_array[1];
    
            $fechaFin->modify("+" . $minutos . " minutes"); 

            $fecha_actual = new DateTime();
     
            $intervalo = $fecha_actual->diff($fechaFin);
     
            $minutos_restantes = $intervalo->format('%r%I');
    
            return intval($minutos_restantes);
        }
}
