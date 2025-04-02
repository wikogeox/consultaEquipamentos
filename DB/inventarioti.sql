-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 02-Abr-2025 às 12:46
-- Versão do servidor: 8.0.36
-- versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `inventarioti`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `computadores`
--

CREATE TABLE `computadores` (
  `id` int NOT NULL,
  `nome_computador` varchar(255) NOT NULL,
  `nome_dominio` varchar(255) DEFAULT NULL,
  `SO` varchar(100) DEFAULT NULL,
  `modelo` varchar(100) DEFAULT NULL,
  `serial_number` varchar(100) DEFAULT NULL,
  `processador` varchar(100) DEFAULT NULL,
  `bios` varchar(100) DEFAULT NULL,
  `memoria_ram` varchar(50) DEFAULT NULL,
  `discos` text,
  `license_key` varchar(255) DEFAULT NULL,
  `id_pavilhao` int DEFAULT NULL,
  `id_sala` int DEFAULT NULL,
  `data_importacao` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `computadores`
--

INSERT INTO `computadores` (`id`, `nome_computador`, `nome_dominio`, `SO`, `modelo`, `serial_number`, `processador`, `bios`, `memoria_ram`, `discos`, `license_key`, `id_pavilhao`, `id_sala`, `data_importacao`) VALUES
(1, 'FD-OE3-09', 'ESFERREIRADIAS', 'Microsoft Windows 10 Pro 64-Bit', 'HP EliteDesk 800 G2 SFF', 'CZC7108BYX', 'Intel(R) Core(TM) i5-6600 CPU @ 3.30GHz', 'HPQOEM - 0', '16384MB', '476.9GB', 'nao tem', 1, 3, '2025-04-01'),
(2, 'FD-OE3-07', 'ESFERREIRADIAS', 'Microsoft Windows 10 Pro 64-Bit', 'HP EliteDesk 800 G2 SFF', 'CZC712BBJM', 'Intel(R) Core(TM) i5-6600 CPU @ 3.30GHz', 'HPQOEM - 0', '16384MB', '476.9GB', 'nao tem', 1, 3, '2025-04-01'),
(3, 'FD-OE3-04', 'ESFERREIRADIAS', 'Microsoft Windows 10 Pro 64-Bit', 'HP Compaq 8000 Elite SFF PC', 'CZC118877R', 'Intel(R) Core(TM)2 Duo CPU     E8400  @ 3.00GHz', 'HPQOEM - 20091022', '8228MB', '223.6GB', 'HVXKV-CXCVG-848FP-B8GBH-HX3GF', 1, 3, '2025-04-01'),
(4, 'FD-OE3-02', 'ESFERREIRADIAS', 'Microsoft Windows 10 Pro 64-Bit', 'HP Compaq 8000 Elite SFF PC', 'CZC0160KJF', 'Intel(R) Core(TM)2 Duo CPU     E8400  @ 3.00GHz', 'HPQOEM - 20110720', '8228MB', '372.6GB', 'BQKFC-Q82WV-36TH8-T2Y4K-X46JQ', 1, 3, '2025-04-01'),
(5, 'FD-OE3-01', 'ESFERREIRADIAS', 'Microsoft Windows 10 Pro 64-Bit', 'HP Compaq 8000 Elite SFF PC', 'CZC018BHBN', 'Intel(R) Core(TM)2 Duo CPU     E8400  @ 3.00GHz', 'HPQOEM - 20091022', '8228MB', '456.5GB', 'RFJX2-GKCBD-X6QB9-F33C9-KGWT3', 1, 3, '2025-04-01'),
(6, 'FD-OE3-26', 'ESFERREIRADIAS', 'Microsoft Windows 10 Pro 64-Bit', 'HP Compaq 8000 Elite SFF PC', '2UA0150FD9', 'Intel(R) Core(TM)2 Duo CPU     E8500  @ 3.16GHz', 'HPQOEM - 20151014', '8228MB', '260.8GB', '89GG7-FY9MQ-P4X4D-7PBCB-DMRCG', 1, 3, '2025-04-01'),
(7, 'FD-OE3-23', 'ESFERREIRADIAS', 'Microsoft Windows 7 Enterprise 32-Bit', 'HP Compaq dc7800 Small Form Factor', 'CZC92571FW', 'Intel(R) Core(TM)2 Duo CPU     E8400  @ 3.00GHz', 'HPQOEM - 20110721', '3567MB', '260.8GB', 'C3CKW-RTDKM-K3GXV-7R7B6-68YW8', 1, 3, '2025-04-01');

-- --------------------------------------------------------

--
-- Estrutura da tabela `pavilhao`
--

CREATE TABLE `pavilhao` (
  `id` int NOT NULL,
  `nome_pavilhao` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `pavilhao`
--

INSERT INTO `pavilhao` (`id`, `nome_pavilhao`) VALUES
(1, 'Eletricidade');

-- --------------------------------------------------------

--
-- Estrutura da tabela `salas`
--

CREATE TABLE `salas` (
  `id` int NOT NULL,
  `nome_sala` varchar(255) NOT NULL,
  `id_pavilhao` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `salas`
--

INSERT INTO `salas` (`id`, `nome_sala`, `id_pavilhao`) VALUES
(1, 'OE1', 1),
(2, 'OE2', 1),
(3, 'OE3', 1),
(4, 'OE4', 1),
(5, 'OE5', 1);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `computadores`
--
ALTER TABLE `computadores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pavilhao` (`id_pavilhao`),
  ADD KEY `id_sala` (`id_sala`);

--
-- Índices para tabela `pavilhao`
--
ALTER TABLE `pavilhao`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `salas`
--
ALTER TABLE `salas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pavilhao` (`id_pavilhao`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `computadores`
--
ALTER TABLE `computadores`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `pavilhao`
--
ALTER TABLE `pavilhao`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `salas`
--
ALTER TABLE `salas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `computadores`
--
ALTER TABLE `computadores`
  ADD CONSTRAINT `computadores_ibfk_1` FOREIGN KEY (`id_pavilhao`) REFERENCES `pavilhao` (`id`),
  ADD CONSTRAINT `computadores_ibfk_2` FOREIGN KEY (`id_sala`) REFERENCES `salas` (`id`);

--
-- Limitadores para a tabela `salas`
--
ALTER TABLE `salas`
  ADD CONSTRAINT `salas_ibfk_1` FOREIGN KEY (`id_pavilhao`) REFERENCES `pavilhao` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
