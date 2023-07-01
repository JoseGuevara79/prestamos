<?php
class BD{
    public static $intancia=null;
    public static function crearInstancia(){

        if( !isset(self::$intancia)){

            $opciones[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
            self::$intancia = new PDO('mysql:host=localhost;dbname=dbprestamos', 'root', '', $opciones);
            //echo "conexion exitosa";
        }
        return self::$intancia;
    }
}
?>
