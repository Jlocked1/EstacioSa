<?php
session_start();
require_once '../includes/conexao.php';
require_once '../functions/auth.php';
require_once '../functions/helpers.php';
require_once '../functions/usuarios.php';

proteger_pagina('../auth.html');

// Buscar dados do usuário
$usuario = buscar_usuario_id($mysqli, $_SESSION['user_id']);

if (!$usuario) {
    definir_erro("Erro ao carregar dados do usuário");
    fazer_logout();
    redirecionar('../auth.html');
}

$media = calcular_media($usuario);

// Obter mensagens
$mensagens = obter_mensagens();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?php echo limpar_html($usuario['nome']); ?></title>
    <link rel="stylesheet" href="../dashboard.css">
</head>

<body class="dashboard-page">

<header>
    <h1>EstácioSa - Dashboard</h1>
    <nav>
        <span>Olá, <strong><?php echo limpar_html($usuario['nome']); ?></strong></span>

        <?php if (is_adm()): ?>
            <a href="admin.php">Painel Admin</a>
        <?php endif; ?>

        <a href="../processa/logout.php">Sair</a>
    </nav>
</header>

<main class="container">

    <!-- Mensagens -->
    <?php if (!empty($mensagens['sucesso'])): ?>
        <div class="alert alert-success">
            <?php echo limpar_html($mensagens['sucesso']); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($mensagens['erro'])): ?>
        <div class="alert alert-error">
            <?php echo limpar_html($mensagens['erro']); ?>
        </div>
    <?php endif; ?>

    <!-- Resumo -->
    <section class="resumo">
        <div class="card">
            <h3>Média Geral</h3>
            <p class="nota-grande"><?php echo formatar_nota($media); ?></p>
        </div>

        <div class="card">
            <h3>Faltas</h3>
            <p class="nota-grande"><?php echo $usuario['faltas']; ?></p>
        </div>
    </section>

    <!-- Disciplinas -->
    <section class="disciplinas">
        <h2>Minhas Disciplinas</h2>

        <?php if (!empty($usuario['cadeira1'])): ?>
            <div class="card-disciplina">
                <h3><?php echo limpar_html($usuario['cadeira1']); ?></h3>
                <p><strong>Professor:</strong> <?php echo limpar_html($usuario['professor1']); ?></p>
                <p><strong>Nota:</strong> <?php echo formatar_nota($usuario['notacadeira1']); ?></p>
            </div>
        <?php endif; ?>

        <?php if (!empty($usuario['cadeira2'])): ?>
            <div class="card-disciplina">
                <h3><?php echo limpar_html($usuario['cadeira2']); ?></h3>
                <p><strong>Professor:</strong> <?php echo limpar_html($usuario['professor2']); ?></p>
                <p><strong>Nota:</strong> <?php echo formatar_nota($usuario['notacadeira2']); ?></p>
            </div>
        <?php endif; ?>

        <?php if (!empty($usuario['cadeira3'])): ?>
            <div class="card-disciplina">
                <h3><?php echo limpar_html($usuario['cadeira3']); ?></h3>
                <p><strong>Professor:</strong> <?php echo limpar_html($usuario['professor3']); ?></p>
                <p><strong>Nota:</strong> <?php echo formatar_nota($usuario['notacadeira3']); ?></p>
            </div>
        <?php endif; ?>

    </section>

</main>

</body>
</html>