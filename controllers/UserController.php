<?php

require_once "models/User.php";
require_once "models/Drink.php";
require_once "middleware/AuthMiddleware.php";
require_once "utils/Response.php";
require_once "config/Database.php";

class UserController {

    public function create(){

        $data = json_decode(file_get_contents("php://input"),true);

        if(!isset($data["name"],$data["email"],$data["password"])){
            Response::json(["error"=>"Missing fields"],400);
        }

        $userModel = new User();

        if($userModel->findByEmail($data["email"])){
            Response::json(["error"=>"User already exists"],400);
        }

        $userModel->create(
            $data["name"],
            $data["email"],
            $data["password"]
        );

        Response::json(["message"=>"User created"]);
    }

    public function list(){
        AuthMiddleware::check();

        $page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
        
        $limit = 10; 
        $offset = ($page - 1) * $limit;

        $db = new Database();
        $conn = $db->connect();

        $sql = "SELECT id, name, email FROM users LIMIT :limit OFFSET :offset";
        $stmt = $conn->prepare($sql);
        
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        Response::json($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function get($id){

        AuthMiddleware::check();

        $userModel = new User();

        $user = $userModel->find($id);

        if(!$user){
            Response::json(["error"=>"User not found"],404);
        }

        Response::json($user);
    }

    public function update($id){

        $authUser = AuthMiddleware::check();

        if($authUser["id"] != $id){

            Response::json(["error"=>"Unauthorized"],403);
        }

        $data = json_decode(file_get_contents("php://input"),true);

        $db = new Database();
        $conn = $db->connect();

        $sql = "UPDATE users
                SET name=:name,
                    email=:email
                WHERE id=:id";

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            "name"=>$data["name"],
            "email"=>$data["email"],
            "id"=>$id
        ]);

        Response::json(["message"=>"User updated"]);
    }

    public function delete($id){

        $authUser = AuthMiddleware::check();

        if($authUser["id"] != $id){
            Response::json(["error"=>"Unauthorized"],403);
        }

        $db = new Database();
        $conn = $db->connect();

        $sqlDrinks = "DELETE FROM drinks WHERE user_id=:id";
        $stmtDrinks = $conn->prepare($sqlDrinks);
        $stmtDrinks->execute(["id"=>$id]);

        $sqlUser = "DELETE FROM users WHERE id=:id";
        $stmtUser = $conn->prepare($sqlUser);
        $stmtUser->execute(["id"=>$id]);

        Response::json(["message"=>"User and drinks deleted"]);
    }

    public function drink($id){
        $authUser = AuthMiddleware::check();

        if($authUser["id"] != $id){
            Response::json(["error"=>"Unauthorized"],403);
        }

        $data = json_decode(file_get_contents("php://input"), true);

        if(!$data || !isset($data["drink"])){
            Response::json(["error"=>"drink field required"],400);
        }

        $drink = new Drink();
        $drink->add($id, $data["drink"]);

        $userModel = new User();
        Response::json($userModel->find($id));
    }

}