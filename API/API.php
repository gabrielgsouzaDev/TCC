<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

require_once __DIR__ . '/../banco/conexao.php';
require_once __DIR__ . '/../banco/functions.php';
require_once __DIR__ . '/auth_controller.php';

$dados = json_decode(file_get_contents('php://input'), true);
$action = $_GET['action'] ?? '';

switch ($action) {

    //  CANTINAS 
    case 'listarCantinas':
        $id_escola = $_GET['id_escola'] ?? null;
        if (!$id_escola) jsonResponse(["erro" => "id_escola é obrigatório"], 400);

        $stmt = $conn->prepare("SELECT id_cantina, nome FROM tb_cantina WHERE id_escola = ?");
        $stmt->bind_param("i", $id_escola);
        $stmt->execute();
        $result = $stmt->get_result();
        $cantinas = $result->fetch_all(MYSQLI_ASSOC);

        jsonResponse($cantinas);
        break;

    //  PRODUTOS 
    case 'listarProdutos':
        $id_cantina = $_GET['id_cantina'] ?? null;
        if (!$id_cantina) jsonResponse(["erro" => "id_cantina é obrigatório"], 400);

        $stmt = $conn->prepare("SELECT id_produto, nome, preco FROM tb_produto WHERE id_cantina = ?");
        $stmt->bind_param("i", $id_cantina);
        $stmt->execute();
        $produtos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        jsonResponse($produtos);
        break;

    //  PEDIDOS 
    case 'fazerPedido':
        validarCampos(["id_aluno", "id_cantina", "itens"], $dados);

        $id_aluno = $dados["id_aluno"];
        $id_cantina = $dados["id_cantina"];
        $itens = $dados["itens"];

        $conn->begin_transaction();
        try {
            $stmt = $conn->prepare("INSERT INTO tb_pedido (id_aluno, id_cantina, status) VALUES (?, ?, 'pendente')");
            $stmt->bind_param("ii", $id_aluno, $id_cantina);
            $stmt->execute();
            $id_pedido = $conn->insert_id;

            $stmtItem = $conn->prepare("INSERT INTO tb_item_pedido (id_pedido, id_produto, quantidade, preco_unitario) VALUES (?, ?, ?, ?)");
            foreach ($itens as $item) {
                $stmtItem->bind_param("iiid", $id_pedido, $item['id_produto'], $item['quantidade'], $item['preco_unitario']);
                $stmtItem->execute();
            }

            $conn->commit();
            jsonResponse(["sucesso" => true, "id_pedido" => $id_pedido]);
        } catch (Exception $e) {
            $conn->rollback();
            jsonResponse(["erro" => "Falha ao registrar pedido: " . $e->getMessage()], 500);
        }
        break;

    //  SALDO 
    case 'getSaldo':
        $id_aluno = $_GET['id_aluno'] ?? null;
        if (!$id_aluno) jsonResponse(["erro" => "id_aluno é obrigatório"], 400);

        $stmt = $conn->prepare("SELECT saldo FROM tb_aluno WHERE id_aluno = ?");
        $stmt->bind_param("i", $id_aluno);
        $stmt->execute();
        $saldo = $stmt->get_result()->fetch_assoc();

        jsonResponse($saldo ?? ["saldo" => 0]);
        break;

    case 'updateSaldo':
        validarCampos(["id_aluno", "valor"], $dados);

        $stmt = $conn->prepare("UPDATE tb_aluno SET saldo = saldo + ? WHERE id_aluno = ?");
        $stmt->bind_param("di", $dados['valor'], $dados['id_aluno']);
        $ok = $stmt->execute();

        jsonResponse(["sucesso" => $ok]);
        break;

    //  HISTÓRICO 
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

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_aluno);
        $stmt->execute();
        $historico = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        jsonResponse($historico);
        break;

    //  AUTENTICAÇÃO RESPONSÁVEL 
    case 'cadastrarResponsavel':
        validarCampos(["nome", "email", "senha", "raAluno"], $dados);
        $resp = cadastrarResponsavel($dados["nome"], $dados["email"], $dados["senha"], $dados["raAluno"]);
        jsonResponse($resp);
        break;

    case 'loginResponsavel':
        validarCampos(["email", "senha"], $dados);
        $resp = loginResponsavel($dados["email"], $dados["senha"]);
        jsonResponse($resp);
        break;

    default:
        jsonResponse(["erro" => "Ação inválida ou não especificada."], 400);
}
?>
