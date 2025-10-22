<?php
require_once "firebase.php";

function validarTokenFirebase($idToken) {
    global $auth;
    try {
        $verifiedToken = $auth->verifyIdToken($idToken);
        return [
            "status" => "sucesso",
            "uid" => $verifiedToken->claims()->get('sub')
        ];
    } catch (\Kreait\Firebase\Exception\Auth\FailedToVerifyToken $e) {
        return ["status" => "erro", "mensagem" => "Token inválido"];
    }
}
