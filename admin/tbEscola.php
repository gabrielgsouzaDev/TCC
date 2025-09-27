<?php
include("../banco/conexao.php");

$sql = "
SELECT 
    e.id_escola,
    e.nome AS nome,
    e.email_contato AS email_contato,
    CONCAT(end.logradouro, ', ', end.numero, ' - ', end.bairro) AS endereco,
    p.nome AS plano_pagamento,
    e.status,
    e.dt_ultimo_pagamento,
    e.qtd_alunos
FROM tb_escola e
LEFT JOIN tb_endereco end ON e.id_endereco = end.id_endereco
LEFT JOIN tb_plano p ON e.id_plano = p.id_plano
ORDER BY e.nome
";

$res = $pdo->query($sql);
$escolas = $res->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($escolas);
?>
