<?php

require_once "models/User.php";
require_once "services/AuthService.php";
require_once "utils/Response.php";

class AuthController {

    public function login(){

        $data = json_decode(file_get_contents("php://input"),true);

        $userModel = new User();

        $user = $userModel->findByEmail($data["email"]);

        if(!$user){

            Response::json(["error"=>"User does not exist"],401);
        }

        if(!password_verify($data["password"],$user["password"])){

            Response::json(["error"=>"Invalid password"],401);
        }

        $token = AuthService::generateToken();

        $db = new Database();
        $conn = $db->connect();

        $sql = "UPDATE users SET token=:token WHERE id=:id";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            "token"=>$token,
            "id"=>$user["id"]
        ]);

        Response::json([
            "token"=>$token,
            "iduser"=>$user["id"],
            "email"=>$user["email"],
            "name"=>$user["name"],
            "drinkCounter"=>$user["drinkCounter"]
        ]);
    }

}