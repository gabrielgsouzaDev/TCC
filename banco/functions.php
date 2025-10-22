<?php
function jsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

function validarCampos(array $campos, array $dados) {
    foreach ($campos as $campo) {
        if (!isset($dados[$campo]) || $dados[$campo] === '') {
            jsonResponse(["erro" => "Campo obrigatório: $campo"], 400);
        }
    }
}
?>
