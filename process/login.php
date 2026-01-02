<?php
session_start();

require_once '../includes/conexao.php';
require_once '../functions/auth.php';
require_once '../functions/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirecionar('../teladelogin.php');
}

$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';

if (empty($email) || empty($senha)) {
    definir_erro("Preencha todos os campos");
    redirecionar('../teladelogin.php');
}

$usuario = fazer_login($mysqli, $email, $senha);

if (!$usuario) {
    definir_erro("Email ou senha incorretos");
    redirecionar('../teladelogin.php');
}

session_regenerate_id(true);

$_SESSION['user_id']   = $usuario['id'];
$_SESSION['user_nome'] = $usuario['nome'];
$_SESSION['user_email'] = $usuario['email'];
$_SESSION['user_adm']  = $usuario['adm'];

if ($usuario['adm'] >= 1) {
    redirecionar('../pages/admin.php');
} else {
    redirecionar('../pages/dashboard.php');
}