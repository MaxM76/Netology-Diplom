-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Июл 15 2019 г., 16:44
-- Версия сервера: 8.0.15
-- Версия PHP: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `mmarkelov`
--

-- --------------------------------------------------------

--
-- Структура таблицы `answers`
--

CREATE TABLE `answers` (
  `answer_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `text` text NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `author` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `answers`
--

INSERT INTO `answers` (`answer_id`, `question_id`, `text`, `author`) VALUES
(5, 11, 'Высоко', 1),
(6, 6, 'Ничего', 1),
(11, 9, 'Ничего', 1),
(13, 8, 'Еще нет', 1),
(14, 10, 'Столько', 1),
(15, 12, 'Очень далеко', 1),
(16, 3, 'Потому что вот так!', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `faqusers`
--

CREATE TABLE `faqusers` (
  `user_id` int(11) NOT NULL,
  `login` tinytext NOT NULL,
  `password` tinytext NOT NULL,
  `type` int(11) NOT NULL,
  `mail` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `faqusers`
--

INSERT INTO `faqusers` (`user_id`, `login`, `password`, `type`, `mail`) VALUES
(1, 'admin', 'admin', 0, 'm.v.markelov@mail.ru'),
(2, 'login1', 'password2', 1, 'm.v.markelov@mail.ru'),
(4, 'max', 'qwerty', 1, 'm.v.markelov@mail.ru'),
(8, 'max1234', '111', 1, 'm.v.markelov@mail.ru'),
(9, 'dfgdddd', '', 2, 'm.v.markelov@mail.ru');

-- --------------------------------------------------------

--
-- Структура таблицы `questions`
--

CREATE TABLE `questions` (
  `question_id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `text` text NOT NULL,
  `author` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `published` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `questions`
--

INSERT INTO `questions` (`question_id`, `topic_id`, `text`, `author`, `published`, `status`) VALUES
(1, 3, 'Есть ли жизнь на Марсе?', 1, 0, 0),
(3, 3, 'Почему?', 1, 0, 1),
(5, 4, 'Сколько?', 1, 0, 1),
(6, 4, 'Ну и что?', 1, 0, 1),
(8, 1, 'Есть ли жизнь на Марсе?', 1, 0, 1),
(9, 1, 'Ну и что?', 1, 1, 1),
(10, 1, 'Сколько?', 1, 0, 1),
(11, 2, 'Высоко ли? А?', 1, 1, 1),
(12, 2, 'Далеко ли?', 1, 1, 1),
(13, 3, '&', 9, 1, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `topics`
--

CREATE TABLE `topics` (
  `topic_id` int(11) NOT NULL,
  `text` tinytext NOT NULL,
  `description` text NOT NULL,
  `author` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `topics`
--

INSERT INTO `topics` (`topic_id`, `text`, `description`, `author`) VALUES
(1, 'Общие вопросы', 'Вопросы общего характера!', 1),
(2, 'Разные', 'Вопросы, не попадающие в существующие категории', 1),
(3, 'Злободневные', 'Топик говорит сам за себя', 1),
(4, 'Новая', 'Новая категория', 1);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`answer_id`);

--
-- Индексы таблицы `faqusers`
--
ALTER TABLE `faqusers`
  ADD PRIMARY KEY (`user_id`);

--
-- Индексы таблицы `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`question_id`);

--
-- Индексы таблицы `topics`
--
ALTER TABLE `topics`
  ADD PRIMARY KEY (`topic_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `answers`
--
ALTER TABLE `answers`
  MODIFY `answer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT для таблицы `faqusers`
--
ALTER TABLE `faqusers`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `questions`
--
ALTER TABLE `questions`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT для таблицы `topics`
--
ALTER TABLE `topics`
  MODIFY `topic_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
