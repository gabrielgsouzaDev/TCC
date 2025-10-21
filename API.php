<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

require_once __DIR__ . '/banco/conexao.php';
require_once __DIR__ . '/banco/functions.php';

$dados = json_decode(file_get_contents('php://input'), true);

$action = $_GET['action'] ?? '';

switch ($action) {

    case 'listarCantinas':
        $id_escola = $_GET['id_escola'] ?? null;
        if (!$id_escola) jsonResponse(["erro" => "id_escola é obrigatório"], 400);

        $stmt = $pdo->prepare("SELECT id_cantina, nome FROM tb_cantina WHERE id_escola = ?");
        $stmt->execute([$id_escola]);
        $cantinas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        jsonResponse($cantinas);
        break;

    case 'listarProdutos':
        $id_cantina = $_GET['id_cantina'] ?? null;
        if (!$id_cantina) jsonResponse(["erro" => "id_cantina é obrigatório"], 400);

        $stmt = $pdo->prepare("SELECT id_produto, nome, preco FROM tb_produto WHERE id_cantina = ?");
        $stmt->execute([$id_cantina]);
        $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        jsonResponse($produtos);
        break;

    case 'fazerPedido':
        if (!$dados) jsonResponse(["erro" => "Dados inválidos ou ausentes"], 400);
        validarCampos(["id_aluno", "id_cantina", "itens"], $dados);

        $id_aluno = $dados["id_aluno"];
        $id_cantina = $dados["id_cantina"];
        $itens = $dados["itens"];

        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("INSERT INTO tb_pedido (id_aluno, id_cantina, status) VALUES (?, ?, 'pendente')");
            $stmt->execute([$id_aluno, $id_cantina]);
            $id_pedido = $pdo->lastInsertId();

            $stmtItem = $pdo->prepare("INSERT INTO tb_item_pedido (id_pedido, id_produto, quantidade, preco_unitario) VALUES (?, ?, ?, ?)");
            foreach ($itens as $item) {
                $stmtItem->execute([
                    $id_pedido,
                    $item['id_produto'],
                    $item['quantidade'],
                    $item['preco_unitario']
                ]);
            }

            $pdo->commit();
            jsonResponse(["sucesso" => true, "id_pedido" => $id_pedido]);

        } catch (Exception $e) {
            $pdo->rollBack();
            jsonResponse(["erro" => "Falha ao registrar pedido: " . $e->getMessage()], 500);
        }
        break;

    case 'getSaldo':
        $id_aluno = $_GET['id_aluno'] ?? null;
        if (!$id_aluno) jsonResponse(["erro" => "id_aluno é obrigatório"], 400);

        $stmt = $pdo->prepare("SELECT saldo FROM tb_aluno WHERE id_aluno = ?");
        $stmt->execute([$id_aluno]);
        $saldo = $stmt->fetch(PDO::FETCH_ASSOC);

        jsonResponse($saldo ?? ["saldo" => 0]);
        break;

    case 'updateSaldo':
        if (!$dados) jsonResponse(["erro" => "Dados inválidos ou ausentes"], 400);
        validarCampos(["id_aluno", "valor"], $dados);

        $stmt = $pdo->prepare("UPDATE tb_aluno SET saldo = saldo + ? WHERE id_aluno = ?");
        $ok = $stmt->execute([$dados['valor'], $dados['id_aluno']]);

        jsonResponse(["sucesso" => $ok]);
        break;

    case 'historicoPedidos':
        $id_aluno = $_GET['id_aluno'] ?? null;
        if (!$id_aluno) jsonResponse(["erro" => "id_aluno é obrigatório"], 400);

        $sql = "SELECT p.id_pedido, p.dt_pedido, p.status, 
                       pr.nome AS produto, ip.quantidade, ip.preco_unitario
                FROM tb_pedido p
                JOIN tb_item_pedido ip ON p.id_pedido = ip.id_pedido
                JOIN tb_produto pr ON ip.id_produto = pr.id_produto
                WHERE p.id_aluno = ?
                ORDER BY p.dt_pedido DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_aluno]);
        $historico = $stmt->fetchAll(PDO::FETCH_ASSOC);

        jsonResponse($historico);
        break;

    default:
        jsonResponse(["erro" => "Ação inválida ou não especificada."], 400);
}
?>
