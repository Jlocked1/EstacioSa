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
  <link rel="icon" href="../images/logo.png" type="image/x-icon">
  <title>Dashboard</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="../admin.css">
</head>

<body class="dashboard-admin">

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
          <span style="color: #203448; margin-left: 8px;"><?php echo limpar_html($usuario_logado['nome']); ?></span>
          <i class="bi bi-chevron-down" style="color: #203448; margin-left: 5px;"></i>
        </div>

        <div id="menuSuspenso" style="display: none; position: absolute; top: 60px; right: 20px; background: white; border: 1px solid #ccc; border-radius: 8px; padding: 10px;">
          <a href="../process/logout.php" style="display: block; padding: 10px; color: #203448; text-decoration: none;">Sair</a>
        </div>

      </div>

    </div>
  </header>

  <?php if (!empty($mensagens['sucesso'])): ?>
    <div style="max-width: 1200px; margin: 20px auto; padding: 15px; background-color: #d4edda; color: #155724; border-radius: 8px; text-align: center;">
      <?php echo limpar_html($mensagens['sucesso']); ?>
    </div>
  <?php endif; ?>

  <?php if (!empty($mensagens['erro'])): ?>
    <div style="max-width: 1200px; margin: 20px auto; padding: 15px; background-color: #f8d7da; color: #721c24; border-radius: 8px; text-align: center;">
      <?php echo limpar_html($mensagens['erro']); ?>
    </div>
  <?php endif; ?>

  <h1>Bem vindo, <?php echo limpar_html($usuario_logado['nome']); ?></h1>

  <div class="container-cartoes">
    <div class="cartao">
      <h2>Cadastrar Aluno.</h2>
      <a href="registrar.php" class="btn-cadastro">Cadastrar</a>
    </div>
    <div class="cartao">
      <h2>Alterar Cadastro.</h2>
      <a href="editar.php" class="btn-cadastro">Alterar</a>
    </div>
  </div>

  <h2 style="text-align: center; margin-top: 50px; color: #203448;">Gerenciar Usuários</h2>

  <div class="container-tabela">
    <div class="tabela-usuarios">
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Tipo</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($usuarios as $u): ?>
            <tr>
              <td><?php echo $u['id']; ?></td>
              <td><?php echo limpar_html($u['nome']); ?></td>
              <td><?php echo limpar_html($u['email']); ?></td>
              <td>
                <?php if ($u['adm'] >= 1): ?>
                  <span class="icone-admin">Administrador</span>
                <?php else: ?>
                  <span class="icone-usuario">Usuário</span>
                <?php endif; ?>
              </td>
              <td>
                <?php if ($u['id'] !== $_SESSION['user_id']): ?>
                  <button class="btn-excluir" onclick="confirmarExclusao(<?php echo $u['id']; ?>, '<?php echo limpar_html($u['nome']); ?>')">
                    Excluir
                  </button>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <footer class="rodape">
    <div class="conteudo-rodape">
      <div class="rodape-esquerda">
        <p><strong>Desenvolvido por:</strong></p>
        <p>Carlos Chagas</p>
        <p>Jean Luca</p>
      </div>

      <div class="rodape-direita">
        <p>© 2025 Estácio. Todos os direitos reservados.</p>
      </div>
    </div>
  </footer>

  <script>
    function alternarMenuSuspenso() {
      const menuSuspenso = document.getElementById('menuSuspenso');
      if (menuSuspenso.style.display == 'none') {
        menuSuspenso.style.display = 'block';
      } else {
        menuSuspenso.style.display = 'none';
      }
    }

    function confirmarExclusao(id, nome) {
      if (confirm('Tem certeza que deseja excluir o usuário "' + nome + '"?\n\nEsta ação não pode ser desfeita!')) {
        window.location.href = '../process/excluir.php?id=' + id;
      }
    }
  </script>

</body>

</html>