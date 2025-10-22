<?php
require_once "../banco/conexao.php";
require_once "../banco/functions.php";
require_once "firebase.php";
require_once "helpers.php";

/*Cadastra responsável no firebase e no banco */
function cadastrarResponsavel($nome, $email, $senha, $raAluno) {
    global $auth, $conn;

    try {
        $user = $auth->createUser([
            'email' => $email,
            'password' => $senha,
            'displayName' => $nome
        ]);
    } catch (\Kreait\Firebase\Exception\AuthException $e) {
        return ["status"=>"erro","mensagem"=>$e->getMessage()];
    }

    $stmt = $conn->prepare("SELECT id_aluno FROM tb_aluno WHERE ra = ?");
    $stmt->bind_param("s", $raAluno);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        return ["status" => "erro", "mensagem" => "RA não encontrado."];
    }

    $aluno = $result->fetch_assoc();
    $idAluno = $aluno["id_aluno"];

    $senhaHash = password_hash($senha, PASSWORD_BCRYPT);
    $stmt = $conn->prepare("INSERT INTO tb_responsavel (nome, email, senha_hash) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nome, $email, $senhaHash);
    if (!$stmt->execute()) {
        return ["status" => "erro", "mensagem" => "Falha ao criar responsável."];
    }

    $idResponsavel = $conn->insert_id;

    $stmt = $conn->prepare("INSERT INTO tb_aluno_responsavel (id_aluno, id_responsavel) VALUES (?, ?)");
    $stmt->bind_param("ii", $idAluno, $idResponsavel);
    $stmt->execute();

    $stmt = $conn->prepare("UPDATE tb_responsavel SET uid_firebase = ? WHERE id_responsavel = ?");
    $stmt->bind_param("si", $user->uid, $idResponsavel);
    $stmt->execute();

    return ["status"=>"sucesso","uid"=>$user->uid,"mensagem"=>"Conta criada!"];
}

/*Login do responsável com firebase token*/
function loginResponsavel($idToken) {
    $validacao = validarTokenFirebase($idToken);
    if ($validacao["status"] === "erro") return $validacao;

    $uid = $validacao["uid"];

    return ["status"=>"sucesso","uid"=>$uid];
}
