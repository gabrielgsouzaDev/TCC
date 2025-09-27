<!-- Tela Esqueci Senha -->
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
include("../banco/conexao.php");

if(isset($_POST['email'])){
    $sql = "SELECT nm_email_usuario FROM tb_usuario WHERE nm_email_usuario = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
       ':email' => $_POST['email']
    ]);
    $existe = $stmt->fetchColumn();
    if($existe < 1){
        echo "<script>alert('Não Encontramos Nenhum Usuario Com Este E-mail, Tente Digitar Novamente.');</script>";
    }else{
        $novasenha = substr(md5(time()), 0, 8);

require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
require '../PHPMailer/src/Exception.php';



$mail = new PHPMailer(true);

try {
    // Configuração do servidor SMTP
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'ctnapp01@gmail.com';        // seu e-mail
    $mail->Password   = 'welpzcjtsjvkwqyl';   // senha de aplicativo do Gmail
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Remetente
    $mail->setFrom('ctnapp01@gmail.com', 'CTNAPP');

    // Destinatário
    $mail->addAddress($_POST['email'], $_POST['email']);

    // Conteúdo do e-mail
    $mail->isHTML(true);
    $mail->Subject = "Recuperacao De Senha Do CTNAPP";
    $mail->Body    = 'Olá Sua Nova Senha Provisoria é: ' . $novasenha . ' Caso Quiser, Você Pode Trocar a Senha Na Personalização do Perfil.';

    $mail->send();

            $sql = "UPDATE tb_usuario SET cd_senha = '$novasenha' WHERE nm_email_usuario = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':email' => $_POST['email']
            ]);

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
    <link rel="stylesheet" href="auth.css" />
    <title>Esqueci a Senha</title>
</head>

<body>
    <img src="../img/fundoAuth.svg" alt="Fundo do site" class="background-svg">
    <div class="container">
        <div class="link-back">
            <a href="escola.php">← Voltar para login</a>
        </div>

        <h2>Recuperar Senha</h2>
        <p class="sub-text">
            Informe o e-mail cadastrado e enviaremos um código de verificação para redefinir sua senha.
        </p>

         <form action="" method="post">
            <div class="form-group">
                <input type="email" name="email" placeholder="Digite seu e-mail" required>
            </div>
            <button class="botao" type="submit">
            <span class="texto">ENVIAR NOVA SENHA</span>
            </button>
        </form>
        <div class="link">
            Lembrou a senha? <a href="escola.php">Entrar</a>
        </div>
    </div>

</body>

</html>