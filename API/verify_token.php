<?php
require_once "helpers.php";

$input = json_decode(file_get_contents('php://input'), true);
$idToken = $input['idToken'] ?? '';

$result = validarTokenFirebase($idToken);
header('Content-Type: application/json');
echo json_encode($result);
