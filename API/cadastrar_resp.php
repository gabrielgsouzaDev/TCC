<?php
header("Content-Type: application/json");
require_once "auth_controller.php";

$input = json_decode(file_get_contents('php://input'), true);
$nome = $input['nome'] ?? '';
$email = $input['email'] ?? '';
$senha = $input['senha'] ?? '';
$raAluno = $input['raAluno'] ?? '';

if (!$nome || !$email || !$senha || !$raAluno) {
    echo json_encode([
        "status" => "erro",
        "mensagem" => "Todos os campos são obrigatórios."
    ]);
    exit;
}

// Chama função do controller que integra Firebase + MySQL
$result = cadastrarResponsavel($nome, $email, $senha, $raAluno);

echo json_encode($result);
