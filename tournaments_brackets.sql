-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Янв 09 2024 г., 21:21
-- Версия сервера: 8.0.30
-- Версия PHP: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `lfl`
--

-- --------------------------------------------------------

--
-- Структура таблицы `tournaments_brackets`
--

CREATE TABLE `tournaments_brackets` (
  `id` int NOT NULL,
  `tournament_id` int NOT NULL,
  `tour_number` int NOT NULL,
  `match_number` int NOT NULL,
  `match_id` int DEFAULT NULL,
  `team_home_id` int DEFAULT NULL,
  `team_away_id` int DEFAULT NULL,
  `is_champion` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `tournaments_brackets`
--

INSERT INTO `tournaments_brackets` (`id`, `tournament_id`, `tour_number`, `match_number`, `match_id`, `team_home_id`, `team_away_id`, `is_champion`) VALUES
(1, 17516, 1, 1, NULL, NULL, NULL, 0),
(2, 17516, 1, 2, NULL, NULL, NULL, 0),
(3, 17516, 1, 3, NULL, NULL, NULL, 0),
(4, 17516, 1, 4, NULL, NULL, NULL, 0),
(5, 17516, 2, 1, NULL, NULL, NULL, 0),
(6, 17516, 2, 2, NULL, NULL, NULL, 0),
(7, 17516, 3, 1, NULL, NULL, NULL, 0),
(8, 17516, 4, 1, NULL, NULL, NULL, 0),
(9, 17516, 1, 1, NULL, NULL, NULL, 0);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `tournaments_brackets`
--
ALTER TABLE `tournaments_brackets`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `tournaments_brackets`
--
ALTER TABLE `tournaments_brackets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
