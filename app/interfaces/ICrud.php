<?php
    /**
     * Esta interfaz me pertira implementar funciones basicas
     * para realizar un CRUD.
     */
    interface ICrud{
        public static function crear($obj);
	    public static function obtenerTodos();
	    public static function obtenerUno($valor);
	    public static function modificar($obj);
	    public static function borrar($obj);
    }