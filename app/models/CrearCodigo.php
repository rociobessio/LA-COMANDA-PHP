<?php

    /**
     * Esta function me permitira generar un codigo de 
     * caracteres y retornarla segun la longitud deseada.
     * 
     * @param int $longitud la longitud de la cadena. 
     */
    function CrearCodigo($longitud)
    {
        $caracteres = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $codigo = "";

        for ($i = 0; $i < $longitud; $i++) {
            $codigo .= $caracteres[rand(0, strlen($caracteres) - 1)];
        }

        return $codigo; // Asegúrate de que el código se retorne aquí
    }
