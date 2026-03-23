<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

require_once "config/Database.php";

$url = trim($_SERVER['REQUEST_URI'], "/");

$_GET['url'] = $url;

require_once "routes/api.php";