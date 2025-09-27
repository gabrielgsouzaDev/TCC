<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

include("../banco/conexao.php");

// Só processa se o formulário foi enviado
if(isset($_POST['email'])){

    $email = trim($_POST['email']); // remove espaços

    // Verifica se o e-mail pertence a uma escola
    $sql = "SELECT email_contato FROM tb_escola WHERE email_contato = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':email' => $email]);
    $existe = $stmt->fetchColumn();

    if(!$existe){
        echo "<script>alert('Não encontramos nenhum usuário com este e-mail.');</script>";
        echo "<script>window.location.href='lembrarSenha.php';</script>";
        exit;
    }

    // Gera código de recuperação
    $Codigo = substr(md5(time()), 0, 4);
    $_SESSION['codigo_recuperacao'] = $Codigo;
    $_SESSION['email_recuperacao'] = $email;

    // Redireciona para a página de token
    echo "<script>window.location.href='token.php';</script>";

    // Envia e-mail com PHPMailer
    require '../PHPMailer/src/PHPMailer.php';
    require '../PHPMailer/src/SMTP.php';
    require '../PHPMailer/src/Exception.php';

    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'ctnapp01@gmail.com';
        $mail->Password   = 'welpzcjtsjvkwqyl';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('ctnapp01@gmail.com', 'CTNAPP');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = "Recuperação de Senha - CTNAPP";
        $mail->Body    = "Olá, seu código de verificação é: <b>{$Codigo}</b>";

        $mail->send();

    } catch (Exception $e) {
        echo "Erro ao enviar: {$mail->ErrorInfo}";
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="auth.css"/>
    <title>Esqueci a Senha</title>
</head>

<body>
    <img src="../img/fundoAuth.svg" alt="Fundo do site" class="background-svg">
    <div class="container">
        <div class="link-back">
            <a href="lembrarSenha.php">← Alterar e-mail</a>
        </div>
        <h2>Digite o código</h2>
        <p class="sub-text"> 
           Enviamos um código de verificação para seu e-mail cadastrado!
        </p>
    <form action="VerificaCod.php" method="post">
          <div class="otp-inputs">
                <input maxlength="1" type="text" class="otp-box" name="Cod">
                <input maxlength="1" type="text" class="otp-box" name="Cod">
                <input maxlength="1" type="text" class="otp-box" name="Cod">
                <input maxlength="1" type="text" class="otp-box" name="Cod">

            </div>
            <button class="botao" type="submit">
            <span class="texto">VERIFICAR</span>
            </button>
            <button class="reenviar" type="submit" name="reenviar">Reenviar código</button>
        </form>
        <div class="link"> Lembrou a senha?
            <a href="cadEscola.php">Entrar</a>
        </div>
    </div>
    <script>
const inputs = document.querySelectorAll('.otp-box');

inputs.forEach((input, index) => {
    // garante que o valor é visível e apenas um dígito
    input.type = 'text';
    input.maxLength = 1;

    input.addEventListener('input', () => {
        // se digitou algo, vai pro próximo input
        if(input.value.length === 1 && index < inputs.length - 1){
            inputs[index + 1].focus();
        }
    });

    input.addEventListener('keydown', (e) => {
        // backspace volta um input
        if(e.key === 'Backspace' && input.value === '' && index > 0){
            inputs[index - 1].focus();
        }
    });
});
</script>

</body>

</html>