-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 08-Dez-2022 às 00:45
-- Versão do servidor: 10.4.24-MariaDB
-- versão do PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `championshot`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `matches`
--

CREATE TABLE `matches` (
  `id` int(11) NOT NULL,
  `idTeamA` int(11) NOT NULL,
  `idTeamB` int(11) NOT NULL,
  `scoreA` int(11) NOT NULL,
  `scoreB` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `shot`
--

CREATE TABLE `shot` (
  `id` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `idWinner` int(11) NOT NULL,
  `idMatch` int(11) NOT NULL,
  `scoreA` int(11) NOT NULL,
  `scoreB` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `team`
--

CREATE TABLE `team` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `initials` varchar(4) NOT NULL,
  `chance` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `team`
--

INSERT INTO `team` (`id`, `name`, `initials`, `chance`) VALUES
(1, 'Barcelona FCB', 'FCB', 19),
(2, 'Real Madrid', 'CRM', 19),
(3, 'Paris Saint-Germain', 'PSG', 8);

-- --------------------------------------------------------

--
-- Estrutura da tabela `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `login` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `user`
--

INSERT INTO `user` (`id`, `name`, `login`, `password`) VALUES
(2, 'Gustavo Trizotti', 'trizotti@email.com', '$2y$10$AqoDAjVffQ.4IQ0iSVy5cOxRl6MRWfyq6NPe64iNrJhoSv/oDNr6G');

-- --------------------------------------------------------

--
-- Estrutura da tabela `userscore`
--

CREATE TABLE `userscore` (
  `id` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `idUser` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `matches`
--
ALTER TABLE `matches`
  ADD PRIMARY KEY (`id`,`idTeamA`,`idTeamB`) USING BTREE,
  ADD KEY `teamA` (`idTeamA`),
  ADD KEY `teamB` (`idTeamB`);

--
-- Índices para tabela `shot`
--
ALTER TABLE `shot`
  ADD PRIMARY KEY (`id`,`idUser`,`idMatch`) USING BTREE,
  ADD KEY `idUser` (`idUser`),
  ADD KEY `idMatch` (`idMatch`),
  ADD KEY `idWinner` (`idWinner`);

--
-- Índices para tabela `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `initials` (`initials`);

--
-- Índices para tabela `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`);

--
-- Índices para tabela `userscore`
--
ALTER TABLE `userscore`
  ADD PRIMARY KEY (`id`,`score`) USING BTREE,
  ADD KEY `userID` (`idUser`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `matches`
--
ALTER TABLE `matches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de tabela `shot`
--
ALTER TABLE `shot`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de tabela `team`
--
ALTER TABLE `team`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `userscore`
--
ALTER TABLE `userscore`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `matches`
--
ALTER TABLE `matches`
  ADD CONSTRAINT `teamA` FOREIGN KEY (`idTeamA`) REFERENCES `team` (`id`),
  ADD CONSTRAINT `teamB` FOREIGN KEY (`idTeamB`) REFERENCES `team` (`id`);

--
-- Limitadores para a tabela `shot`
--
ALTER TABLE `shot`
  ADD CONSTRAINT `idMatch` FOREIGN KEY (`idMatch`) REFERENCES `matches` (`id`),
  ADD CONSTRAINT `idUser` FOREIGN KEY (`idUser`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `idWinner` FOREIGN KEY (`idWinner`) REFERENCES `team` (`id`);

--
-- Limitadores para a tabela `userscore`
--
ALTER TABLE `userscore`
  ADD CONSTRAINT `userID` FOREIGN KEY (`idUser`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
