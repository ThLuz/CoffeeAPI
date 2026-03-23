<?php

class Database {

    private $host = "localhost";
    private $db   = "coffee_api";
    private $user = "root";
    private $pass = "";
    public $conn;

    public function connect(){

        $this->conn = null;

        try{

            $this->conn = new PDO(
                "mysql:host=".$this->host.";dbname=".$this->db,
                $this->user,
                $this->pass
            );

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        }catch(PDOException $e){
            echo json_encode(["error"=>$e->getMessage()]);
        }

        return $this->conn;
    }

}