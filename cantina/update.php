<?php
include("../banco/conexao.php");
$data = json_decode(file_get_contents('php://input'), true);

if(isset($data['id'], $data['status'])){
    $stmt = $pdo->prepare("UPDATE tb_pedidos SET status=? WHERE id=?");
    $stmt->execute([$data['status'], $data['id']]);
    echo json_encode(['success'=>true]);
} else {
    echo json_encode(['success'=>false,'error'=>'Dados incompletos']);
}
?>