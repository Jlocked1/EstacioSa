# EstacioSa

EstacioSa é um site institucional simples e um pequeno sistema acadêmico desenvolvido originalmente como projeto de período acadêmico e posteriormente aprimorado.
O projeto está funcional e serve como demonstração de integração entre frontend estático (landing page) e backend em PHP com persistência em banco relacional (MySQL).

Resumo

- Propósito: site institucional com landing page e sistema básico de gerenciamento de usuários/alunos para fins educacionais.
- Status: funcional (landing page, autenticação, CRUD básico de usuários e páginas administrativas).
- Público-alvo: demonstração acadêmica e ambiente de aprendizagem.

Tecnologias

- Front-end: HTML, CSS e JavaScript (vanilla).
- Back-end: PHP (scripts organizados em includes/, process/ e pages/).
- Banco de dados: MySQL/MariaDB (arquivo de esquema: database.sql).

Estrutura do repositório (arquivos principais)

- index.html, index.css — Landing page pública com formulário de contato (envio simulado via JS).
- teladelogin.php — Tela de login (usa functions/helpers.php para mensagens e validações).
- includes/conexao.php — Conexão com o banco de dados (ajustar credenciais conforme o ambiente).
- pages/ — Páginas protegidas (admin.php, dashboard.php, registrar.php, editar.php) usadas após autenticação.
- process/ — Scripts que executam ações no servidor (login.php, logout.php, cadastro.php, atualizar.php, excluir.php).
- database.sql — Script SQL para criar o banco `db` e a tabela `usuarios`, com dois usuários de exemplo (admin e aluno).
- images/ — Imagens e logos usados pela landing page.
- functions/ (e.g. functions/helpers.php) — Funções utilitárias usadas pelo projeto.

Instalação e execução

1. Requisitos: PHP (7.4+ recomendado), MySQL/MariaDB e um servidor web (Apache/Nginx) ou o servidor embutido do PHP.
2. Importar o banco de dados:
   mysql -u <usuario> -p < database.sql
3. Ajustar parâmetros de conexão em includes/conexao.php (host, usuário, senha, nome do banco).
4. Servir o diretório pelo servidor web ou via terminal:
   php -S localhost:8000
5. Acessar `http://localhost:8000` (ou a URL configurada) para ver a landing page e navegar até a tela de login.

Credenciais de teste (presentes em database.sql)

- Admin: admin@estacio.br / password
- Aluno: carlos@aluno.com / password

Observações de segurança e melhorias sugeridas

- As senhas de exemplo estão hasheadas no database.sql (bcrypt) — substituir por senhas seguras em produção.
- Mover credenciais sensíveis (DB) para variáveis de ambiente ou arquivo de configuração não versionado (.env).
- Implementar proteção contra CSRF, validação e sanitização adicionais no servidor e uso de HTTPS em produção.
- Melhorias UX: feedback de formulários no backend, paginação/listagens e controles de permissões mais refinados para usuários e administradores.

Licença

Projeto para fins educacionais e demonstração. Sinta-se à vontade para usar este código como base para estudos e melhorias.
