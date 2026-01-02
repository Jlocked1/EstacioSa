-- Banco de dados: db
-- Sistema de Gerenciamento Acadêmico EstácioSa

-- Criar banco de dados se não existir
CREATE DATABASE IF NOT EXISTS `db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `db`;

-- Estrutura da tabela `usuarios`
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL UNIQUE,
  `senha` varchar(255) NOT NULL,
  `cadeira1` varchar(50) DEFAULT NULL,
  `cadeira2` varchar(50) DEFAULT NULL,
  `cadeira3` varchar(50) DEFAULT NULL,
  `notacadeira1` decimal(4,2) DEFAULT 0.00,
  `notacadeira2` decimal(4,2) DEFAULT 0.00,
  `notacadeira3` decimal(4,2) DEFAULT 0.00,
  `faltas` int(10) DEFAULT 0,
  `suplente` varchar(100) DEFAULT NULL,
  `profcadeira1` varchar(100) DEFAULT NULL,
  `profcadeira2` varchar(100) DEFAULT NULL,
  `profcadeira3` varchar(100) DEFAULT NULL,
  `adm` int(2) DEFAULT 0,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dados de exemplo (usuário admin e usuário comum)
INSERT INTO `usuarios` (`nome`, `email`, `senha`, `cadeira1`, `cadeira2`, `cadeira3`, `notacadeira1`, `notacadeira2`, `notacadeira3`, `faltas`, `suplente`, `profcadeira1`, `profcadeira2`, `profcadeira3`, `adm`) VALUES
('Administrador', 'admin@estacio.br', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, NULL, 0.00, 0.00, 0.00, 0, NULL, NULL, NULL, NULL, 2),
('Carlos Eduardo Silva', 'carlos@aluno.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '1155-Banco de Dados', '1152-Algoritmos I', '1153-Estruturas de Dados', 8.50, 7.00, 9.00, 2, 'Seg/Qua/Sex 19h às 20h', 'Prof. João Silva', 'Prof. Maria Santos', 'Prof. Ana Costa', 0);

-- Nota: A senha padrão para os dois usuários é 'password'
-- Você pode fazer login com:
-- Admin: admin@estacio.br / password
-- Aluno: carlos@aluno.com / password
