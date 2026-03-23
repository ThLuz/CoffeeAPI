<?php

require_once "controllers/UserController.php";
require_once "controllers/AuthController.php";
require_once "controllers/ReportController.php";

$url = $_GET['url'] ?? '';

// --- ADICIONE ESTA LINHA ABAIXO PARA CORRIGIR AS ROTAS ---
$url = explode('?', $url)[0]; 

$method = $_SERVER['REQUEST_METHOD'];

$userController = new UserController();
$authController = new AuthController();
$reportController = new ReportController();

if($url == "users" && $method=="POST"){
    $userController->create();
}

elseif($url == "users" && $method=="GET"){
    $userController->list();
}

elseif(preg_match("/users\/([0-9]+)$/",$url,$m)){
    if($method=="GET")    $userController->get($m[1]);
    if($method=="PUT")    $userController->update($m[1]);
    if($method=="DELETE") $userController->delete($m[1]);
}

elseif(preg_match("/users\/([0-9]+)\/drink/",$url,$m)){
    if($method=="POST")   $userController->drink($m[1]);
}

elseif(preg_match("/users\/([0-9]+)\/history/",$url,$m)){
    $reportController->history($m[1]);
}

elseif(preg_match("/ranking\/day\/(.+)/",$url,$m)){
    $reportController->rankingDay($m[1]);
}

elseif(preg_match("/ranking\/days\/([0-9]+)/",$url,$m)){
    $reportController->rankingDays($m[1]);
}

elseif($url == "login" && $method=="POST"){
    $authController->login();
}

else{
    echo json_encode(["error"=>"Route not found", "debug_url" => $url]);
}