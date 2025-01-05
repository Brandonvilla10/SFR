

<?php

class database{

    private $host ="localhost";
    private $database = "databasesft";
    private $user = "root";
    private $password = "";
    private $charset = "utf8";


    function conectar(){


        try{
            $cone = "mysql:host=" . $this->host . "; dbname=" . $this->database . "; charset=" . $this->charset;

            $opcion = [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false
            ];

            $pdo = new PDO($cone,$this->user ,$this->password,$opcion);

            return $pdo;
            
        }catch(PDOException $e){

            echo "Error de conexcion" . $e->getMessage();
            exit();

        }
        
    }

}