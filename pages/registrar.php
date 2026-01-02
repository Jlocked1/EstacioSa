<?php
session_start();
require_once '../includes/conexao.php';
require_once '../functions/auth.php';
require_once '../functions/usuarios.php';
require_once '../functions/helpers.php';

proteger_pagina('../auth.html', true);

$usuario_logado = buscar_usuario_id($mysqli, $_SESSION['user_id']);
$usuarios = listar_todos_usuarios($mysqli);

$mensagens = obter_mensagens();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Página de cadastro</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="../registrar.css">
</head>

<body>

  <header class="cabecalho-principal">
    <div class="conteudo-cabecalho">

      <div class="secao-esquerda">
        <div class="texto-cabecalho">
          <span style="font-weight: bold; font-size: 1.5rem; color: #203448;">Estácio</span>
          <span style="font-size: 1rem; color: #203448; margin-left: 10px;">Painel Administrador</span>
        </div>
      </div>

      <div class="secao-direita">
        <div class="perfil-usuario" onclick="alternarMenuSuspenso()">
          <i class="bi bi-person-circle" style="font-size: 2rem; color: #203448;"></i>
          <span style="color: #203448; margin-left: 8px;">
            <?php echo limpar_html($usuario_logado['nome']); ?>
          </span>
          <i class="bi bi-chevron-down" style="color: #203448; margin-left: 5px;"></i>
        </div>

        <div id="menuSuspenso" style="display:none; position:absolute; top:60px; right:20px; background:white; border:1px solid #ccc; border-radius:8px; padding:10px;">
          <a href="admin.php" style="display:block; padding:10px; color:#203448; text-decoration:none;">Meu Dashboard</a>
          <a href="../process/logout.php" style="display:block; padding:10px; color:#203448; text-decoration:none;">Sair</a>
        </div>
      </div>

    </div>
  </header>

  <div class="modal-overlay">
    <div class="modal">

      <h2>Cadastro de Usuário</h2>

      <?php if (!empty($erro)): ?>
        <div class="modal-alert error">
          <?php foreach ($erro as $e): ?>
            <p><?php echo $e; ?></p>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($sucesso)): ?>
        <div class="modal-alert success">
          <p><?php echo $sucesso; ?></p>
        </div>
      <?php endif; ?>

      <form method="POST" action="../process/cadastro.php">

        <div class="modal-grid">
          <input type="email" name="email" placeholder="E-mail" required>
          <input type="password" name="senha" placeholder="Senha" required>

          <select name="adm" required>
            <option value="">Selecione o tipo</option>
            <option value="1">Usuário Normal</option>
            <option value="2">Administrador</option>
          </select>
        </div>

        <div class="modal-actions">
          <button type="submit">Cadastrar</button>
          <a href="admin.php" class="btn-secundario">Cancelar</a>
        </div>

      </form>

    </div>
  </div>

  <script>
    function alternarMenuSuspenso() {
      const menu = document.getElementById('menuSuspenso');
      menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
    }

    document.getElementById('email').addEventListener('blur', function() {
      const email = this.value.trim();
      if (!email) return;

      fetch(`../api/buscar_usuario.php?email=${encodeURIComponent(email)}`)
        .then(res => res.json())
        .then(data => {
          if (data.error) return;

          document.getElementById('nome').value = data.nome ?? '';
          document.getElementById('cadeira1').value = data.cadeira1 ?? '';
          document.getElementById('cadeira2').value = data.cadeira2 ?? '';
          document.getElementById('cadeira3').value = data.cadeira3 ?? '';
          document.getElementById('profcadeira1').value = data.profcadeira1 ?? '';
          document.getElementById('profcadeira2').value = data.profcadeira2 ?? '';
          document.getElementById('profcadeira3').value = data.profcadeira3 ?? '';
          document.getElementById('notacadeira1').value = data.notacadeira1 ?? '';
          document.getElementById('notacadeira2').value = data.notacadeira2 ?? '';
          document.getElementById('notacadeira3').value = data.notacadeira3 ?? '';
          document.getElementById('suplente').value = data.suplente ?? '';
          document.getElementById('adm').value = data.adm ?? '1';
        })
        .catch(() => {
          console.error('Erro ao buscar usuário');
        });
    });
  </script>

</body>

</html>