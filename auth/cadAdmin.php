<?php
session_start();
include("../banco/conexao.php");

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['password'] ?? '';

    if (empty($nome) || empty($email) || empty($senha)) {
        $mensagem = "❌ Preencha todos os campos.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensagem = "❌ Email inválido.";
    } else {
        // Verifica se já existe admin com esse email
        $sql = "SELECT id_admin FROM tb_admin WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':email' => $email]);

        if ($stmt->rowCount() > 0) {
            $mensagem = "⚠️ Já existe um administrador com esse e-mail.";
        } else {
            // Cria hash da senha
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

            // Insere no banco
            $sqlInsert = "INSERT INTO tb_admin (nome, email, senha_hash) 
                          VALUES (:nome, :email, :senha)";
            $stmtInsert = $pdo->prepare($sqlInsert);
            $executou = $stmtInsert->execute([
                ':nome' => $nome,
                ':email' => $email,
                ':senha' => $senhaHash
            ]);

            if ($executou) {
                $mensagem = "✅ Administrador cadastrado com sucesso!";
            } else {
                $mensagem = "❌ Erro ao cadastrar administrador.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="auth.css" />
  <title>Cadastro de Administrador</title>
</head>
<body>
<img src="../img/fundoAuth.svg" alt="Fundo do site" class="background-svg">
<div class="container">
  <div class="link-back">
    <a href="logAdmin.php">← Voltar para Login</a>
  </div>

  <h2>Cadastrar Admin</h2>
  <p class="sub-text">Preencha os dados para cadastrar um novo administrador.</p>

  <?php if (!empty($mensagem)): ?>
    <div class="mensagem">
      <p><?= $mensagem ?></p>
    </div>
  <?php endif; ?>

  <form method="POST" action="">
    <div class="form-group">
      <input type="text" name="nome" placeholder="Nome completo" required maxlength="100">
    </div>

    <div class="form-group">
      <input type="email" name="email" placeholder="E-mail do administrador" required maxlength="100">
    </div>

    <div class="form-group senha">
      <input type="password" name="password" id="senhaCadastro" placeholder="Crie uma senha" required>
      <span class="ver-senha" onclick="toggleSenha('senhaCadastro')">👁</span>
    </div>

    <button type="submit" class="botao">
      <span class="texto">CADASTRAR</span>
    </button>
  </form>
</div>

<script>
  function toggleSenha(id) {
    const input = document.getElementById(id);
    input.type = input.type === "password" ? "text" : "password";
  }
</script>
</body>
</html>
