<?php
session_start();
require_once '../includes/conexao.php';
require_once '../functions/auth.php';
require_once '../functions/helpers.php';
require_once '../functions/usuarios.php';

proteger_pagina('../auth.html');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirecionar('../pages/dashboard.php');
}

$id = intval($_POST['id'] ?? $_SESSION['user_id']);

if ($id !== $_SESSION['user_id'] && !is_adm()) {
    definir_erro("Você não tem permissão para atualizar este usuário");
    redirecionar('../pages/dashboard.php');
}

$dados = [];

if (!empty($_POST['nome'])) {
    $dados['nome'] = trim($_POST['nome']);
}

if (!empty($_POST['email'])) {
    if (!validar_email($_POST['email'])) {
        definir_erro("Email inválido");
        redirecionar($_SERVER['HTTP_REFERER'] ?? '../pages/dashboard.php');
    }
    $dados['email'] = trim($_POST['email']);
}

if (!empty($_POST['senha'])) {
    $dados['senha'] = $_POST['senha'];
}

if (isset($_POST['cadeira1'])) $dados['cadeira1'] = trim($_POST['cadeira1']);
if (isset($_POST['professor1'])) $dados['professor1'] = trim($_POST['professor1']);
if (isset($_POST['notacadeira1'])) $dados['notacadeira1'] = floatval($_POST['notacadeira1']);

if (isset($_POST['cadeira2'])) $dados['cadeira2'] = trim($_POST['cadeira2']);
if (isset($_POST['professor2'])) $dados['professor2'] = trim($_POST['professor2']);
if (isset($_POST['notacadeira2'])) $dados['notacadeira2'] = floatval($_POST['notacadeira2']);

if (isset($_POST['cadeira3'])) $dados['cadeira3'] = trim($_POST['cadeira3']);
if (isset($_POST['professor3'])) $dados['professor3'] = trim($_POST['professor3']);
if (isset($_POST['notacadeira3'])) $dados['notacadeira3'] = floatval($_POST['notacadeira3']);

if (isset($_POST['faltas'])) $dados['faltas'] = intval($_POST['faltas']);

if (is_adm() && isset($_POST['adm'])) {
    $dados['adm'] = intval($_POST['adm']);
}

$sucesso = atualizar_usuario($mysqli, $id, $dados);

if ($sucesso) {
    // Atualizar sessão se alterou próprio email
    if (isset($dados['email']) && $id === $_SESSION['user_id']) {
        $_SESSION['user_email'] = $dados['email'];
    }
    if (isset($dados['nome']) && $id === $_SESSION['user_id']) {
        $_SESSION['user_nome'] = $dados['nome'];
    }
    definir_sucesso("Dados atualizados com sucesso!");
} else {
    definir_erro("Erro ao atualizar dados");
}

if (is_adm() && $id !== $_SESSION['user_id']) {
    redirecionar('../pages/admin.php');
} else {
    redirecionar('../pages/dashboard.php');
}