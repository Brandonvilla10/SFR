<?php

class database{

    private $host ="localhost";
    private $database = "u721653865_databasesft";
    private $user = "u721653865_senaUser";
    private $password = "Z;O5y4&J4m*";
    private $charset = "utf8";


    function conectar(){


        try{
            $cone = "mysql:host=" . $this->host . "; dbname=" . $this->database . "; charset=" . $this->charset;

            $opcion = [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false
            ];

            $pdo = new PDO($cone,$this->user ,$this->password,$opcion);
            
            $pdo->exec("SET time_zone = '-05:00'");


            return $pdo;

        }catch(PDOException $e){

            echo "Error de conexcion" . $e->getMessage();
            exit();

        }

    }

}