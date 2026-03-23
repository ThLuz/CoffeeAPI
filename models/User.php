<?php

require_once "config/Database.php";

class User {

    private $conn;
    private $table = "users";

    public function __construct(){

        $db = new Database();
        $this->conn = $db->connect();
    }

    public function create($name,$email,$password){

        $sql = "INSERT INTO users(name,email,password) VALUES(:name,:email,:password)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            "name"=>$name,
            "email"=>$email,
            "password"=>password_hash($password,PASSWORD_DEFAULT)
        ]);
    }

    public function findByEmail($email){

        $sql = "SELECT * FROM users WHERE email=:email";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["email"=>$email]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function find($id){

        $sql = "SELECT id,name,email,drinkCounter FROM users WHERE id=:id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["id"=>$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function all(){

        $sql = "SELECT id,name,email,drinkCounter FROM users";

        $stmt = $this->conn->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}