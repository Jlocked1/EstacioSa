<?php
require_once 'includes/conexao.php';
require_once 'functions/usuarios.php';

echo "=== TESTE: buscar_usuario_por_id ===\n";
$usuario = buscar_usuario_id($mysqli, 1);
if ($usuario) {
    echo " Usuário encontrado: " . $usuario['nome'] . "\n";
    print_r($usuario);
} else {
    echo "Usuário não encontrado\n";
}

echo "\n=== TESTE: listar_todos_usuarios ===\n";
$usuarios = listar_todos_usuarios($mysqli);
echo "Total de usuários: " . count($usuarios) . "\n";
foreach ($usuarios as $u) {
    echo "- " . $u['nome'] . " (" . $u['email'] . ")\n";
}

echo "\n=== TESTE: email_existe ===\n";
if (email_existe($mysqli, 'carlos@email.com')) {
    echo "Email existe\n";
} else {
    echo "Email não existe\n";
}


echo "\n=== TESTE: criar_usuario ===\n";
$dados = [
    'nome' => 'Teste Usuario',
    'email' => 'teste' . time() . '@email.com',
    'senha' => '123456',
    'cadeira1' => 'Teste',
    'notacadeira1' => 10.0
];

$novo_id = criar_usuario($mysqli, $dados);

if ($novo_id) {
    echo " Usuário criado com ID: $novo_id\n";
    // Deletar usuário de teste
    if (deletar_usuario($mysqli, $novo_id)) {
        echo "Usuário de teste deletado\n";
    }
} else {
    echo "Erro ao criar usuário\n";
}


$mysqli->close();