<!-- Tela Esqueci Senha -->

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="auth.css" />
    <title>Esqueci a Senha</title>
</head>

<body>
    <img src="../img/fundoAuth.svg" alt="Fundo do site" class="background-svg">
    <div class="container">
        <div class="link-back">
            <a href="login.php">← Voltar para login</a>
        </div>

        <h2>Recuperar Senha</h2>
        <p class="sub-text">
            Informe o e-mail cadastrado e enviaremos um código de verificação para redefinir sua senha.
        </p>

         <form action="token.php" method="post">
            <div class="form-group">
                <input type="email" name="email" placeholder="Digite seu e-mail" required>
            </div>
            <button class="botao" type="submit">
            <span class="texto">ENVIAR CÓDIGO</span>
            </button>
        </form>
        <div class="link">
            Lembrou a senha? <a href="login.php">Entrar</a>
        </div>
    </div>

</body>

</html>