<?php

$nome = $_POST["nome"];
$email = $_POST["email"];
$telefone = $_POST["telefone"];
$senha = $_POST['senha'];

if($nome != NULL && $email != NULL && $telefone != NULL && $senha != NULL) {
    echo 'Dados Recebidos. Redirecionando...';
    sleep(1);
    header( 'Location: http://localhost/EstacioSa/auth.html' );
}


