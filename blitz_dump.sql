-- phpMyAdmin SQL Dump
-- version 4.0.10.12
-- http://www.phpmyadmin.net
--
-- Хост: 127.8.185.130:3306
-- Время создания: Июн 26 2017 г., 13:27
-- Версия сервера: 5.5.52
-- Версия PHP: 5.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `blitz`
--

-- --------------------------------------------------------

--
-- Структура таблицы `forceseats`
--

CREATE TABLE IF NOT EXISTS `forceseats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `names` varchar(1000) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `forceseats`
--

INSERT INTO `forceseats` (`id`, `names`) VALUES
(1, 'Githzeru Kindgor PNT/PLT Zdarova');

-- --------------------------------------------------------

--
-- Структура таблицы `new_replays`
--

CREATE TABLE IF NOT EXISTS `new_replays` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `cheat` int(1) NOT NULL DEFAULT '0',
  `done` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=42 ;

--
-- Дамп данных таблицы `new_replays`
--

INSERT INTO `new_replays` (`id`, `url`, `cheat`, `done`) VALUES
(1, 'http://tenhou.net/0/?log=2017052716gm-0009-11816-de3770bf&tw=0', 0, 1),
(2, 'http://tenhou.net/0/?log=2017052716gm-0009-11816-de3770bf&tw=1', 0, 1),
(3, 'http://tenhou.net/0/?log=2017052716gm-0009-11816-b9d3e8a9&tw=3', 0, 1),
(4, 'http://tenhou.net/0/?log=2017052716gm-0009-11816-8b016842&tw=3', 0, 1),
(5, 'http://tenhou.net/0/?log=2017052716gm-0009-11816-927db9eb&tw=2', 0, 1),
(6, 'http://tenhou.net/0/?log=2017052716gm-0009-11816-95c9332f&tw=3', 0, 1),
(7, 'http://tenhou.net/0/?log=2017052716gm-0009-11816-4b0f46d0&tw=3', 0, 1),
(8, 'http://tenhou.net/0/?log=2017052716gm-0009-11816-bee60ba4&tw=1', 0, 1),
(9, 'http://tenhou.net/0/?log=2017052716gm-0009-11816-bee60ba4&tw=0', 0, 1),
(10, 'http://tenhou.net/0/?wg=7EE6005F', 0, 1),
(11, 'http://tenhou.net/0/?log=2017052716gm-0009-11816-bdb77e6b&tw=1', 0, 1),
(12, 'http://tenhou.net/0/?log=2017052716gm-0009-11816-83c1f205', 0, 1),
(13, 'http://tenhou.net/0/?log=2017052716gm-0009-11816-8d8c7071', 0, 1),
(14, 'http://tenhou.net/0/?log=2017052717gm-0009-11816-6ae2aede&tw=0', 0, 1),
(15, ' http://tenhou.net/0/?log=2017052717gm-0009-11816-0d3e52fe&tw=1', 0, 1),
(16, 'http://tenhou.net/0/?C18167032', 0, 1),
(17, 'http://tenhou.net/0/?log=2017052717gm-0009-11816-28b96af1&tw=2', 0, 1),
(18, 'http://tenhou.net/0/?log=2017052717gm-0009-11816-24f683ea&tw=0', 0, 1),
(19, 'http://tenhou.net/0/?log=2017052717gm-0009-11816-24f683ea&tw=1', 0, 1),
(20, 'http://tenhou.net/0/?log=2017052717gm-0009-11816-54343baf&tw=0', 0, 1),
(21, 'http://tenhou.net/0/?log=2017052717gm-0009-11816-652626d0&tw=2', 0, 1),
(22, 'http://tenhou.net/0/?log=2017052717gm-0009-11816-5e26a0b6&tw=2', 0, 1),
(23, 'http://tenhou.net/0/?log=2017052718gm-0009-11816-7011326d&tw=0', 0, 1),
(24, 'http://tenhou.net/0/?log=2017052718gm-0009-11816-2c9f1e76&tw=3', 0, 1),
(25, 'http://tenhou.net/0/?log=2017052718gm-0009-11816-7e72cb17&tw=2', 0, 1),
(26, 'http://tenhou.net/0/?log=2017052718gm-0009-11816-d293afee', 0, 1),
(27, 'http://tenhou.net/0/?log=2017052718gm-0009-11816-2ec517cc&tw=0', 0, 1),
(28, 'http://tenhou.net/0/?log=2017052718gm-0009-11816-d293afee', 0, 1),
(29, 'http://tenhou.net/0/?log=2017052718gm-0009-11816-d293afee', 0, 1),
(30, 'http://tenhou.net/0/?log=2017052718gm-0009-11816-492816e0&tw=0', 0, 1),
(31, 'http://tenhou.net/0/?log=2017052718gm-0009-11816-d293afee', 0, 1),
(32, 'http://tenhou.net/0/?log=2017052719gm-0009-11816-1fb89b9d&tw=0', 0, 1),
(33, 'http://tenhou.net/0/?log=2017052719gm-0009-11816-0732d554&tw=0', 0, 1),
(34, 'http://tenhou.net/0/?log=2017052719gm-0009-11816-20bf9d16&tw=2', 0, 1),
(35, 'http://tenhou.net/0/?log=2017052719gm-0009-11816-30e9a008', 0, 1),
(36, 'http://tenhou.net/0/?log=2017052719gm-0009-11816-23bd83bf', 0, 1),
(37, 'http://tenhou.net/0/?log=2017052719gm-0009-11816-d5e9e194', 0, 1),
(38, 'http://tenhou.net/0/?log=2017052719gm-0009-11816-777a8a68&tw=0', 0, 0),
(39, 'http://tenhou.net/0/?log=2017052716gm-0009-11816-8da1187b&tw=3', 0, 0),
(40, 'http://tenhou.net/0/?log=2017052719gm-0009-11816-777a8a68&tw=1', 0, 0),
(41, 'http://tenhou.net/0/?log=2017052717gm-0009-11816-36f8b556&tw=2', 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `params`
--

CREATE TABLE IF NOT EXISTS `params` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(15) NOT NULL,
  `round` int(1) NOT NULL,
  `time` bigint(13) NOT NULL,
  `lobby` varchar(10) NOT NULL,
  `delay` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `params`
--

INSERT INTO `params` (`id`, `status`, `round`, `time`, `lobby`, `delay`) VALUES
(1, 'end', 5, 1495883560150, 'C18167032', 600000);

-- --------------------------------------------------------

--
-- Структура таблицы `registrations`
--

CREATE TABLE IF NOT EXISTS `registrations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(10) NOT NULL,
  `contacts` varchar(300) DEFAULT NULL,
  `comment` varchar(200) DEFAULT NULL,
  `anonymous` int(1) NOT NULL,
  `notify` int(1) NOT NULL,
  `news` int(1) NOT NULL,
  `lang` varchar(20) NOT NULL,
  `discordName` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `discriminator` varchar(10) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `offline` varchar(250) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `confirmed` int(1) NOT NULL DEFAULT '0',
  `disqual` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=81 ;

