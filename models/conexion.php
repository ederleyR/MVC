<?php
    class Conexion{
        private $mysqli;
        private $sql;
        private $result;
        private $filasAfectadas;
        public function conectar(){
            $host = 'localhost';
            $db = 'hotel_reservas';
            $user = 'root';
            $password = '';
            $this->mysqli = new mysqli($host,$user,$password,$db);
            if(mysqli_connect_error()){
                throw new Exception("Error de conexion a la base de datos");
            }
            return $this->mysqli;
        }

        public function desconectar(){
            $this->mysqli->close();
        }
        
        public function query($sql){
            $this->sql = $sql;
            $this->result = $this->mysqli->query($sql);
            $this->filasAfectadas = $this->mysqli->affected_rows;
        }
        public function getResult(){
            return $this->result;
        }
        public function getFilasAfectadas(){
            return $this->filasAfectadas;
        } 
    
       
    }
?>