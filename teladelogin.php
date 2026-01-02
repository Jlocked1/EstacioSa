<?php
session_start();
require_once 'functions/helpers.php';

$mensagens = obter_mensagens();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="images/logo.png" type="image/x-icon">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="auth.css">
  <title>Login - Estácio</title>
</head>

<body class="login-page">
  <div class="container">
    <div class="cartao-login">
      <h1>Bem-vindo</h1>

      <?php if (!empty($mensagens['erro'])): ?>
        <div class="alert alert-error">
          <?php echo limpar_html($mensagens['erro']); ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($mensagens['sucesso'])): ?>
        <div class="alert alert-success">
          <?php echo limpar_html($mensagens['sucesso']); ?>
        </div>
      <?php endif; ?>

      <form action="process/login.php" method="POST">
        <input type="email" placeholder="E-mail" name="email" required>
        <input type="password" placeholder="Senha" name="senha" required>
        <button type="submit" class="botao-entrar">Entrar</button>
      </form>
    </div>
  </div>
</body>
</html>
