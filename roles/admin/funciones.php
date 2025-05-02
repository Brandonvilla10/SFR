<?php
    include ('../../database/database.php');
    session_start();
    
    
    class consultas {
        
        
        private $con;
      

        public function __construct() {
            $conexion = new Database();
            $this->con = $conexion->conectar();
        }

        function SelectUser($documento){

            $sql = $this->con->prepare("SELECT * FROM usuario WHERE id_documento = $documento");
            $sql->execute();

            $sql = $sql->fetch(PDO::FETCH_ASSOC);

            return $sql;
        }

        function PersonalisedConsulte($campo, $tabla, $filtro,$valor){

            $sql = "SELECT $campo FROM $tabla WHERE $filtro = $valor";

            $stmt = $this->con->prepare($sql);
            $stmt->execute();
        
            // Devuelve una fila
            return $stmt->fetch(PDO::FETCH_ASSOC);
    
        }




    }