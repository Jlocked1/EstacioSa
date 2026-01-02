<?php

function calcular_media($usuario) {
    $nota1 = floatval($usuario['notacadeira1'] ?? 0);
    $nota2 = floatval($usuario['notacadeira2'] ?? 0);
    $nota3 = floatval($usuario['notacadeira3'] ?? 0);

    return ($nota1 + $nota2 + $nota3) / 3;
}

function formatar_nota($nota) {
    return number_format($nota, 2, ',', '.');
}

function limpar_html($texto) {
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}

function validar_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function redirecionar($url) {
    header("Location: $url");
    exit();
}

function definir_sucesso($mensagem) {
    if(session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $_SESSION['sucesso'] = $mensagem;
}

function definir_erro($mensagem) {
    if(session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $_SESSION['erro'] = $mensagem;
}

function obter_mensagens() {
    if(session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $mensagens = [ 
        'sucesso' => $_SESSION['sucesso'] ?? '',
        'erro' => $_SESSION['erro'] ?? '' 
    ];
    
    unset($_SESSION['sucesso'], $_SESSION['erro']);

    return $mensagens;
}

