<?php
session_start();
include("../banco/conexao.php");

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['password'] ?? '';

    // Valida email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensagem = "❌ E-mail inválido!";
    } else {
        // Consulta segura ao banco usando o nome correto das colunas
        $sql = "SELECT * FROM tb_admin WHERE email = :email LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin) {
            // Verifica a senha usando password_verify
            if (password_verify($senha, $admin['senha_hash'])) {
                // Login OK
                $_SESSION['admin_id'] = $admin['id_admin'];
                $_SESSION['admin_nome'] = $admin['nome'];
                header("Location: ../admin/painelAdmin.php");
                exit;
            } else {
                $mensagem = "❌ Senha incorreta!";
            }
        } else {
            $mensagem = "❌ Administrador não encontrado!";
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
  <title>CTNAPP - Admin</title>
</head>

<body>
<img src="../img/fundoAuth.svg" alt="Fundo do site" class="background-svg">
  <div class="container">
    <div class="link-back">
      <a href="../index.php">← Voltar para Início</a>
    </div>


    <h2>Login Admin</h2>
    <p class="sub-text">Acesso restrito a administradores previamente cadastrados.
    </p>
    <form id="formCadastro" action="" method="POST">
    <div class="form-group">
      <input type="email" placeholder="Digite seu e-mail" name="email" maxlength="50" required>
    </div>
      <?php

      ?>
    <div class="form-group senha">
      <input type="password" placeholder="Digite sua senha" id="senhaCadastro" name="password" required>
      <!--Olhinho de ver senha-->
      <span class="ver-senha" onclick="toggleSenha('senhaCadastro')">👁</span>
    </div>
            <button type="submit" class="botao">
            <span class="texto">LOGIN</span>
            </button>
                <div class="link">
      <a href="cadAdmin.php">Cadastro temporário</a>
    </div>
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