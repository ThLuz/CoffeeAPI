<?php

require_once "config/Database.php";

class Drink {

    private $conn;

    public function __construct(){

        $db = new Database();
        $this->conn = $db->connect();
    }

    public function add($user_id,$quantity){

        $sql = "INSERT INTO drinks(user_id,quantity) VALUES(:user_id,:quantity)";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            "user_id"=>$user_id,
            "quantity"=>$quantity
        ]);

        $sql2 = "UPDATE users SET drinkCounter = drinkCounter + :q WHERE id=:id";

        $stmt2 = $this->conn->prepare($sql2);

        $stmt2->execute([
            "q"=>$quantity,
            "id"=>$user_id
        ]);
    }

}