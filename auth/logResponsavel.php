<!--Tela de Cadastro-->
<?php
include("../banco/conexao.php");

  if(isset($_POST['email'])){
    if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
      $dominio = substr(strrchr($_POST['email'], "@"), 1);

      if(checkdnsrr($dominio, "MX")){

    $sql = "SELECT * FROM tb_usuario WHERE nm_email_usuario = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':email' => $_POST['email'],
    ]);
    $existe = $stmt->fetchColumn();
    if ($existe > 0) {
      echo "<script>alert('Este e-mail já está cadastrado!');</script>";
    }elseif(isset($_POST['password']) && isset($_POST['Confpassword'])){
      if($_POST['password'] != $_POST['Confpassword']){
        echo "<script>alert('As Senhas Não São Iguais, Tente Novamente');</script>";
      }else{
        if(isset($_POST['CodCantina'])){
          $sql = "SELECT * FROM tb_cantina WHERE cd_cantina = :codigo";
          $stmt = $pdo->prepare($sql);
          $stmt->execute([
            ':codigo' => $_POST['CodCantina'],
          ]);
          $existe = $stmt->fetchColumn();
          if($existe < 1){
            echo "<script>alert('Este Codigo De Cantina Não Existe, Tente Novamente!');</script>";
          }else{
            $sql = "INSERT INTO tb_usuario(nm_usuario, nm_email_usuario, cd_senha, cd_cantina) values (:nome, :email, :senha, :cod)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
              ':nome' => $_POST['name'],
              ':email' => $_POST['email'],
              ':senha' => $_POST['password'],
              ':cod' => $_POST['CodCantina'],
            ]);
            echo "<script>alert('Usuario Cadastrado Com Sucesso'); window.location.href='../cantina/painelCantineiro.php';</script>";
          }
        }
      }
    }

  } else {
      echo "<script>alert('E-mail inválido!');</script>";
  }
} else {
    echo "<script>alert('Domínio não encontrado!');</script>";
}



  }
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="auth.css" />
  <title>CTNAPP - Responsável</title>
</head>

<body>
<img src="../img/fundoAuth.svg" alt="Fundo do site" class="background-svg">
  <div class="container">
    <div class="link-back">
      <a href="../index.php">← Voltar para Início</a>
    </div>


    <h2>Login Responsável</h2>
    <p class="sub-text">login e cadastro
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
    <div class="form-group">
      <input type="number" placeholder="Digite o ID da Cantina " name="CodCantina" min="1" max="10" required>
    </div>
            <button type="submit" class="botao">
            <span class="texto">LOGIN</span>
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