<?php
session_start();
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
require '../PHPMailer/src/Exception.php';
include("../banco/conexao.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        function enviarCodigo($email, $Codigo){
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
    }

    if ($_POST['Cod'] == ($_SESSION['codigo_recuperacao'] ?? '')) {
        header("Location: alterarSenha.php");
        exit;
    }elseif(isset($_POST['reenviar'])){
    $novoCodigo = substr(md5(time()), 0, 4);
    $_SESSION['codigo_recuperacao'] = $novoCodigo;

        if (!empty($_SESSION['email_recuperacao'])) {
            enviarCodigo($_SESSION['email_recuperacao'], $novoCodigo);
            echo "<script>alert('Novo código enviado para o seu e-mail.');</script>";
            echo "<script>window.location.href='token.php';</script>";
        } else {
            $erro = "Não foi possível reenviar. Tente iniciar o processo de recuperação novamente.";
        }
    } else {
        echo "<script>alert('O Código Digitado Está Incorreto. Tente Novamente.');</script>";
        echo "<script>window.location.href='token.php';</script>";
    }
}


?>