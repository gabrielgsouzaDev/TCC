<?php
$data = json_decode(file_get_contents('php://input'), true);

if(isset($data['email'], $data['status'])){
    $to = $data['email'];
    $subject = "Atualização do seu pedido";
    $message = "Seu pedido agora está: " . $data['status'];
    $headers = "From: cantina@escola.com";

    mail($to, $subject, $message, $headers);
    echo json_encode(['success'=>true]);
} else {
    echo json_encode(['success'=>false,'error'=>'Dados incompletos']);
}
