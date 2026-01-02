<?php
session_start();
require_once '../includes/conexao.php';
require_once '../functions/auth.php';
require_once '../functions/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirecionar('../teladelogin.php');
}

$dados = [
    'nome' => trim($_POST['nome'] ?? ''),
    'email' => trim($_POST['email'] ?? ''),
    'senha' => $_POST['senha'] ?? '',
    'adm' => 0, // Novos usuários não são admins

    // Cadeiras (opcionais)
    'cadeira1' => trim($_POST['cadeira1'] ?? ''),
    'professor1' => trim($_POST['professor1'] ?? ''),
    'notacadeira1' => floatval($_POST['notacadeira1'] ?? 0),

    'cadeira2' => trim($_POST['cadeira2'] ?? ''),
    'professor2' => trim($_POST['professor2'] ?? ''),
    'notacadeira2' => floatval($_POST['notacadeira2'] ?? 0),
    
    'cadeira3' => trim($_POST['cadeira3'] ?? ''),
    'professor3' => trim($_POST['professor3'] ?? ''),
    'notacadeira3' => floatval($_POST['notacadeira3'] ?? 0),
    
    'faltas' => intval($_POST['faltas'] ?? 0)
];

if (empty($dados['nome']) || empty($dados['email']) || empty($dados['senha'])) {
    definir_erro("Preencha todos os campos obrigatórios");
    redirecionar('../teladelogin.php');
}

if (!validar_email($dados['email'])) {
    definir_erro("Email inválido");
    redirecionar('../teladelogin.php');
}

if (email_existe($mysqli, $dados['email'])) {
    definir_erro("Este email já está cadastrado");
    redirecionar('../teladelogin.php');
}

$novo_id = criar_usuario($mysqli, $dados);

if ($novo_id) {
    definir_sucesso("Cadastro realizado com sucesso! Faça login");
    redirecionar('../teladelogin.php');
} else {
    definir_erro("Erro ao cadastrar usuário");
    redirecionar('../teladelogin.php');
}