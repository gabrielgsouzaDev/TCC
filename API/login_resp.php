<?php
header("Content-Type: application/json");
require_once "auth_controller.php";

$input = json_decode(file_get_contents('php://input'), true);
$idToken = $input['idToken'] ?? '';

$result = loginResponsavel($idToken);
echo json_encode($result);
