<?php

    class Uploader{
//********************************************* ATRIBUTO *********************************************
        private $_directorio;
//********************************************* CONSTRUCTOR *********************************************
        public function __construct($directorio)
        {
            $this->_directorio = $directorio;
            if (!file_exists($directorio)) {
                mkdir($directorio, 0777, true);
            }
        }
//********************************************* FUNCTIONES *********************************************
        /**
         * Me va a permitir guardar una imagen en 
         * el destino seleccionado.
         */
        public function guardarImagen($tempFile, $newFileName)
        {
            $destino = $this->_directorio . $newFileName; 
            var_dump($destino); // Agregar esta línea para depuración
            if (move_uploaded_file($tempFile, $destino)) { 
                return true;
            } else { 
                return false;
            }            
        }

        
        /**
         * Me va a permitir mover una imagen a un directorio de backup.
         * Se fija si existe la ruta de la imagen, si existe con rename 
         * intenta moverla rename(origen,destino).
         * 
         * @param string $rutaImagen la ruta actual de la imagen.
         * @param string $directorioRespaldo el directorio de respaldo
         * donde se guardara la imagen
         * @param string $nuevoNombre el nuevo nombre de la imagen.
         * 
         * @return bool true si pudo mover la imagen de directorio
         * correctamente, false caso contrario.
         */
        public function moverImagenABackUp($rutaImagen, $directorioRespaldo, $nuevoNombre) {
            //--Formo la ruta completa del path backup
            $rutaRespaldo = $directorioRespaldo . $nuevoNombre;
    
            if (file_exists($rutaImagen)) {
                //-->Muevo la imagen 
                return rename($rutaImagen, $rutaRespaldo);
            }
    
            return false;//-->No existe
        } 
    }