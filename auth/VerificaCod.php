<?php
session_start();
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
require '../PHPMailer/src/Exception.php';
include("../banco/conexao.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
    $mail->Subject = "Recuperação de Senha - CTNAPP";
    $mail->Body    = "Olá, seu código de verificação é: <b>{$Codigo}</b>";
    $mail->send();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Verifica se sessão de email existe e pertence a uma escola
    if (empty($_SESSION['email_recuperacao'])) {
        echo "<script>alert('Erro: Inicie o processo de recuperação novamente.');</script>";
        echo "<script>window.location.href='lembrarSenha.php';</script>";
        exit;
    }

    $email = $_SESSION['email_recuperacao'];

    // Confere se o email realmente existe na tabela tb_escola
    $stmt = $pdo->prepare("SELECT email_contato FROM tb_escola WHERE email_contato = :email");
    $stmt->execute([':email' => $email]);
    if (!$stmt->fetchColumn()) {
        echo "<script>alert('Erro: E-mail inválido.');</script>";
        echo "<script>window.location.href='lembrarSenha.php';</script>";
        exit;
    }

    // Validação do código
    if (!empty($_POST['Cod']) && $_POST['Cod'] === ($_SESSION['codigo_recuperacao'] ?? '')) {
        header("Location: alterarSenha.php");
        exit;
    } elseif (isset($_POST['reenviar'])) {
        $novoCodigo = substr(md5(time()), 0, 4);
        $_SESSION['codigo_recuperacao'] = $novoCodigo;
        enviarCodigo($email, $novoCodigo);
        echo "<script>alert('Novo código enviado para o seu e-mail.');</script>";
        echo "<script>window.location.href='token.php';</script>";
        exit;
    } else {
        echo "<script>alert('O código digitado está incorreto. Tente novamente.');</script>";
        echo "<script>window.location.href='token.php';</script>";
        exit;
    }
}
?>
