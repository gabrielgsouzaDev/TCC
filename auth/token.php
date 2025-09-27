<!-- Tela Esqueci Senha -->

<?php

session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

include("../banco/conexao.php");





if(isset($_POST['email'])){
    $email = $_POST['email'];
    $sql = "SELECT nm_email_usuario FROM tb_usuario WHERE nm_email_usuario = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
       ':email' => $_POST['email']
    ]);
    $existe = $stmt->fetchColumn();
    if($existe < 1){
        echo "<script>alert('Não Encontramos Nenhum Usuario Com Este E-mail, Tente Digitar Novamente.');</script>";
        echo "<script>window.location.href='lembrarSenha.php';</script>";
    }else{
        echo "<script>window.location.href='token.php';</script>";
        
$Codigo = substr(md5(time()), 0, 4);
$_SESSION['codigo_recuperacao'] = $Codigo;
$_SESSION['email_recuperacao'] = $email;

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
        $mail->Subject = "Recuperacao De Senha Do CTNAPP";
        $mail->Body    = 'Olá Seu Código De Verificação é: <br>' . $Codigo;
        $mail->send();


} catch (Exception $e) {
    echo "Erro ao enviar: {$mail->ErrorInfo}";
}

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
                <input maxlength="4" type="text" class="otp-box" name="Cod">
            </div>
            <button class="botao" type="submit">
            <span class="texto">VERIFICAR</span>
            </button>
            <button class="reenviar" type="submit" name="reenviar">Reenviar código</button>
        </form>
        <div class="link"> Lembrou a senha?
            <a href="login.html">Entrar</a>
        </div>
    </div>
</body>

</html>