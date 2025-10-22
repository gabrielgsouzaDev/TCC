<?php
header("Content-Type: application/json");
require_once "helpers.php";
require_once "../banco/conexao.php";

$input = json_decode(file_get_contents('php://input'), true);
$idToken = $input['idToken'] ?? '';

$validacao = validarTokenFirebase($idToken);
if ($validacao['status'] === 'erro') {
    echo json_encode($validacao);
    exit;
}

$uid = $validacao['uid'];

$stmt = $conn->prepare("SELECT id_responsavel FROM tb_responsavel WHERE uid_firebase = ?");
$stmt->bind_param("s", $uid);
$stmt->execute();
$responsavel = $stmt->get_result()->fetch_assoc();

if (!$responsavel) {
    echo json_encode(["status"=>"erro","mensagem"=>"Responsável não encontrado"]);
    exit;
}

$idResponsavel = $responsavel['id_responsavel'];

$stmt = $conn->prepare("
    SELECT a.id_aluno, a.nome, a.email, a.ra
    FROM tb_aluno a
    JOIN tb_aluno_responsavel ar ON a.id_aluno = ar.id_aluno
    WHERE ar.id_responsavel = ?
");
$stmt->bind_param("i", $idResponsavel);
$stmt->execute();
$alunos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

echo json_encode(["status"=>"sucesso","alunos"=>$alunos]);
