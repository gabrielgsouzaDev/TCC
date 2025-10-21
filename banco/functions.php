<?php
function jsonResponse($data, $status = 200) {
    http_response_code($status);
    header("Content-Type: application/json; charset=utf-8");
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

function validarCampos($campos, $dados) {
    foreach ($campos as $campo) {
        if (!isset($dados[$campo]) || empty($dados[$campo])) {
            jsonResponse(["erro" => "Campo obrigatório ausente: $campo"], 400);
        }
    }
}
?>
