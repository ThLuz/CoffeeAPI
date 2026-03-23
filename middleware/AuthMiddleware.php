<?php

require_once "config/Database.php";
require_once "utils/Response.php";

class AuthMiddleware {

    public static function check(){

        $headers = getallheaders();

        if(!isset($headers["token"])){
            Response::json(["error"=>"Token required"],401);
        }

        $token = $headers["token"];

        $db = new Database();
        $conn = $db->connect();

        $sql = "SELECT * FROM users WHERE token=:token";

        $stmt = $conn->prepare($sql);
        $stmt->execute(["token"=>$token]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$user){

            Response::json(["error"=>"Invalid token"],401);
        }

        return $user;
    }

}