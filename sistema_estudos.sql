-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 17/02/2025 às 20:28
-- Versão do servidor: 8.3.0
-- Versão do PHP: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `sistema_estudos`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `kanban`
--

DROP TABLE IF EXISTS `kanban`;
CREATE TABLE IF NOT EXISTS `kanban` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `column_name` enum('To Do','In Progress','Done') NOT NULL,
  `position` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `kanban`
--

INSERT INTO `kanban` (`id`, `name`, `column_name`, `position`, `created_at`, `updated_at`) VALUES
(4, 'teste', 'To Do', 1, '2025-02-17 20:19:11', '2025-02-17 20:21:59');

-- --------------------------------------------------------

--
-- Estrutura para tabela `rotinas`
--

DROP TABLE IF EXISTS `rotinas`;
CREATE TABLE IF NOT EXISTS `rotinas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) DEFAULT NULL,
  `data_inicio` date DEFAULT NULL,
  `data_termino` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `rotina_dias`
--

DROP TABLE IF EXISTS `rotina_dias`;
CREATE TABLE IF NOT EXISTS `rotina_dias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `rotina_id` int DEFAULT NULL,
  `dia_semana` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rotina_id` (`rotina_id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `rotina_dias`
--

INSERT INTO `rotina_dias` (`id`, `rotina_id`, `dia_semana`) VALUES
(4, 2, 1),
(5, 2, 2),
(6, 2, 3),
(7, 3, 1),
(8, 3, 2),
(9, 3, 3),
(10, 3, 4),
(11, 3, 5),
(12, 3, 6),
(13, 3, 0),
(17, 2, 1),
(18, 3, 1),
(19, 3, 2),
(20, 3, 3),
(21, 3, 4),
(22, 3, 5),
(23, 3, 6),
(24, 3, 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
