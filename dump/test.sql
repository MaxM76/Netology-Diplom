-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Сен 02 2019 г., 15:21
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
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `text` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `author_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `answers`
--

INSERT INTO `answers` (`id`, `question_id`, `text`, `author_id`) VALUES
(5, 11, 'Высоко', 1),
(11, 9, 'Ничего', 1),
(13, 8, 'Еще нет', 1),
(14, 10, 'Столько', 1),
(15, 12, 'Очень далеко', 1),
(16, 3, 'Потому что вот так!', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `text` text NOT NULL,
  `author_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_published` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `questions`
--

INSERT INTO `questions` (`id`, `topic_id`, `text`, `author_id`, `is_published`, `status`) VALUES
(1, 3, 'Есть ли жизнь на Марсе?', 1, 1, 0),
(3, 3, 'Почему?', 1, 0, 1),
(5, 4, 'Сколько?', 1, 0, 0),
(6, 4, 'Ну и что?', 1, 0, 0),
(8, 1, 'Есть ли жизнь на Марсе?', 1, 0, 1),
(9, 1, 'Ну и что?', 1, 1, 1),
(10, 1, 'Сколько?', 1, 0, 1),
(11, 2, 'Высоко ли? А?', 1, 1, 1),
(12, 2, 'Далеко ли?', 1, 1, 1),
(13, 3, '&', 9, 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `topics`
--

CREATE TABLE `topics` (
  `id` int(11) NOT NULL,
  `text` tinytext NOT NULL,
  `description` text NOT NULL,
  `author_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `topics`
--

INSERT INTO `topics` (`id`, `text`, `description`, `author_id`) VALUES
(1, 'Общие вопросы', 'Вопросы общего характера!', 1),
(2, 'Разные', 'Вопросы, не попадающие в существующие категории', 1),
(3, 'Злободневные', 'Топик говорит сам за себя', 1),
(4, 'Новая', 'Новая категория', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `login` tinytext NOT NULL,
  `password` tinytext NOT NULL,
  `type` int(11) NOT NULL,
  `email` tinytext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `type`, `email`) VALUES
(1, 'admin', 'admin', 0, 'm.v.markelov@mail.ru'),
(2, 'login1', 'password2', 1, 'm.v.markelov@mail.ru'),
(4, 'max', 'qwerty', 1, 'm.v.markelov@mail.ru'),
(8, 'max1234', '111', 1, 'm.v.markelov@mail.ru'),
(9, 'dfgdddd', '', 2, 'm.v.markelov@mail.ru'),
(11, 'quest1', '', 2, 'm.v.markelov@mail.ru'),
(12, 'quest2', '', 2, 'm.v.markv@mail.ru');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `question_id` (`question_id`),
  ADD KEY `author_id` (`author_id`);

--
-- Индексы таблицы `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `topic_id` (`topic_id`),
  ADD KEY `author_id` (`author_id`);

--
-- Индексы таблицы `topics`
--
ALTER TABLE `topics`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `author_id` (`author_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `answers`
--
ALTER TABLE `answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT для таблицы `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT для таблицы `topics`
--
ALTER TABLE `topics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `answers_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `answers_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`);

--
-- Ограничения внешнего ключа таблицы `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`),
  ADD CONSTRAINT `questions_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `topics`
--
ALTER TABLE `topics`
  ADD CONSTRAINT `topics_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
