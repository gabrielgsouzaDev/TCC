<?php
require_once "../banco/conexao.php";
require_once "../banco/functions.php";

function cadastrarResponsavel($nome, $email, $senha, $raAluno) {
    global $conn;

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

    return ["status" => "sucesso", "mensagem" => "Conta criada com sucesso!"];
}

function loginResponsavel($email, $senha) {
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM tb_responsavel WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        return ["status" => "erro", "mensagem" => "Email não encontrado."];
    }

    $user = $result->fetch_assoc();
    if (!password_verify($senha, $user["senha_hash"])) {
        return ["status" => "erro", "mensagem" => "Senha incorreta."];
    }

    $stmt = $conn->prepare("
        SELECT a.id_aluno, a.nome, a.email, a.id_escola, a.dt_criacao, a.ra
        FROM tb_aluno a
        JOIN tb_aluno_responsavel ar ON a.id_aluno = ar.id_aluno
        WHERE ar.id_responsavel = ?
    ");
    $stmt->bind_param("i", $user["id_responsavel"]);
    $stmt->execute();
    $alunos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    return [
        "status" => "sucesso",
        "responsavel" => [
            "id" => $user["id_responsavel"],
            "nome" => $user["nome"],
            "email" => $user["email"],
            "alunos" => $alunos
        ]
    ];
}
?>
