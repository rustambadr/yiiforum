-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:8888
-- Время создания: Мар 30 2020 г., 17:39
-- Версия сервера: 5.7.21
-- Версия PHP: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `yii_forum`
--

-- --------------------------------------------------------

--
-- Структура таблицы `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `alias` varchar(256) NOT NULL,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `image` varchar(256) NOT NULL,
  `date_create` datetime NOT NULL,
  `role_view` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `category`
--

INSERT INTO `category` (`id`, `parent_id`, `alias`, `title`, `content`, `image`, `date_create`, `role_view`) VALUES
(4, 0, 'belye_uslugi', 'Белые услуги', '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>', 'images/upload/db96861c883548278d53359b190f9d48.png', '2020-03-11 09:45:00', 1),
(5, 0, 'serye_uslugi', 'Серые услуги', '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>', 'images/upload/bfda530bc7b0d31829bac0cc42d6b13a.jpg', '2020-03-16 00:00:00', 1),
(6, 0, 'cernye_uslugi', 'Черные услуги', '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>', 'images/upload/195baca8bf372045a90bc0cde38af775.png', '2020-03-16 00:00:00', 1),
(7, 0, 'privatnye_temy', 'Приватные темы', '<p>Приватный раздел</p>', 'images/upload/ddc745f3aa7ce460e57b16d406f0cb39.png', '2020-03-25 13:44:00', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `id_owner` int(11) NOT NULL,
  `id_thread` int(11) NOT NULL,
  `comment` text NOT NULL,
  `date_create` datetime NOT NULL,
  `type` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `dialog`
--

CREATE TABLE `dialog` (
  `id` int(11) NOT NULL,
  `user_ids` text NOT NULL,
  `date_update` datetime NOT NULL,
  `enable` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `dialog`
--

INSERT INTO `dialog` (`id`, `user_ids`, `date_update`, `enable`) VALUES
(1, '{\"u1\":1,\"u2\":\"2\"}', '2020-03-30 16:51:22', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `id_dialog` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `message` varchar(8192) NOT NULL,
  `date_create` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `message`
--

INSERT INTO `message` (`id`, `id_dialog`, `id_user`, `message`, `date_create`) VALUES
(1, 1, 1, '<p>Привет</p>', '2020-03-30 16:51:29'),
(2, 1, 2, '<p>Привет</p>', '2020-03-30 16:51:37');

-- --------------------------------------------------------

--
-- Структура таблицы `page`
--

CREATE TABLE `page` (
  `id` int(11) NOT NULL,
  `title` varchar(256) NOT NULL,
  `alias` varchar(256) NOT NULL,
  `content` text NOT NULL,
  `date_create` datetime NOT NULL,
  `icon` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `thread`
--

CREATE TABLE `thread` (
  `id` int(11) NOT NULL,
  `title` varchar(256) NOT NULL,
  `color` text NOT NULL,
  `color_text` text NOT NULL,
  `content` text NOT NULL,
  `alias` varchar(256) NOT NULL,
  `id_owner` int(11) NOT NULL,
  `id_category` int(11) NOT NULL,
  `date_create` datetime NOT NULL,
  `date_update` datetime NOT NULL,
  `role_view` int(11) NOT NULL,
  `comment_count` int(11) NOT NULL,
  `allow_comment_ids` text NOT NULL,
  `allow_view_ids` text NOT NULL,
  `enable` int(1) NOT NULL,
  `type` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `thread`
--

INSERT INTO `thread` (`id`, `title`, `color`, `color_text`, `content`, `alias`, `id_owner`, `id_category`, `date_create`, `date_update`, `role_view`, `comment_count`, `allow_comment_ids`, `allow_view_ids`, `enable`, `type`) VALUES
(1, 'Тестовая тема', '', '', '<p>Тест</p><p><span style=\"background-color: rgb(247, 150, 70);\">1</span></p><p><span style=\"background-color: rgb(247, 150, 70);\">2</span></p><p><span style=\"background-color: rgb(247, 150, 70);\">3</span></p>', 'testovaa_tema', 2, 4, '2020-03-30 16:50:00', '2020-03-30 16:50:00', 1, 0, '', '', 1, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `email` varchar(256) NOT NULL,
  `image` varchar(256) NOT NULL,
  `password` varchar(64) NOT NULL,
  `date_create` datetime NOT NULL,
  `role` int(1) NOT NULL,
  `about` varchar(8192) NOT NULL,
  `unread_message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `image`, `password`, `date_create`, `role`, `about`, `unread_message`) VALUES
(1, 'Администратор', 'admin@mail.ru', 'images/upload/41a1efc983b4f04a310ad300e97138ba.png', '5f4dcc3b5aa765d61d8327deb882cf99', '2020-03-30 16:46:40', 6, '<p>Гарант</p>', ''),
(2, 'User', 'user@mail.ru', '', '5f4dcc3b5aa765d61d8327deb882cf99', '2020-03-30 16:49:30', 1, '', '');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `dialog`
--
ALTER TABLE `dialog`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `page`
--
ALTER TABLE `page`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `thread`
--
ALTER TABLE `thread`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `dialog`
--
ALTER TABLE `dialog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `page`
--
ALTER TABLE `page`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `thread`
--
ALTER TABLE `thread`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
