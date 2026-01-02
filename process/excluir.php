<?php
session_start();
require_once '../includes/conexao.php';
require_once '../functions/auth.php';
require_once '../functions/helpers.php';
require_once '../functions/usuarios.php';

proteger_pagina('../auth.html', true);

$id = intval($_GET['id'] ?? 0);

if ($id <= 0) {
    definir_erro("ID inválido");
    redirecionar('../pages/admin.php');
}

if ($id === $_SESSION['user_id']) {
    definir_erro("Você não pode excluir sua própria conta!");
    redirecionar('../pages/admin.php');
}

$sucesso = deletar_usuario($mysqli, $id);

if ($sucesso) {
    definir_sucesso("Usuário excluído com sucesso!");
} else {
    definir_erro("Erro ao excluir usuário");
}

redirecionar('../pages/admin.php');