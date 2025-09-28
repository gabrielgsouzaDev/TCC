<?php
$host = 'db'; // nome do serviço MySQL no Docker
$dbname = 'ctnapp';
$username = 'root';
$password = 'root'; // se deixar vazio, configure o MYSQL_PASSWORD também

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(['erro' => 'Erro na conexão: ' . $e->getMessage()]));
}
?>
