<?php
    include("../banco/conexao.php");
    session_start();
    if(isset($_POST['password']) && isset($_POST['Confpassword'])){
        $senha = $_POST['password'];
        $confSenha = $_POST['Confpassword'];
        if($senha == $confSenha){
            $sql = "UPDATE tb_usuario SET cd_senha = :senha WHERE nm_email_usuario = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':email' => $_SESSION['email_recuperacao'],
                ':senha' => $senha
            ]);
            echo "<script>alert('Senha Alterada Com Sucesso!');</script>";
            echo "<script>window.location.href='login.php';</script>";
            session_unset();
        }else{
          echo "<script>alert('As Senhas São Diferentes Tente Digitar Novamente!');</script>";
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Senha</title>
</head>
<body>
    <form action="alterarSenha.php" method="post">
        <input type="password" placeholder="Digite sua senha" id="senhaCadastro" name="password" required>
          <!--Olhinho de ver senha-->
          <span class="ver-senha" onclick="toggleSenha('senhaCadastro')">👁</span>
        </div>
           <div class="form-group senha">
          <input type="password" placeholder="Confirme sua senha" id="CsenhaCadastro" name="Confpassword" required>
          <!--Olhinho de ver senha-->
          <span class="ver-senha" onclick="toggleSenha('CsenhaCadastro')">👁</span>
        </div>
        <button type="submit"></button>
    </form>
    <script>
    function toggleSenha(id) {
      const input = document.getElementById(id);
      input.type = input.type === "password" ? "text" : "password";
    }
  </script>
</body>
</html>