<?php
    class Conexion{
        private static $instancia = null;
        private $con;

        private function __construct(){
            $this->con = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME) or die("<script> window.location = 'instalador.php' </script>");
        }

        public static function getInstancia(){
            if(self::$instancia == null)
                self::$instancia = new Conexion(); 
            
            return self::$instancia->con;
        }

        # Solo para select
        public static function consulta($sql){
            $con = self::getInstancia();
            $rs = mysqli_query($con, $sql);

            $datos = array();
            
            while($fila = mysqli_fetch_array($rs)){
                $datos[] = $fila;
            }

            return $datos;
        }
        
        function __destruct(){
            mysqli_close($this->con);
        }
    }    
?>