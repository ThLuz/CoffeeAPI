<?php

require_once "config/Database.php";
require_once "utils/Response.php";
require_once "middleware/AuthMiddleware.php";

class ReportController {

    public function history($id){

        AuthMiddleware::check();

        $db = new Database();
        $conn = $db->connect();

        $sql = "SELECT DATE(created_at) as date, SUM(quantity) as drinks FROM drinks WHERE user_id=:id GROUP BY DATE(created_at)";

        $stmt = $conn->prepare($sql);
        $stmt->execute(["id"=>$id]);

        Response::json($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function rankingDay($date){

        AuthMiddleware::check();

        $db = new Database();
        $conn = $db->connect();

        $sql = "SELECT u.name, SUM(d.quantity) as drinks FROM drinks d JOIN users u ON u.id=d.user_id 
        WHERE DATE(d.created_at)=:date GROUP BY u.id ORDER BY drinks DESC";

        $stmt = $conn->prepare($sql);
        $stmt->execute(["date"=>$date]);

        Response::json($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function rankingDays($days){

        AuthMiddleware::check();

        $db = new Database();
        $conn = $db->connect();

        $sql = "SELECT u.name, SUM(d.quantity) as drinks FROM drinks d JOIN users u ON u.id=d.user_id
        WHERE d.created_at >= DATE_SUB(NOW(), INTERVAL :days DAY) GROUP BY u.id ORDER BY drinks DESC";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":days",$days,PDO::PARAM_INT);

        $stmt->execute();

        Response::json($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

}