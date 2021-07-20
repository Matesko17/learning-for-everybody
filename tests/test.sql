-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u1
-- http://www.phpmyadmin.net
--
-- Počítač: localhost
-- Vytvořeno: Ned 15. lis 2015, 19:23
-- Verze serveru: 10.0.22-MariaDB-0+deb8u1
-- Verze PHP: 5.6.14-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databáze: `test`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `prefix_route`
--

CREATE TABLE IF NOT EXISTS `prefix_route` (
`Id` int(11) NOT NULL,
  `Presenter` varchar(50) NOT NULL,
  `Action` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='routy';

--
-- Vypisuji data pro tabulku `prefix_route`
--

INSERT INTO `prefix_route` (`Id`, `Presenter`, `Action`) VALUES
(1, 'Homepage', 'detail'),
(2, 'Homepage', 'def'),
(3, 'Homepage', 'default'),
(4, 'Homepage', 'alias');

-- --------------------------------------------------------

--
-- Struktura tabulky `prefix_route_alias`
--

CREATE TABLE IF NOT EXISTS `prefix_route_alias` (
`Id` int(11) NOT NULL,
  `IdRoute` int(11) NOT NULL COMMENT 'fk na router',
  `Lang` varchar(10) NOT NULL COMMENT 'jazyk',
  `Slug` varchar(255) NOT NULL COMMENT 'textovy slug',
  `IdItem` int(11) DEFAULT NULL COMMENT 'id polozky',
  `Parameters` text COMMENT 'serializovane extra parametry',
  `Added` datetime NOT NULL COMMENT 'datum pridani slouzici jako poradi'
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='route slug';

--
-- Vypisuji data pro tabulku `prefix_route_alias`
--

INSERT INTO `prefix_route_alias` (`Id`, `IdRoute`, `Lang`, `Slug`, `IdItem`, `Parameters`, `Added`) VALUES
(1, 1, 'cs', 'home/detailik/detail-abc', 123, NULL, '2015-11-15 18:47:58'),
(2, 2, 'cs', 'home/detail-cs', NULL, NULL, '2015-11-15 18:47:58'),
(3, 2, 'en', 'home/detail-en', NULL, NULL, '2015-11-15 18:47:58'),
(4, 4, 'cs', 'home/detail/detail', NULL, NULL, '2015-11-15 18:47:59'),
(5, 4, 'cs', 'home/detailovy/parametr', NULL, NULL, '2015-11-15 18:48:00'),
(6, 3, 'cs', 'home/parametr', NULL, 'a:1:{s:2:"gg";s:3:"123";}', '2015-11-15 18:48:00'),
(7, 3, 'cs', 'home/parametr1', NULL, 'a:1:{s:2:"bb";s:3:"321";}', '2015-11-15 18:48:01'),
(8, 3, 'cs', 'home/parametr2', NULL, 'a:2:{s:2:"gg";s:3:"456";s:2:"bb";s:3:"789";}', '2015-11-15 18:48:02'),
(9, 2, 'en', 'home/detail-en', NULL, NULL, '2015-11-15 18:48:04'),
(10, 2, 'en', 'home/detail-en', NULL, NULL, '2015-11-15 18:48:09');

-- --------------------------------------------------------

--
-- Struktura tabulky `prefix_route_seo`
--

CREATE TABLE IF NOT EXISTS `prefix_route_seo` (
`Id` int(11) NOT NULL,
  `IdRoute` int(11) NOT NULL COMMENT 'fk na router',
  `IdItem` int(11) DEFAULT NULL COMMENT 'id polozky',
  `TitleCS` varchar(255) DEFAULT NULL,
  `TitleEN` varchar(255) DEFAULT NULL,
  `DescriptionCS` varchar(255) DEFAULT NULL,
  `DescriptionEN` varchar(255) DEFAULT NULL,
  `KeywordsCS` varchar(255) DEFAULT NULL,
  `KeywordsEN` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='route seo';

-- --------------------------------------------------------

--
-- Struktura tabulky `prefix_translations`
--

CREATE TABLE IF NOT EXISTS `prefix_translations` (
`Id` int(11) NOT NULL,
  `Ident` varchar(100) NOT NULL,
  `CS` text,
  `EN` text
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='preklady';

--
-- Vypisuji data pro tabulku `prefix_translations`
--

INSERT INTO `prefix_translations` (`Id`, `Ident`, `CS`, `EN`) VALUES
(1, '3b96a015574f9c8007c65c4f5a29c70b', 'ahoj svete!', NULL),
(2, 'fc3943a1a16ef7c60dfa17fbb77fb374:plural:0', 'mame doma %d oken', NULL),
(3, 'fc3943a1a16ef7c60dfa17fbb77fb374:plural:1', 'mame doma %d okno', NULL),
(4, 'fc3943a1a16ef7c60dfa17fbb77fb374:plural:2', 'mame doma %d okna', NULL),
(5, '74670ea49d41b0d22b3eea352b7a9058:plural:0', 'mame doma %d oken a %d tapetu', NULL),
(6, '74670ea49d41b0d22b3eea352b7a9058:plural:1', 'mame doma %d okno a %d tapetu', NULL),
(7, '74670ea49d41b0d22b3eea352b7a9058:plural:2', 'mame doma %d okna a %d tapetu', NULL);

--
-- Klíče pro exportované tabulky
--

--
-- Klíče pro tabulku `prefix_route`
--
ALTER TABLE `prefix_route`
 ADD PRIMARY KEY (`Id`);

--
-- Klíče pro tabulku `prefix_route_alias`
--
ALTER TABLE `prefix_route_alias`
 ADD PRIMARY KEY (`Id`), ADD KEY `fk_route_alias_route_idx` (`IdRoute`);

--
-- Klíče pro tabulku `prefix_route_seo`
--
ALTER TABLE `prefix_route_seo`
 ADD PRIMARY KEY (`Id`), ADD KEY `fk_route_seo_route_idx` (`IdRoute`);

--
-- Klíče pro tabulku `prefix_translations`
--
ALTER TABLE `prefix_translations`
 ADD PRIMARY KEY (`Id`), ADD UNIQUE KEY `Ident_UNIQUE` (`Ident`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `prefix_route`
--
ALTER TABLE `prefix_route`
MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pro tabulku `prefix_route_alias`
--
ALTER TABLE `prefix_route_alias`
MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT pro tabulku `prefix_route_seo`
--
ALTER TABLE `prefix_route_seo`
MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pro tabulku `prefix_translations`
--
ALTER TABLE `prefix_translations`
MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