--
-- Дамп данных таблицы `registrations`
--

INSERT INTO `registrations` (`id`, `name`, `contacts`, `comment`, `anonymous`, `notify`, `news`, `lang`, `discordName`, `discriminator`, `offline`, `confirmed`, `disqual`) VALUES
(1, 'ReiReiRe', NULL, NULL, 1, 1, 0, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 1),
(2, 'Vitaly', NULL, NULL, 0, 1, 1, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 0),
(3, 'yulkolog', NULL, NULL, 0, 1, 1, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 1),
(4, 'xxldr', NULL, NULL, 0, 1, 1, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 1),
(5, 'YMI', NULL, NULL, 0, 1, 1, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 1),
(6, 'heilage', NULL, NULL, 0, 1, 1, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 0),
(7, 'AsD', NULL, NULL, 0, 1, 1, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 0),
(8, 'Vicente', NULL, NULL, 1, 1, 1, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 0),
(9, 'Babaika', NULL, NULL, 0, 1, 1, 'en-US', NULL, NULL, NULL, 0, 1),
(10, 'Sayaka-s', NULL, NULL, 0, 1, 1, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 0),
(11, 'cronion', NULL, NULL, 0, 1, 1, 'ru', NULL, NULL, NULL, 0, 1),
(12, 'Roj', NULL, NULL, 0, 1, 1, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 0),
(13, 'Trilon57', NULL, NULL, 0, 1, 1, 'ru,en-US;q=0.8,en;q=', NULL, NULL, NULL, 0, 0),
(14, 'RainCat', NULL, NULL, 0, 1, 1, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 0),
(15, 'MCR', NULL, NULL, 0, 1, 1, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 1),
(16, 'シュレーディンガ', NULL, NULL, 1, 0, 0, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 0),
(17, 'Gikls', NULL, NULL, 1, 1, 0, 'ru,en-US;q=0.8,en;q=', NULL, NULL, NULL, 0, 0),
(18, 'KIRILLLL', NULL, NULL, 0, 1, 1, 'ru-RU,en-US;q=0.8', NULL, NULL, NULL, 0, 0),
(19, 'Kdenis', NULL, NULL, 0, 1, 1, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 1),
(20, 'BBB', NULL, NULL, 0, 1, 1, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 0),
(21, 'm.shinji', NULL, NULL, 0, 1, 1, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 0),
(22, 'Benawi', NULL, NULL, 0, 1, 1, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 0),
(23, 'Tyanka', NULL, NULL, 1, 1, 1, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 0),
(24, 'クマモンさん', NULL, NULL, 0, 1, 1, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 0),
(25, 'Lolt', NULL, NULL, 0, 1, 1, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 1),
(26, 'Houby', NULL, NULL, 0, 1, 1, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 1),
(27, 'Suanko', NULL, NULL, 0, 1, 1, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 1),
(28, 'Kindgor', NULL, NULL, 0, 1, 1, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 0),
(29, 'rynermax', NULL, NULL, 0, 1, 1, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 0),
(30, 'Ｍａｔｔｅ', NULL, NULL, 0, 1, 1, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 1),
(31, 'Githzeru', NULL, NULL, 0, 0, 0, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 0),
(32, 'Vestrump', NULL, NULL, 0, 1, 1, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 1),
(33, 'CapBlood', NULL, NULL, 0, 1, 1, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 1),
(34, 'Alu', NULL, NULL, 0, 1, 1, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 1),
(35, 'OmegaID', NULL, NULL, 1, 1, 1, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 1),
(36, 'Zdarova', NULL, NULL, 0, 1, 1, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 0),
(37, 'Fantomas', NULL, NULL, 1, 1, 1, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 0),
(38, 'yakuman', NULL, NULL, 0, 0, 0, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 0),
(39, 'Kai23', NULL, NULL, 1, 1, 1, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 1),
(40, 'Ivan.Fe', NULL, NULL, 0, 1, 1, 'ru-RU', NULL, NULL, NULL, 0, 1),
(41, 'Sheraly', NULL, NULL, 0, 1, 1, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 0),
(42, 'Kukankai', NULL, NULL, 0, 1, 1, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 0),
(43, 'KyoMaooo', NULL, NULL, 0, 1, 1, 'ru', NULL, NULL, NULL, 0, 0),
(44, '7shanten', NULL, NULL, 0, 1, 1, 'en-US,en;q=0.8,ru;q=', NULL, NULL, NULL, 0, 1),
(45, 'Lupus', NULL, NULL, 0, 1, 1, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 0),
(46, ';_;', NULL, NULL, 1, 1, 0, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 1),
(47, 'Drakon67', NULL, NULL, 0, 1, 1, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 1),
(48, 'Vodonoc', NULL, NULL, 0, 1, 1, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 1),
(49, 'Kapustka', NULL, NULL, 0, 1, 1, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 1),
(51, 'アンドロイド', NULL, NULL, 0, 1, 1, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 0),
(52, 'NTrigby', NULL, NULL, 0, 1, 0, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 0),
(53, 'wiarlawd', NULL, NULL, 0, 1, 1, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 0),
(54, 'Baka', NULL, NULL, 0, 0, 0, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 1),
(55, 'recursor', NULL, NULL, 0, 1, 1, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 1),
(56, 'JayTord', NULL, NULL, 0, 1, 0, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 0),
(57, 'Romual', NULL, NULL, 0, 1, 1, 'en-US,en;q=0.8', NULL, NULL, NULL, 0, 1),
(58, 'Djett', NULL, NULL, 0, 1, 1, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 0),
(59, 'MtrsNxz', NULL, NULL, 0, 1, 1, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 1),
(60, 'vikmar', NULL, NULL, 0, 1, 1, 'ru,en-US;q=0.8,en;q=', NULL, NULL, NULL, 0, 1),
(61, 'Remus', NULL, NULL, 0, 0, 0, 'en-GB,en;q=0.8,en-US', NULL, NULL, NULL, 0, 0),
(62, 'Severus', NULL, NULL, 0, 0, 0, 'en-GB,en;q=0.8,en-US', NULL, NULL, NULL, 0, 0),
(63, 'Lyudmila', NULL, NULL, 0, 1, 1, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 0),
(64, 'Medvedj', NULL, NULL, 1, 1, 1, 'ru', NULL, NULL, NULL, 0, 0),
(65, 'Zyklop', NULL, NULL, 0, 1, 0, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 0),
(66, 'm1', NULL, NULL, 0, 1, 1, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 0),
(67, 'Bowie', NULL, NULL, 0, 0, 0, 'ru,en;q=0.8', NULL, NULL, NULL, 0, 1),
(68, 'Унди', NULL, NULL, 1, 1, 1, 'en-US,en;q=0.8', NULL, NULL, NULL, 0, 1),
(69, 'Francyz', NULL, NULL, 0, 1, 1, 'ru,en;q=0.8', NULL, NULL, NULL, 0, 0),
(70, 'Replay', NULL, NULL, 0, 1, 1, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 1),
(71, 'ponchik', NULL, NULL, 0, 1, 1, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 0),
(72, 'relghufr', NULL, NULL, 0, 0, 0, 'en-US,en;q=0.8,ru;q=', NULL, NULL, NULL, 0, 0),
(74, 'PNT/PLT', NULL, NULL, 0, 1, 1, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 0),
(75, 'dis', NULL, NULL, 0, 0, 1, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 0),
(77, 'Slevko90', NULL, NULL, 0, 0, 0, 'ru-RU,ru;q=0.8,en-US', NULL, NULL, NULL, 0, 1),
(78, 'ggBuffon', NULL, NULL, 0, 0, 1, 'ru,en;q=0.8', NULL, NULL, NULL, 0, 0),
(79, 'KCat', NULL, NULL, 1, 1, 1, '1', NULL, NULL, NULL, 0, 0),
(80, 'Tau', NULL, NULL, 1, 1, 1, '1', NULL, NULL, NULL, 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `replays`
--

CREATE TABLE IF NOT EXISTS `replays` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `round` int(1) NOT NULL,
  `board` int(2) NOT NULL,
  `url` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `round` (`round`,`board`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=55 ;

--
-- Дамп данных таблицы `replays`
--

INSERT INTO `replays` (`id`, `round`, `board`, `url`) VALUES
(1, 1, 5, '2017052716gm-0009-11816-de3770bf'),
(3, 1, 13, '2017052716gm-0009-11816-b9d3e8a9'),
(4, 1, 6, '2017052716gm-0009-11816-8b016842'),
(5, 1, 12, '2017052716gm-0009-11816-927db9eb'),
(6, 1, 4, '2017052716gm-0009-11816-95c9332f'),
(7, 1, 1, '2017052716gm-0009-11816-4b0f46d0'),
(8, 1, 9, '2017052716gm-0009-11816-bee60ba4'),
(10, 1, 8, '2017052716gm-0009-11816-bdb77e6b'),
(11, 1, 7, '2017052716gm-0009-11816-83c1f205'),
(12, 1, 2, '2017052716gm-0009-11816-8d8c7071'),
(13, 2, 6, '2017052717gm-0009-11816-6ae2aede'),
(14, 2, 7, '2017052717gm-0009-11816-0d3e52fe'),
(15, 2, 5, '2017052717gm-0009-11816-28b96af1'),
(16, 2, 9, '2017052717gm-0009-11816-24f683ea'),
(18, 2, 12, '2017052717gm-0009-11816-54343baf'),
(19, 2, 11, '2017052717gm-0009-11816-652626d0'),
(39, 2, 3, '2017052717gm-0009-11816-5e26a0b6'),
(40, 3, 8, '2017052718gm-0009-11816-7011326d'),
(41, 3, 5, '2017052718gm-0009-11816-2c9f1e76'),
(42, 3, 12, '2017052718gm-0009-11816-7e72cb17'),
(43, 3, 1, '2017052718gm-0009-11816-2ec517cc'),
(44, 3, 2, '2017052718gm-0009-11816-d293afee'),
(47, 3, 7, '2017052718gm-0009-11816-492816e0'),
(49, 4, 9, '2017052719gm-0009-11816-1fb89b9d'),
(50, 4, 6, '2017052719gm-0009-11816-0732d554'),
(51, 4, 10, '2017052719gm-0009-11816-20bf9d16'),
(52, 4, 2, '2017052719gm-0009-11816-30e9a008'),
(53, 4, 4, '2017052719gm-0009-11816-23bd83bf'),
(54, 4, 3, '2017052719gm-0009-11816-d5e9e194');

-- --------------------------------------------------------

--
-- Структура таблицы `reports`
--

CREATE TABLE IF NOT EXISTS `reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `who` varchar(20) NOT NULL,
  `message` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `reports`
--

INSERT INTO `reports` (`id`, `who`, `message`) VALUES
(2, 'kdenis', 'не подтвердилось участие в турнире'),
(3, 'KyoMaooo', 'нет таблицы итоговой');

-- --------------------------------------------------------

--
-- Структура таблицы `results`
--

CREATE TABLE IF NOT EXISTS `results` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `round` int(1) NOT NULL,
  `board` int(2) NOT NULL,
  `player_id` int(3) NOT NULL,
  `start_points` int(5) DEFAULT NULL,
  `end_points` int(6) DEFAULT NULL,
  `place` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=245 ;

--
-- Дамп данных таблицы `results`
--

INSERT INTO `results` (`id`, `round`, `board`, `player_id`, `start_points`, `end_points`, `place`) VALUES
(1, 1, 1, 80, 25000, 77500, 1),
(2, 1, 1, 28, 25000, 42900, 2),
(3, 1, 1, 0, NULL, NULL, 0),
(4, 1, 1, 17, 25000, -12800, 4),
(5, 1, 2, 79, 25000, 17800, 3),
(6, 1, 2, 7, 25000, 34600, 2),
(7, 1, 2, 2, 25000, -4900, 4),
(8, 1, 2, 0, NULL, NULL, 0),
(9, 1, 3, 38, 25000, -1500, 4),
(10, 1, 3, 53, 25000, 17000, 3),
(11, 1, 3, 63, 25000, 74200, 1),
(12, 1, 3, 52, 25000, 30300, 2),
(13, 1, 4, 0, NULL, NULL, 0),
(14, 1, 4, 77, 25000, 7800, 3),
(15, 1, 4, 10, 25000, 85600, 1),
(16, 1, 4, 62, 25000, 34700, 2),
(17, 1, 5, 65, 25000, 34800, 2),
(18, 1, 5, 56, 25000, 24200, 3),
(19, 1, 5, 75, 25000, 81000, 1),
(20, 1, 5, 72, 25000, -20000, 4),
(21, 1, 6, 66, 25000, -12800, 4),
(22, 1, 6, 61, 25000, 36200, 2),
(23, 1, 6, 46, 25000, 23900, 3),
(24, 1, 6, 22, 25000, 72700, 1),
(25, 1, 7, 0, NULL, NULL, 0),
(26, 1, 7, 12, 25000, -21900, 4),
(27, 1, 7, 69, 25000, 83900, 1),
(28, 1, 7, 45, 25000, 18000, 3),
(29, 1, 8, 71, 25000, 31700, 2),
(30, 1, 8, 21, 25000, 21400, 3),
(31, 1, 8, 74, 25000, 66300, 1),
(32, 1, 8, 29, 25000, 600, 4),
(33, 1, 9, 54, 25000, 36300, 2),
(34, 1, 9, 41, 25000, 74300, 1),
(35, 1, 9, 14, 25000, -1100, 4),
(36, 1, 9, 37, 25000, 10500, 3),
(37, 1, 10, 18, 25000, -5100, 4),
(38, 1, 10, 20, 25000, 13000, 3),
(39, 1, 10, 58, 25000, 39000, 2),
(40, 1, 10, 43, 25000, 73100, 1),
(41, 1, 11, 31, 25000, 89900, 1),
(42, 1, 11, 23, 25000, -11500, 4),
(43, 1, 11, 16, 25000, 35300, 2),
(44, 1, 11, 51, 25000, 6300, 3),
(45, 1, 12, 64, 25000, 900, 3),
(46, 1, 12, 36, 25000, 88200, 1),
(47, 1, 12, 6, 25000, 47800, 2),
(48, 1, 12, 8, 25000, -16900, 4),
(49, 1, 13, 42, 25000, 39600, 2),
(50, 1, 13, 24, 25000, 17000, 3),
(51, 1, 13, 78, 25000, -9600, 4),
(52, 1, 13, 13, 25000, 73000, 1),
(53, 2, 1, 28, 24300, 68400, 1),
(54, 2, 1, 63, 22000, 13800, 3),
(55, 2, 1, 52, 24300, 2100, 4),
(56, 2, 1, 2, 29400, 35700, 2),
(57, 2, 2, 45, 26200, 88800, 1),
(58, 2, 2, 79, 26200, 33600, 2),
(59, 2, 2, 53, 26200, 19200, 3),
(60, 2, 2, 80, 21400, -21600, 4),
(61, 2, 3, 20, 25000, 34500, 2),
(62, 2, 3, 7, 22700, 73100, 1),
(63, 2, 3, 38, 27300, 13100, 3),
(64, 2, 3, 64, 25000, -700, 4),
(65, 2, 4, 77, 26200, 8400, 3),
(66, 2, 4, 41, 21400, -11800, 4),
(67, 2, 4, 66, 28700, 45900, 2),
(68, 2, 4, 58, 23700, 77500, 1),
(69, 2, 5, 16, 23700, -10100, 4),
(70, 2, 5, 10, 21400, 24900, 3),
(71, 2, 5, 56, 26200, 67900, 1),
(72, 2, 5, 29, 28700, 37300, 2),
(73, 2, 6, 65, 25000, -8100, 4),
(74, 2, 6, 24, 27600, 71000, 1),
(75, 2, 6, 74, 22400, 17800, 3),
(76, 2, 6, 62, 25000, 39300, 2),
(77, 2, 7, 46, 26200, 17100, 3),
(78, 2, 7, 21, 26200, 69100, 1),
(79, 2, 7, 37, 26200, 36700, 2),
(80, 2, 7, 13, 21400, -2900, 4),
(81, 2, 8, 61, 25000, 34800, 2),
(82, 2, 8, 69, 22400, -18500, 4),
(83, 2, 8, 8, 30200, 96200, 1),
(84, 2, 8, 43, 22400, 7500, 3),
(85, 2, 9, 54, 23100, 72900, 1),
(86, 2, 9, 22, 20900, 14500, 3),
(87, 2, 9, 78, 28000, -2200, 4),
(88, 2, 9, 72, 28000, 34800, 2),
(89, 2, 10, 23, 26100, 92100, 1),
(90, 2, 10, 71, 21700, 14700, 3),
(91, 2, 10, 14, 26100, -12700, 4),
(92, 2, 10, 18, 26100, 25900, 2),
(93, 2, 11, 36, 21800, 44500, 2),
(94, 2, 11, 51, 26900, -18100, 4),
(95, 2, 11, 17, 29500, 16200, 3),
(96, 2, 11, 75, 21800, 77400, 1),
(97, 2, 12, 31, 22000, 95600, 1),
(98, 2, 12, 6, 24300, -20500, 4),
(99, 2, 12, 12, 29400, 10800, 3),
(100, 2, 12, 42, 24300, 34100, 2),
(101, 3, 1, 17, 26000, 14800, 3),
(102, 3, 1, 52, 24000, 84100, 1),
(103, 3, 1, 53, 24000, 26000, 2),
(104, 3, 1, 64, 26000, -4900, 4),
(105, 3, 2, 28, 25000, 65800, 1),
(106, 3, 2, 2, 25000, 35500, 2),
(107, 3, 2, 38, 25000, 21600, 3),
(108, 3, 2, 0, NULL, NULL, 0),
(109, 3, 3, 63, 23000, 88200, 1),
(110, 3, 3, 79, 25600, -16600, 4),
(111, 3, 3, 12, 30700, 35800, 2),
(112, 3, 3, 7, 20700, 12600, 3),
(113, 3, 4, 75, 21200, -3400, 0),
(114, 3, 4, 46, 33400, 72300, 0),
(115, 3, 4, 31, 21200, 12200, 0),
(116, 3, 4, 58, 24200, 38900, 0),
(117, 3, 5, 56, 23700, 13800, 3),
(118, 3, 5, 62, 23700, 28900, 2),
(119, 3, 5, 45, 23700, -4400, 4),
(120, 3, 5, 6, 28900, 81700, 1),
(121, 3, 6, 29, 25500, 82800, 1),
(122, 3, 6, 51, 27900, -15100, 4),
(123, 3, 6, 66, 25500, 37700, 2),
(124, 3, 6, 24, 21100, 14600, 3),
(125, 3, 7, 65, 25500, -18400, 4),
(126, 3, 7, 61, 21100, -1800, 3),
(127, 3, 7, 23, 23200, 91400, 1),
(128, 3, 7, 78, 30200, 48800, 2),
(129, 3, 8, 74, 23100, 92600, 1),
(130, 3, 8, 37, 25600, 39400, 2),
(131, 3, 8, 72, 28200, 15400, 3),
(132, 3, 8, 42, 23100, -27400, 4),
(133, 3, 9, 36, 23600, 18800, 3),
(134, 3, 9, 54, 23600, 2400, 4),
(135, 3, 9, 43, 26400, 69200, 1),
(136, 3, 9, 21, 26400, 29600, 2),
(137, 3, 10, 10, 21500, 18100, 3),
(138, 3, 10, 69, 23800, 2500, 4),
(139, 3, 10, 14, 30900, 33100, 2),
(140, 3, 10, 20, 23800, 66300, 1),
(141, 3, 11, 71, 24400, 26100, 2),
(142, 3, 11, 41, 24400, 600, 4),
(143, 3, 11, 8, 24400, 12900, 3),
(144, 3, 11, 16, 26800, 80400, 1),
(145, 3, 12, 13, 25000, 28200, 2),
(146, 3, 12, 18, 27500, 77500, 1),
(147, 3, 12, 22, 22500, 16700, 3),
(148, 3, 12, 80, 25000, -2400, 4),
(197, 4, 1, 31, 16900, 300, 4),
(198, 4, 1, 36, 31400, 30900, 2),
(199, 4, 1, 28, 24000, 74100, 1),
(200, 4, 1, 74, 27700, 14700, 3),
(201, 4, 2, 0, NULL, NULL, 0),
(202, 4, 2, 18, 25000, -11100, 4),
(203, 4, 2, 51, 25000, 24400, 2),
(204, 4, 2, 41, 25000, 93900, 1),
(205, 4, 3, 23, 25000, 26500, 2),
(206, 4, 3, 0, NULL, NULL, 0),
(207, 4, 3, 63, 25000, 74300, 1),
(208, 4, 3, 29, 25000, 15100, 3),
(209, 4, 4, 61, 25000, 15700, 2),
(210, 4, 4, 53, 25000, 127200, 1),
(211, 4, 4, 0, NULL, NULL, 0),
(212, 4, 4, 24, 25000, -2800, 3),
(213, 4, 5, 62, 19400, 38100, 2),
(214, 4, 5, 80, 26100, 8800, 3),
(215, 4, 5, 38, 28400, -11100, 4),
(216, 4, 5, 12, 26100, 84200, 1),
(217, 4, 6, 65, 27700, 16300, 3),
(218, 4, 6, 16, 21300, 32400, 2),
(219, 4, 6, 42, 23300, 3500, 4),
(220, 4, 6, 14, 27700, 67800, 1),
(221, 4, 7, 37, 23100, 37700, 2),
(222, 4, 7, 45, 25600, 20200, 3),
(223, 4, 7, 7, 20900, 78700, 1),
(224, 4, 7, 78, 30400, -16600, 4),
(225, 4, 8, 20, 21400, 70900, 1),
(226, 4, 8, 66, 26200, 2500, 4),
(227, 4, 8, 72, 28700, 15700, 3),
(228, 4, 8, 13, 23700, 30900, 2),
(229, 4, 9, 56, 23700, 18000, 3),
(230, 4, 9, 58, 13900, -17600, 4),
(231, 4, 9, 64, 33700, 84700, 1),
(232, 4, 9, 69, 28700, 34900, 2),
(233, 4, 10, 79, 26100, 32500, 2),
(234, 4, 10, 10, 21700, 82600, 1),
(235, 4, 10, 17, 28400, -5900, 4),
(236, 4, 10, 8, 23800, 10800, 3),
(237, 4, 11, 22, 25600, 34500, 2),
(238, 4, 11, 52, 25600, 80100, 1),
(239, 4, 11, 21, 23200, -11900, 4),
(240, 4, 11, 6, 25600, 17300, 3),
(241, 4, 12, 2, 32800, 71200, 1),
(242, 4, 12, 43, 23400, 17200, 3),
(243, 4, 12, 75, 14200, -3000, 4),
(244, 4, 12, 71, 29600, 34600, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `wish`
--

CREATE TABLE IF NOT EXISTS `wish` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `who` int(11) NOT NULL,
  `withWhom` int(11) NOT NULL,
  `done` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
