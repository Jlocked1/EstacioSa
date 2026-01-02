<?php
header('Content-Type: application/json');

require_once '../includes/conexao.php';
require_once '../functions/usuarios.php';

/* PRIORIDADE: busca por ID (usado no editar.php) */
if (isset($_GET['id'])) {

    $id = (int) $_GET['id'];
    $usuario = buscar_usuario_id($mysqli, $id);

}
/* FALLBACK: busca por email (fluxo antigo) */
elseif (isset($_GET['email'])) {

    $email = $_GET['email'];
    $usuario = buscar_usuario_email($mysqli, $email);

}
else {
    echo json_encode(['error' => 'Parâmetro não fornecido']);
    exit();
}

if ($usuario) {
    unset($usuario['senha']); // segurança
    echo json_encode($usuario);
} else {
    echo json_encode(['error' => 'Usuário não encontrado']);
}