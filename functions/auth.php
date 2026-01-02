<?php
require_once __DIR__ . '/usuarios.php';

function fazer_login($mysqli, $email, $senha) {
    if(empty($email) || empty($senha)) {
        return false;
    }

    $usuario = buscar_usuario_email($mysqli, $email);

    if(!$usuario) {
        return false;
    }

    if(!password_verify($senha, $usuario['senha'])) {
        return false;
    }

    return $usuario;
}

function logged_in() {
    if(session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function is_adm() {
    if(session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    return isset($_SESSION['user_adm']) && $_SESSION['user_adm'] >= 1;
}

function proteger_pagina($redirect_url = '../teladelogin.php', $apenas_admin = false) {
    if(session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if(!logged_in()) {
        $_SESSION['erro'] = "Você precisa fazer o login para acessar essa página";
        header("Location: $redirect_url");
        exit();
    }

    if($apenas_admin && !is_adm()) {
        $_SESSION['erro'] = "Acesso negado! Apenas administradores";
        header("Location: ../pages/dashboard.php");
        exit();
    }
}

function fazer_logout() {
    if(session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $_SESSION = [];

    if(isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }

    session_destroy();
}