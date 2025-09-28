<?php
include("../banco/conexao.php");

$sql = "
SELECT 
    e.id_escola,
    e.nome AS nome,
    e.email_contato AS email_contato,
    CONCAT(end.logradouro, ', ', end.numero, 
           IF(end.complemento IS NOT NULL AND end.complemento != '', CONCAT(' - ', end.complemento), ''),
           ' - ', end.bairro, ' - ', end.cidade, '/', end.estado) AS endereco,
    p.nome AS plano_pagamento,
    e.status,
    e.qtd_alunos
FROM tb_escola e
LEFT JOIN tb_endereco end ON e.id_endereco = end.id_endereco
LEFT JOIN tb_plano p ON e.id_plano = p.id_plano
ORDER BY e.nome
";

try {
    $res = $pdo->query($sql);
    $escolas = $res->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($escolas);
} catch(PDOException $e) {
    header('Content-Type: application/json', true, 500);
    echo json_encode(['erro' => $e->getMessage()]);
}
?>
