-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql201.infinityfree.com
-- Tempo de geração: 15/04/2026 às 18:10
-- Versão do servidor: 11.4.10-MariaDB
-- Versão do PHP: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `if0_39007414_bdcontroleestoque`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `itens_venda`
--

CREATE TABLE `itens_venda` (
  `id` int(11) NOT NULL,
  `id_venda` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `nome_produto` varchar(100) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `valor_unitario` decimal(10,2) NOT NULL,
  `trocado` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `itens_venda`
--

INSERT INTO `itens_venda` (`id`, `id_venda`, `id_produto`, `nome_produto`, `quantidade`, `valor_unitario`, `trocado`) VALUES
(33, 89, 11, 'Bateria CR2032', 2, '10.00', 0),
(32, 88, 5, 'pilha AAA comum', 2, '5.00', 0),
(31, 88, 11, 'Bateria CR2032', 3, '10.00', 0),
(30, 88, 14, 'Mouse c/ Fio', 1, '15.00', 0),
(29, 87, 15, 'Bateria CR2016', 1, '10.00', 0),
(28, 86, 8, 'Telefone', 1, '50.00', 0),
(27, 86, 13, 'Mouse s/ Fio', 1, '30.00', 0),
(26, 85, 15, 'Bateria CR2016', 30, '10.00', 0),
(25, 84, 15, 'Bateria CR2016', 30, '10.00', 0),
(24, 83, 15, 'Bateria CR2016', 30, '10.00', 0),
(23, 82, 15, 'Bateria CR2016', 25, '10.00', 0),
(22, 81, 11, 'Bateria CR2032', 1, '10.00', 0),
(38, 93, 23, 'Fone bluetooth', 1, '50.00', 0),
(35, 91, 11, 'Bateria CR2032', 2, '10.00', 0),
(36, 91, 14, 'Mouse c/ Fio', 1, '15.00', 0),
(39, 94, 23, 'Fone bluetooth', 1, '50.00', 0),
(40, 95, 12, 'Bateria CR2025', 2, '10.00', 0),
(41, 96, 23, 'Fone bluetooth', 1, '50.00', 0),
(42, 97, 11, 'Bateria CR2032', 2, '10.00', 0),
(43, 98, 23, 'Fone bluetooth', 1, '50.00', 0),
(44, 99, 12, 'Bateria CR2025', 2, '10.00', 0),
(45, 100, 20, 'Pen drive 32GB', 1, '45.00', 0),
(52, 106, 23, 'Fone bluetooth', 1, '50.00', 0),
(47, 102, 12, 'Bateria CR2025', 3, '10.00', 0),
(48, 102, 17, 'Carregador tipo C', 1, '35.00', 0),
(49, 103, 20, 'Pen drive 32GB', 1, '45.00', 0),
(50, 104, 21, 'MemÃ³ria Micro SD 32GB', 1, '30.00', 0),
(53, 106, 14, 'Mouse c/ Fio', 1, '15.00', 0),
(54, 107, 11, 'Bateria CR2032', 3, '10.00', 1),
(55, 108, 11, 'Bateria CR2032', 3, '10.00', 0),
(56, 109, 12, 'Bateria CR2025', 7, '10.00', 0),
(57, 109, 5, 'pilha AAA comum', 3, '5.00', 0),
(58, 110, 11, 'Bateria CR2032', 3, '10.00', 0),
(59, 111, 23, 'Fone bluetooth', 1, '50.00', 0),
(60, 112, 19, 'Conversor TV digital', 1, '140.00', 0),
(64, 116, 15, 'Bateria CR2016', 2, '10.00', 0),
(63, 115, 11, 'Bateria CR2032', 3, '10.00', 0),
(65, 117, 11, 'Bateria CR2032', 3, '10.00', 0),
(66, 118, 1, 'Teclado Gamer', 1, '100.00', 0),
(67, 118, 11, 'Bateria CR2032', 1, '10.00', 0),
(68, 119, 11, 'Bateria CR2032', 2, '10.00', 0),
(69, 120, 11, 'Bateria CR2032', 2, '10.00', 0),
(70, 120, 15, 'Bateria CR2016', 2, '10.00', 0),
(71, 121, 12, 'Bateria CR2025', 3, '10.00', 0),
(72, 122, 15, 'Bateria CR2016', 2, '10.00', 0),
(73, 123, 11, 'Bateria CR2032', 2, '10.00', 0),
(74, 123, 12, 'Bateria CR2025', 2, '10.00', 0),
(75, 124, 11, 'Bateria CR2032', 2, '10.00', 0),
(76, 125, 23, 'Fone bluetooth', 1, '50.00', 0),
(77, 126, 24, 'Pilha Recarregavel AA com 2', 1, '25.00', 0),
(78, 127, 15, 'Bateria CR2016', 2, '10.00', 0),
(79, 128, 13, 'Mouse s/ Fio', 1, '30.00', 0),
(80, 128, 8, 'Telefone', 1, '50.00', 0),
(81, 129, 13, 'Mouse s/ Fio', 3, '30.00', 0),
(82, 129, 12, 'Bateria CR2025', 10, '10.00', 0),
(83, 129, 20, 'Pen drive 32GB', 2, '45.00', 0),
(84, 130, 18, 'Carregador Iphone', 5, '35.00', 0),
(85, 130, 13, 'Mouse s/ Fio', 5, '30.00', 0),
(86, 130, 8, 'Telefone', 2, '50.00', 0),
(87, 130, 11, 'Bateria CR2032', 20, '10.00', 0),
(88, 131, 26, 'Bateria 9V Zinco', 5, '10.00', 0),
(89, 131, 5, 'pilha AAA comum', 5, '5.00', 0),
(90, 132, 27, 'Suporte Automotivo Para Celular C/ Ventosa', 3, '25.00', 0),
(91, 132, 1, 'Teclado Gamer', 3, '100.00', 0),
(92, 133, 22, 'Controle LG smart 7027', 10, '35.00', 0),
(93, 133, 19, 'Conversor TV digital', 10, '140.00', 0),
(94, 134, 13, 'Mouse s/ Fio', 20, '30.00', 0),
(95, 135, 17, 'Carregador tipo C', 21, '35.00', 0),
(96, 135, 24, 'Pilha Recarregavel AA com 2', 15, '25.00', 0),
(97, 136, 5, 'pilha AAA comum', 18, '5.00', 0),
(98, 136, 16, 'Carregador micro USB', 14, '30.00', 0),
(99, 137, 5, 'pilha AAA comum', 5, '5.00', 0),
(100, 137, 13, 'Mouse s/ Fio', 20, '30.00', 0),
(101, 138, 23, 'Fone bluetooth', 18, '50.00', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `id_produto` int(11) NOT NULL,
  `nome_produto` varchar(100) NOT NULL,
  `qtde_estoque` int(11) NOT NULL,
  `valor` decimal(10,2) NOT NULL DEFAULT 0.00,
  `valor_unitario` decimal(10,2) NOT NULL DEFAULT 0.00,
  `codigo_barras` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id_produto`, `nome_produto`, `qtde_estoque`, `valor`, `valor_unitario`, `codigo_barras`) VALUES
(1, 'Teclado Gamer', 48, '0.00', '100.00', '1643028527587'),
(5, 'pilha AAA comum', 3, '0.00', '5.00', '4907992234255'),
(8, 'Telefone', 28, '0.00', '50.00', '4410249036272'),
(11, 'Bateria CR2032', 150, '0.00', '10.00', '7897768202417'),
(12, 'Bateria CR2025', 115, '0.00', '10.00', '7908417102702'),
(13, 'Mouse s/ Fio', 5, '0.00', '30.00', '3422683416680'),
(14, 'Mouse c/ Fio', 50, '0.00', '15.00', '5179198824480'),
(15, 'Bateria CR2016', 128, '0.00', '10.00', '9986586342618'),
(16, 'Carregador micro USB', 1, '0.00', '30.00', '1003706322219'),
(17, 'Carregador tipo C', 29, '0.00', '35.00', '5484985308832'),
(18, 'Carregador Iphone', 25, '0.00', '35.00', '2164567594316'),
(19, 'Conversor TV digital', 30, '0.00', '140.00', '0329934428743'),
(20, 'Pen drive 32GB', 2, '0.00', '45.00', '4306896754097'),
(21, 'MemÃ³ria Micro SD 32GB', 35, '0.00', '45.00', '4654426684066'),
(22, 'Controle LG smart 7027', 40, '0.00', '35.00', '5563416854613'),
(23, 'Fone bluetooth', 2, '0.00', '50.00', '4258261828133'),
(24, 'Pilha Recarregavel AA com 2', 84, '0.00', '25.00', '5846808850503'),
(25, 'Pilha Recarregavel AAA com 2', 100, '0.00', '20.00', '2728521464754'),
(26, 'Bateria 9V Zinco', 25, '0.00', '10.00', '3195682959077'),
(27, 'Suporte Automotivo Para Celular C/ Ventosa', 7, '0.00', '25.00', '1863142854766'),
(28, 'Carregador para Celular', 20, '0.00', '30.00', '8043210862558');

-- --------------------------------------------------------

--
-- Estrutura para tabela `projeto_previsao_estoque_usuarios`
--

CREATE TABLE `projeto_previsao_estoque_usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `senha` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `projeto_previsao_estoque_usuarios`
--

INSERT INTO `projeto_previsao_estoque_usuarios` (`id`, `usuario`, `senha`) VALUES
(1, 'admin', '$2y$10$Q4Fy5o.H30nn7S8xdY/lCelchnqoPmlnM2G2bJzTAfC83d5pkP69y'),
(3, 'piunivesp2', '$2y$10$Q8dTMN708ONqRg8N3XpAwepaIjMr/bCI6cI1qqaa7jQprbvg0fGMG'),
(4, 'piunivesp', '$2y$10$noSWQvtwItvZ8vKHJAsVIeU3cx6mb9dj4GnovQ0pDaFQr.iqKl1uq');

-- --------------------------------------------------------

--
-- Estrutura para tabela `relatorio_financeiro`
--

CREATE TABLE `relatorio_financeiro` (
  `id` int(11) NOT NULL,
  `data_operacao` datetime DEFAULT NULL,
  `tipo_operacao` varchar(20) DEFAULT NULL,
  `forma_pagamento` varchar(50) DEFAULT NULL,
  `nome_produto` varchar(255) DEFAULT NULL,
  `quantidade` int(11) DEFAULT NULL,
  `valor_unitario` decimal(10,2) DEFAULT NULL,
  `desconto` decimal(10,2) DEFAULT NULL,
  `taxa_aplicada` decimal(5,2) DEFAULT NULL,
  `valor_liquido` decimal(10,2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `relatorio_reposicao`
--

CREATE TABLE `relatorio_reposicao` (
  `id_produto` int(11) NOT NULL,
  `qtde_vend_3meses` int(11) DEFAULT NULL,
  `qtde_estoque` int(11) DEFAULT NULL,
  `qtde_arepor` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `senha` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `senha`) VALUES
(1, 'admin', '$2y$10$EM8OfiM0FAf8WC8qUPjwW.0tc21qSYZeM8B0GuRLpWkkfYHKd.5My'),
(2, 'piunivesp2', '$2y$10$pyJc.ylP.LZClFvbi9Wog.Uxk/YXBs2h13cid79vcAwW1/B42.6OC'),
(10, 'piunivesp3', '$2y$10$44BaWeVBenOnXDL9OV8gFOowkn1P.K2XQVa.mW5uSUmTYioGCYx8y');

-- --------------------------------------------------------

--
-- Estrutura para tabela `vendas`
--

CREATE TABLE `vendas` (
  `id_venda` int(11) NOT NULL,
  `data_venda` datetime NOT NULL,
  `forma_pagamento` varchar(50) DEFAULT NULL,
  `total_venda` decimal(10,2) NOT NULL DEFAULT 0.00,
  `desconto` decimal(10,2) DEFAULT 0.00,
  `desconto_total` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `vendas`
--

INSERT INTO `vendas` (`id_venda`, `data_venda`, `forma_pagamento`, `total_venda`, `desconto`, `desconto_total`) VALUES
(81, '2025-07-22 21:48:00', 'CartÃ£o de CrÃ©dito', '10.00', '0.00', '0.00'),
(82, '2025-07-23 17:04:00', 'Pix', '250.00', '0.00', '0.00'),
(83, '2025-07-23 17:05:00', 'CartÃ£o de CrÃ©dito', '300.00', '0.00', '0.00'),
(84, '2025-07-23 17:14:00', 'CartÃ£o de DÃ©bito', '300.00', '0.00', '0.00'),
(85, '2025-07-23 17:45:00', 'CartÃ£o de CrÃ©dito', '300.00', '0.00', '0.00'),
(86, '2025-07-24 17:10:00', 'CartÃ£o de DÃ©bito', '80.00', '0.00', '0.00'),
(87, '2025-07-24 17:34:00', 'CartÃ£o de DÃ©bito', '10.00', '1.00', '0.00'),
(88, '2025-07-24 18:13:00', 'CartÃ£o de DÃ©bito', '55.00', '2.00', '0.00'),
(89, '2025-07-24 20:06:00', 'CartÃ£o de CrÃ©dito', '20.00', '1.00', '0.00'),
(91, '2025-07-26 10:06:00', 'Dinheiro', '35.00', '1.00', '0.00'),
(93, '2025-08-02 19:53:00', 'CartÃ£o de DÃ©bito', '50.00', '0.00', '0.00'),
(94, '2025-08-02 20:01:00', 'CartÃ£o de CrÃ©dito', '50.00', '0.00', '0.00'),
(95, '2025-08-02 21:06:00', 'Dinheiro', '20.00', '0.00', '0.00'),
(96, '2025-09-02 22:09:00', 'CartÃ£o de CrÃ©dito', '50.00', '0.00', '0.00'),
(97, '2025-09-23 21:38:00', 'CartÃ£o de DÃ©bito', '20.00', '0.00', '0.00'),
(98, '2025-09-27 19:43:00', 'CartÃ£o de DÃ©bito', '50.00', '0.00', '0.00'),
(99, '2025-09-27 19:43:00', 'Dinheiro', '20.00', '1.00', '0.00'),
(100, '2025-09-29 18:50:00', 'CartÃ£o de CrÃ©dito', '45.00', '0.00', '0.00'),
(102, '2025-09-29 18:51:00', 'Dinheiro', '65.00', '2.00', '0.00'),
(103, '2025-10-02 21:40:00', 'Pix', '45.00', '0.00', '0.00'),
(104, '2025-10-02 21:41:00', 'CartÃ£o de CrÃ©dito', '30.00', '0.00', '0.00'),
(106, '2025-10-06 14:20:00', 'CartÃ£o de DÃ©bito', '65.00', '0.00', '0.00'),
(107, '2025-10-06 14:21:00', 'CartÃ£o de DÃ©bito', '30.00', '0.00', '0.00'),
(108, '2025-10-06 21:29:00', 'Dinheiro', '50.00', '2.00', '0.00'),
(109, '2025-10-07 08:27:00', 'CartÃ£o de DÃ©bito', '115.00', '0.00', '0.00'),
(110, '2025-10-07 08:32:00', 'Pix', '40.00', '0.00', '0.00'),
(111, '2025-10-07 08:40:00', 'Dinheiro', '50.00', '0.00', '0.00'),
(112, '2025-10-07 08:40:00', 'CartÃ£o de CrÃ©dito', '140.00', '0.00', '0.00'),
(115, '2025-10-07 09:30:00', 'CartÃ£o de DÃ©bito', '30.00', '0.00', '0.00'),
(116, '2025-10-09 09:56:00', 'Dinheiro', '20.00', '0.00', '0.00'),
(117, '2025-10-09 10:37:00', 'CartÃ£o de DÃ©bito', '30.00', '0.00', '0.00'),
(118, '2025-10-12 10:50:00', 'CartÃ£o de CrÃ©dito', '110.00', '0.00', '0.00'),
(119, '2025-10-26 11:48:00', 'Pix', '19.00', '1.00', '0.00'),
(120, '2025-10-26 16:42:00', 'CartÃ£o de DÃ©bito', '40.00', '0.00', '0.00'),
(121, '2025-10-26 21:32:00', 'CartÃ£o de DÃ©bito', '30.00', '0.00', '0.00'),
(122, '2025-10-26 21:36:00', 'Dinheiro', '19.00', '1.00', '0.00'),
(123, '2025-11-12 21:33:00', 'CartÃ£o de DÃ©bito', '40.00', '0.00', '0.00'),
(124, '2026-02-14 13:43:00', 'CartÃ£o de DÃ©bito', '20.00', '0.00', '0.00'),
(125, '2026-03-22 09:14:00', 'CartÃ£o de CrÃ©dito', '50.00', '0.00', '0.00'),
(126, '2026-03-22 09:14:00', 'Pix', '25.00', '0.00', '0.00'),
(127, '2026-03-22 09:15:00', 'CartÃ£o de DÃ©bito', '20.00', '0.00', '0.00'),
(128, '2026-03-22 09:15:00', 'Dinheiro', '80.00', '0.00', '0.00'),
(129, '2026-04-15 14:16:00', 'CartÃ£o de DÃ©bito', '280.00', '0.00', '0.00'),
(130, '2026-04-15 14:21:00', 'CartÃ£o de CrÃ©dito', '625.00', '0.00', '0.00'),
(131, '2026-04-15 14:22:00', 'Pix', '75.00', '0.00', '0.00'),
(132, '2026-04-15 14:23:00', 'Dinheiro', '375.00', '0.00', '0.00'),
(133, '2026-04-15 14:24:00', 'CartÃ£o de CrÃ©dito', '1750.00', '0.00', '0.00'),
(134, '2026-04-15 14:24:00', 'CartÃ£o de DÃ©bito', '600.00', '0.00', '0.00'),
(135, '2026-04-15 14:25:00', 'CartÃ£o de CrÃ©dito', '1110.00', '0.00', '0.00'),
(136, '2026-04-15 14:38:00', 'CartÃ£o de CrÃ©dito', '510.00', '0.00', '0.00'),
(137, '2026-04-15 14:40:00', 'Pix', '625.00', '0.00', '0.00'),
(138, '2026-04-15 14:40:00', 'CartÃ£o de DÃ©bito', '900.00', '0.00', '0.00');

--
-- Índices de tabelas apagadas
--

--
-- Índices de tabela `itens_venda`
--
ALTER TABLE `itens_venda`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_venda` (`id_venda`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id_produto`),
  ADD UNIQUE KEY `nome_produto` (`nome_produto`),
  ADD UNIQUE KEY `codigo_barras` (`codigo_barras`);

--
-- Índices de tabela `projeto_previsao_estoque_usuarios`
--
ALTER TABLE `projeto_previsao_estoque_usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- Índices de tabela `relatorio_financeiro`
--
ALTER TABLE `relatorio_financeiro`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `relatorio_reposicao`
--
ALTER TABLE `relatorio_reposicao`
  ADD PRIMARY KEY (`id_produto`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- Índices de tabela `vendas`
--
ALTER TABLE `vendas`
  ADD PRIMARY KEY (`id_venda`);

--
-- AUTO_INCREMENT de tabelas apagadas
--

--
-- AUTO_INCREMENT de tabela `itens_venda`
--
ALTER TABLE `itens_venda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id_produto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de tabela `projeto_previsao_estoque_usuarios`
--
ALTER TABLE `projeto_previsao_estoque_usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `relatorio_financeiro`
--
ALTER TABLE `relatorio_financeiro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `vendas`
--
ALTER TABLE `vendas`
  MODIFY `id_venda` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
