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

// Cálculo da média
$media = calcular_media($usuario);
$media_float = number_format($media, 2, '.', '');
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../images/logo.png" type="image/x-icon">
    <title>Dashboard - <?php echo limpar_html($usuario['nome']); ?></title>

    <!-- Fontes e ícones -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@coreui/coreui-pro@5.5.0/dist/css/coreui.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- CSS personalizado -->
    <link rel="stylesheet" href="../dashboard.css?v=<?php echo time(); ?>">
</head>

<body class="dashboard barra-lateral-oculta">

    <!-- Barra lateral -->
    <div class="barra-lateral oculta">
        <button class="botao-alternar-menu" onclick="alternarBarraLateral()">
            <i class="bi bi-list"></i>
        </button>

        <ul class="menu">
            <li>
                <a href="#" onclick="mostrarSecao('home')">
                    <i class="bi bi-house-door"></i>
                    <span>Home</span>
                </a>
            </li>
            <li>
                <a href="#" onclick="mostrarSecao('calendario')">
                    <i class="bi bi-calendar"></i>
                    <span>Calendário</span>
                </a>
            </li>
            <li>
                <a href="https://minhabiblioteca.com.br/catalogo/" target="_blank">
                    <i class="bi bi-collection"></i>
                    <span>Biblioteca</span>
                </a>
            </li>
            <?php if (is_adm()): ?>
                <li>
                    <a href="admin.php">
                        <i class="bi bi-shield-lock"></i>
                        <span>Admin</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>

    <!-- Cabeçalho -->
    <header class="cabecalho-principal">
        <div class="conteudo-cabecalho">

            <div class="secao-esquerda">
                <div class="texto-cabecalho">
                    <span style="font-weight: bold; font-size: 1.5rem; color: #203448;">Estácio</span>
                    <span style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; font-weight: 400; font-size: 1rem; color: #203448; margin-left: 10px;">Painel Aluno</span>
                </div>
            </div>

            <div class="secao-direita">
                <div class="perfil-usuario" onclick="alternarMenuSuspenso()">
                    <i class="bi bi-person-circle" style="font-size: 2rem; color: #203448;"></i>
                    <span style="color: #203448; margin-left: 8px;"><?php echo limpar_html($usuario['nome']); ?></span>
                    <i class="bi bi-chevron-down" style="color: #203448; margin-left: 5px;"></i>
                </div>

                <div id="menuSuspenso" style="display: none; position: absolute; top: 60px; right: 20px; background: white; border: 1px solid #ccc; border-radius: 8px; padding: 10px;">
                    <a href="../process/logout.php" style="display: block; padding: 10px; color: #203448; text-decoration: none;">Sair</a>
                </div>
            </div>

        </div>
    </header>

    <!-- Home -->
    <h1>Bem vindo, <?php echo limpar_html($usuario['nome']); ?></h1>

    <div class="container-cartoes">
        <div class="cartao">
            <h2>Cursos Ativos</h2>
            <p class="numero-grande">3</p>
        </div>
        <div class="cartao">
            <h2>Média Geral</h2>
            <p class="numero-grande"><?php echo $media_float ?></p>
        </div>
        <div class="cartao">
            <h2>Faltas</h2>
            <p class="numero-grande"><?php echo $usuario['faltas']; ?></p>
        </div>
        <div class="cartao">
            <h2>Próxima Prova</h2>
            <p class="numero-grande">5 dias</p>
        </div>
    </div>

    <h2 id="titulo-disciplinas" style="text-align: center; margin-top: 50px; color: #203448;">Minhas Disciplinas</h2>

    <div class="container-disciplinas">
        <?php for ($i = 1; $i <= 3; $i++):
            $cadeira = $usuario["cadeira{$i}"];
            if (empty($cadeira)) continue;
        ?>
            <div class="cartao-disciplina">
                <h3><?php echo limpar_html($usuario["cadeira{$i}"]); ?></h3>
                <p><strong>Professor:</strong> <?php echo limpar_html($usuario["professor{$i}"]); ?></p>
                <p><strong>Horário:</strong> <?php echo limpar_html($usuario['suplente']); ?></p>
                <p class="nota">Nota: <?php echo $usuario["notacadeira{$i}"]; ?></p>
            </div>
        <?php endfor; ?>
    </div>

    <!-- Calendário -->
    <div id="secao-calendario" class="secao-conteudo" style="display: none;">
        <h2 style="text-align: center; margin-top: 50px; color: #203448;">Calendário Acadêmico</h2>
        <div style="display: flex; justify-content: center; margin-top: 30px; padding: 0 40px;">
            <div id="meuCalendario"></div>
        </div>
    </div>

    <!-- Rodapé -->
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

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@coreui/coreui-pro@5.5.0/dist/js/coreui.bundle.min.js"></script>
    <script>
        function alternarBarraLateral() {
            document.querySelector('.barra-lateral').classList.toggle('oculta');
            document.body.classList.toggle('barra-lateral-oculta');
        }

        function alternarMenuSuspenso() {
            const menuSuspenso = document.getElementById('menuSuspenso');
            menuSuspenso.style.display = (menuSuspenso.style.display == 'none') ? 'block' : 'none';
        }

        function mostrarSecao(secao) {
            const lista = document.querySelectorAll('.secao-conteudo');

            lista.forEach(function(elemento) {
                elemento.style.display = 'none';
            });

            if (secao === 'home') {
                document.querySelector('h1').style.display = 'block';
                document.querySelector('.container-cartoes').style.display = 'flex';
                document.getElementById('titulo-disciplinas').style.display = 'block';
                document.querySelector('.container-disciplinas').style.display = 'flex';
            } else {
                document.querySelector('h1').style.display = 'none';
                document.querySelector('.container-cartoes').style.display = 'none';
                document.getElementById('titulo-disciplinas').style.display = 'none';
                document.querySelector('.container-disciplinas').style.display = 'none';

                document.getElementById('secao-' + secao).style.display = 'block';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('meuCalendario');
            if (calendarEl) {
                const calendar = new coreui.Calendar(calendarEl, {
                    locale: 'pt-BR',
                    selectionType: 'day'
                });
            }
        });
    </script>

</body>

</html>