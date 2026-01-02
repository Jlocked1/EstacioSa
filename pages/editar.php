<?php
session_start();
require_once '../includes/conexao.php';
require_once '../functions/auth.php';
require_once '../functions/helpers.php';
require_once '../functions/usuarios.php';

proteger_pagina('../auth.html');

if (!is_adm()) {
    definir_erro("Acesso restrito a administradores");
    redirecionar('../pages/dashboard.php');
}

$usuario_logado = buscar_usuario_id($mysqli, $_SESSION['user_id']);
$usuarios = listar_todos_usuarios($mysqli);
$mensagens = obter_mensagens();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Editar Usuário</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../editar.css">
</head>

<body>

<header class="cabecalho-principal">
    <div class="conteudo-cabecalho">

        <div class="secao-esquerda">
            <div class="texto-cabecalho">
                <span style="font-weight: bold; font-size: 1.5rem; color: #203448;">Estácio</span>
                <span style="font-size: 1rem; color: #203448; margin-left: 10px;">Editar Usuário</span>
            </div>
        </div>

        <div class="secao-direita">
            <div class="perfil-usuario">
                <i class="bi bi-person-circle" style="font-size: 2rem; color: #203448;"></i>
                <span style="margin-left: 8px;">
                    <?= limpar_html($usuario_logado['nome']); ?>
                </span>
            </div>
        </div>

    </div>
</header>

<div class="modal-overlay">
    <div class="modal">

        <h2>Alterar Dados do Usuário</h2>

        <?php if (!empty($mensagens['erro'])): ?>
            <div class="modal-alert error"><?= $mensagens['erro']; ?></div>
        <?php endif; ?>

        <?php if (!empty($mensagens['sucesso'])): ?>
            <div class="modal-alert success"><?= $mensagens['sucesso']; ?></div>
        <?php endif; ?>

        <!-- SELETOR DE USUÁRIO -->
        <div class="seletor-usuario">
            <select id="usuarioSelect">
                <option value="">Selecione um usuário</option>
                <?php foreach ($usuarios as $u): ?>
                    <option value="<?= $u['id']; ?>">
                        <?= limpar_html($u['nome']); ?> (<?= limpar_html($u['email']); ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <form id="formEditar" method="POST" action="../process/atualizar.php">

            <input type="hidden" name="id" id="usuarioId">

            <div class="modal-grid">
                <input type="text" name="nome" id="nome" placeholder="Nome">
                <input type="email" name="email" id="email" placeholder="E-mail">

                <input type="password" name="senha" placeholder="Nova senha (opcional)">

                <input type="text" name="cadeira1" id="cadeira1" placeholder="Cadeira 1">
                <input type="text" name="professor1" id="professor1" placeholder="Professor 1">
                <input type="number" step="0.01" name="notacadeira1" id="nota1" placeholder="Nota 1">

                <input type="text" name="cadeira2" id="cadeira2" placeholder="Cadeira 2">
                <input type="text" name="professor2" id="professor2" placeholder="Professor 2">
                <input type="number" step="0.01" name="notacadeira2" id="nota2" placeholder="Nota 2">

                <input type="text" name="cadeira3" id="cadeira3" placeholder="Cadeira 3">
                <input type="text" name="professor3" id="professor3" placeholder="Professor 3">
                <input type="number" step="0.01" name="notacadeira3" id="nota3" placeholder="Nota 3">

                <select name="adm">
                    <option value="0">Usuário Normal</option>
                    <option value="1">Administrador</option>
                </select>
            </div>

            <div class="modal-actions">
                <button type="submit">Salvar alterações</button>
                <a href="./admin.php" class="btn-secundario">Cancelar</a>
            </div>

        </form>

    </div>
</div>

<script>
    const select = document.getElementById('usuarioSelect');

    select.addEventListener('change', () => {
        const id = select.value;
        if (!id) return;

        fetch(`../api/buscar_usuario.php?id=${id}`)
            .then(res => res.json())
            .then(dados => {
                document.getElementById('usuarioId').value = dados.id;
                document.getElementById('nome').value = dados.nome ?? '';
                document.getElementById('email').value = dados.email ?? '';

                document.getElementById('cadeira1').value = dados.cadeira1 ?? '';
                document.getElementById('professor1').value = dados.professor1 ?? '';
                document.getElementById('nota1').value = dados.notacadeira1 ?? '';

                document.getElementById('cadeira2').value = dados.cadeira2 ?? '';
                document.getElementById('professor2').value = dados.professor2 ?? '';
                document.getElementById('nota2').value = dados.notacadeira2 ?? '';

                document.getElementById('cadeira3').value = dados.cadeira3 ?? '';
                document.getElementById('professor3').value = dados.professor3 ?? '';
                document.getElementById('nota3').value = dados.notacadeira3 ?? '';

                document.querySelector('select[name="adm"]').value = dados.adm ?? 0;
            })
            .catch(() => alert('Erro ao carregar usuário'));
    });
</script>

</body>
</html>
