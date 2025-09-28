<?php
header('Content-Type: application/json');
include("../banco/conexao.php");

try {
    $stmt = $pdo->query("
        SELECT p.id_pedido AS id,
               COALESCE(a.nome, r.nome) AS nome,
               p.status,
               GROUP_CONCAT(CONCAT(ip.quantidade,'x ',pr.nome) SEPARATOR ', ') AS produtos,
               COALESCE(a.email, r.email) AS email
        FROM tb_pedido p
        LEFT JOIN tb_aluno a ON a.id_aluno = p.id_aluno
        LEFT JOIN tb_responsavel r ON r.id_responsavel = p.id_responsavel
        JOIN tb_item_pedido ip ON ip.id_pedido = p.id_pedido
        JOIN tb_produto pr ON pr.id_produto = ip.id_produto
        GROUP BY p.id_pedido
        ORDER BY p.id_pedido ASC
    ");

    $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($pedidos);

} catch(PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
