<?php
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();include __DIR__ . "/../banco/conexao.php";
if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['erro' => 'Acesso não autorizado']);
    exit;
}

try {
    $stmt = $pdo->query("SELECT COUNT(*) AS total FROM tb_escola WHERE status = 'ativa'");
    $escolasAtivas = (int)($stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);

    $stmt = $pdo->query("SELECT COUNT(*) AS total FROM tb_aluno");
    $totalAlunos = (int)($stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);

    $stmt = $pdo->query("SELECT COUNT(*) AS total FROM tb_responsavel");
    $totalResponsaveis = (int)($stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);

    $stmt = $pdo->query("SELECT COUNT(*) AS total FROM tb_cantineiro");
    $totalCantineiros = (int)($stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);

    $usuariosResumo = [
        'aluno' => $totalAlunos,
        'responsavel' => $totalResponsaveis,
        'cantineiro' => $totalCantineiros
    ];

    $stmt = $pdo->query("
        SELECT COUNT(*) AS total 
        FROM tb_pedidos 
        WHERE MONTH(dt_pedido) = MONTH(CURDATE())
          AND YEAR(dt_pedido) = YEAR(CURDATE())
    ");
    $pedidosMes = (int)($stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);

    $graficoPedidos = array_fill(0, 12, 0);
    $stmt = $pdo->query("
        SELECT MONTH(dt_pedido) AS mes, COUNT(*) AS total
        FROM tb_pedidos
        WHERE YEAR(dt_pedido) = YEAR(CURDATE())
        GROUP BY MONTH(dt_pedido)
    ");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $graficoPedidos[(int)$row['mes'] - 1] = (int)$row['total'];
    }
    
    echo json_encode([
        'escolasAtivas' => $escolasAtivas,
        'usuariosResumo' => $usuariosResumo,
        'pedidosMes' => $pedidosMes,
        'graficoFaturamento' => $graficoPedidos
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['erro' => $e->getMessage()]);
}
exit;
?>
