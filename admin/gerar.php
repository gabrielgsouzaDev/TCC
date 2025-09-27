<?php
include __DIR__ . "/../banco/conexao.php";

$stmt = $pdo->query("SELECT COUNT(*) AS total FROM tb_aluno");
var_dump($stmt->fetch(PDO::FETCH_ASSOC));

$stmt = $pdo->query("SELECT COUNT(*) AS total FROM tb_responsavel");
var_dump($stmt->fetch(PDO::FETCH_ASSOC));

$stmt = $pdo->query("SELECT COUNT(*) AS total FROM tb_cantineiro");
var_dump($stmt->fetch(PDO::FETCH_ASSOC));

?>