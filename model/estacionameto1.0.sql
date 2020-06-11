-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 11-Jun-2020 às 17:30
-- Versão do servidor: 10.4.11-MariaDB
-- versão do PHP: 7.4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `estacionameto`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `carros`
--

CREATE TABLE `carros` (
  `placa` varchar(10) NOT NULL,
  `ultilizacoes` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `carros`
--

INSERT INTO `carros` (`placa`, `ultilizacoes`) VALUES
('ABC', 12),
('ASDW', 1),
('ASFG', 1),
('CDE', 1),
('FER', 2),
('LRIS', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `estadias`
--

CREATE TABLE `estadias` (
  `id` int(11) NOT NULL,
  `placa` varchar(10) NOT NULL,
  `data_entrada` datetime NOT NULL DEFAULT current_timestamp(),
  `data_saida` datetime NOT NULL,
  `valor` float NOT NULL,
  `estado` varchar(100) NOT NULL DEFAULT 'Estacionado' COMMENT 'Estacionado, Pago'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `estadias`
--

INSERT INTO `estadias` (`id`, `placa`, `data_entrada`, `data_saida`, `valor`, `estado`) VALUES
(49, 'ABC', '2020-06-11 10:24:28', '2020-06-11 10:24:49', 8, 'Pago'),
(50, 'CDE', '2020-06-11 10:24:32', '2020-06-11 10:40:04', 8, 'Pago'),
(51, 'FER', '2020-06-11 10:24:33', '2020-06-11 10:32:42', 8, 'Pago'),
(52, 'ABC', '2020-06-11 10:25:10', '2020-06-11 10:25:34', 8, 'Pago'),
(53, 'ABC', '2020-06-11 10:25:36', '2020-06-11 10:32:35', 8, 'Pago'),
(54, 'ABC', '2020-06-11 10:32:39', '2020-06-11 10:34:09', 8, 'Pago'),
(55, 'ASDW', '2020-06-11 10:32:45', '2020-06-11 10:32:48', 8, 'Pago'),
(56, 'FER', '2020-06-11 10:34:07', '2020-06-11 10:42:57', 8, 'Pago'),
(57, 'ABC', '2020-06-11 10:43:04', '2020-06-11 10:46:34', 0, 'Pago'),
(58, 'ABC', '2020-06-11 10:46:51', '2020-06-11 10:46:57', 8, 'Pago'),
(59, 'ABC', '2020-06-11 10:47:05', '2020-06-11 10:47:11', 8, 'Pago'),
(60, 'ABC', '2020-06-11 10:47:13', '2020-06-11 10:47:26', 8, 'Pago'),
(61, 'ABC', '2020-06-11 10:47:28', '2020-06-11 10:47:33', 8, 'Pago'),
(62, 'ABC', '2020-06-11 10:47:35', '2020-06-11 10:47:44', 0, 'Pago'),
(63, 'ABC', '2020-06-11 10:50:20', '2020-06-11 11:57:01', 24, 'Pago'),
(67, 'LRIS', '2020-06-11 11:56:50', '0000-00-00 00:00:00', 0, 'Estacionado'),
(68, 'ABC', '2020-06-11 12:27:25', '0000-00-00 00:00:00', 0, 'Estacionado');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `carros`
--
ALTER TABLE `carros`
  ADD PRIMARY KEY (`placa`);

--
-- Índices para tabela `estadias`
--
ALTER TABLE `estadias`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `estadias`
--
ALTER TABLE `estadias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
