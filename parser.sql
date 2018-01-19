-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Янв 20 2018 г., 00:25
-- Версия сервера: 5.6.38
-- Версия PHP: 5.6.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `parser`
--

-- --------------------------------------------------------

--
-- Структура таблицы `html`
--

CREATE TABLE `html` (
  `id` int(11) NOT NULL,
  `site_name` varchar(255) NOT NULL,
  `pure_site_link` varchar(255) NOT NULL,
  `pure_site_link_chk` tinyint(1) NOT NULL DEFAULT '0',
  `product_description` text NOT NULL,
  `xpath_img` varchar(255) NOT NULL,
  `xpath_main_img` varchar(255) NOT NULL,
  `xpath_title` varchar(255) NOT NULL,
  `xpath_price` varchar(255) NOT NULL,
  `parse_link` varchar(255) NOT NULL,
  `xpath_product_link` varchar(255) NOT NULL,
  `xpath_product_description` varchar(255) NOT NULL,
  `product_category` varchar(255) NOT NULL,
  `product_url_category` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `html`
--

INSERT INTO `html` (`id`, `site_name`, `pure_site_link`, `pure_site_link_chk`, `product_description`, `xpath_img`, `xpath_main_img`, `xpath_title`, `xpath_price`, `parse_link`, `xpath_product_link`, `xpath_product_description`, `product_category`, `product_url_category`) VALUES
(1, 'MBM', 'http://mvm.ua', 1, '<h3><strong>Дверная ручка на розетке MVM {$goods}</strong></h3><p> </p><p>    Ручка – самый значимый аксессуар, который может внести новый штрих в общий вид. При выборе ручки, следует помнить, что ее цвет должен сочетаться с цветом фурнитуры мебельных гарнитуров жилища, а дизайн ручки с дизайном двери. Руководствоваться так же можно и тактильными ощущениями – буквально потрогать все предлагаемые вам ручки.</p><p>    Фурнитура <em>MVM {$goods}</em> изготовлена из высококачественного цинкового сплава и алюминия, основными характеристиками которых есть стойкость к коррозии, высокая прочность при температурных колебаниях, нетоксичность (используется в пищевой промышленности).</p><p>    Ручки могут устанавливаться на входные/межкомнатные двери, подходят для общественного использования с повышенными гигиеническими требованиями.</p><p><strong>Комплектация:</strong></p><ol><li>Ручки дверные - 1 пара</li><li>Квадрат соединительный 8х100мм - 1 шт</li><li>Комплект для крепления с шестигранным ключом - 1 шт</li></ol><p><strong>Дополнительные рекомендации:</strong></p><ol><li>Не чистить изделия абразивными средствами, в случае необходимости протирать сухой мягкой салфеткой.</li><li>К монтажу хранить в оригинальной упаковке производителя.</li><li>Для создания единого стиля рекомендуем комплектовать ручки фурнитурой от компании МВМ.</li></ol>', 'div#galleria-content div.item-list ul.gallery>li>img', 'null', 'div.views-field-title span.field-content>a', 'null', 'http://mvm.ua/ru/category/ruchki-na-planke/ruchka-dlya-metallicheskikh-dverei', 'div.views-field-title span.field-content>a', 'null', 'Дверная фурнитура/MBM/Ручки на розетке', 'door-furniture/mbm/ruchki-na-rozetke'),
(2, 'KEDR', 'http://www.kedr-locks.com/', 0, '<h3><strong>Дверная ручка на розетке MVM&nbsp;{$goods}</strong></h3><p>&nbsp;</p><p>&nbsp; &nbsp; Ручка – самый значимый аксессуар, который может внести новый штрих в общий вид. При выборе ручки, следует помнить, что ее цвет должен сочетаться с цветом фурнитуры мебельных гарнитуров жилища, а дизайн ручки с дизайном двери. Руководствоваться так же можно и тактильными ощущениями – буквально потрогать все предлагаемые вам ручки.</p><p>&nbsp; &nbsp; Фурнитура <em>MVM {$goods}</em>&nbsp;изготовлена из высококачественного цинкового сплава и алюминия, основными характеристиками которых есть стойкость к коррозии, высокая прочность при температурных колебаниях, нетоксичность (используется в пищевой промышленности).</p><p>&nbsp; &nbsp; Ручки могут устанавливаться на входные/межкомнатные&nbsp;двери, подходят для общественного использования с повышенными гигиеническими требованиями.</p><p><strong>Комплектация:</strong></p><ol><li>Ручки дверные - 1 пара</li><li>Квадрат соединительный 8х100мм - 1 шт</li><li>Комплект для крепления с шестигранным ключом - 1 шт</li></ol><p><strong>Дополнительные рекомендации:</strong></p><ol><li>Не чистить изделия абразивными средствами, в случае необходимости протирать сухой мягкой салфеткой.</li><li>К монтажу хранить в оригинальной упаковке производителя.</li><li>Для создания единого стиля рекомендуем комплектовать ручки фурнитурой от компании МВМ.</li></ol>', 'div#galleria-content div.item-list ul.gallery>li>img', '', 'div.views-field-title span.field-content>a', 'div.views-field-title span.field-content>a', 'http://mvm.ua/ru/category/ruchki-na-rozetke?page=8', 'div.views-field-title span.field-content>a', '', 'Дверная фурнитура/MBM/Ручки на розетке', 'door-furniture/mbm/ruchki-na-rozetke'),
(3, 'Mega Bit', 'https://reg.megabitcomp.ru', 1, '<table class=\"js-tabHeight tableDetails \" itemprop=\"description\"><tbody><tr><td>Тип</td><td>телефон</td></tr><tr><td>Тип корпуса</td><td>классический</td></tr><tr><td>Материал корпуса</td><td>пластик</td></tr><tr><td>Количество SIM-карт</td><td>2</td></tr><tr><td>Режим работы нескольких SIM-карт</td><td>попеременный</td></tr><tr><td>Вес</td><td>65 г</td></tr><tr><td>Размеры (ШxВxТ)</td><td>45x107x14 мм</td></tr><tr><td>Тип экрана</td><td>цветной TFT</td></tr><tr><td>Диагональ</td><td>1.77 дюйм.</td></tr><tr><td>Размер изображения</td><td>160x128</td></tr><tr><td>Число пикселей на дюйм (PPI)</td><td>116</td></tr><tr><td>Тип мелодий</td><td>полифонические</td></tr><tr><td>Тыловая фотокамера</td><td>нет</td></tr><tr><td>Аудио</td><td>FM-радио</td></tr><tr><td>Стандарт</td><td>GSM 900/1800</td></tr><tr><td>Доступ в интернет</td><td>нет</td></tr><tr><td>Интерфейсы</td><td>Bluetooth 2.1</td></tr><tr><td>Объем встроенной памяти</td><td>32 Мб</td></tr><tr><td>Слот для карт памяти</td><td>есть, объемом до 8 Гб</td></tr><tr><td>Тип аккумулятора</td><td>Li-Ion</td></tr><tr><td>Емкость аккумулятора</td><td>600 мА⋅ч</td></tr><tr><td>Тип разъема для зарядки</td><td>micro-USB</td></tr><tr><td>Органайзер</td><td>будильник</td></tr></tbody></table>', 'ul#lightSliderTumb li.thumbnail img', 'div.mainInfo div.fotorama_new div.fotorama_new__container div.fotorama_new__item a.mainPic img', 'div.nd__masView div.nd__masView--item div.nd__masView--item--contain div.nd__masView--about a.nd__masView--name', 'div.nd__masView div.nd__masView--item div.nd__masView--item--contain div.nd__masView--priceWrap div.nd__mainPrice', 'https://reg.megabitcomp.ru/catalog/mobilnye_telefony/?page=2', 'div.nd__masView div.nd__masView--item div.nd__masView--item--contain a.nd__masView--pic', 'div.newDes__tabContainer div.wts div.newDes__tabMinHeight table', 'Мобильные телефоны, смартфоны', 'mobilnye_telefony/267558/'),
(4, 'Ecostyle', 'http://ecostyle.pp.ua', 0, 'Test 11', 'Test 5', 'Test 6', 'Test 3', 'Test 4', 'http://ecostyle.pp.ua/our-works', 'Test 7', 'Test 8', 'Test 9', 'Test 10'),
(10, 'MBM4', 'http://parser.loc/', 0, 'fhfhfghfghfg', 'ul#lightSliderTumb>li.thumbnail>img', 'Test 6', '//*[@id=\'ajax_content\']/div[3]/div[10]/div/div[3]/a', '//*[@id=\'ajax_content\']/div[3]/div[10]/div/div[4]/div[2]', 'https://reg.megabitcomp.ru/catalog/mobilnye_telefony/?page=2', 'div.nd__masView--item--contain>a.nd__masView--pic', 'Test 8', 'Мобильная связь и телефония / Мобильные телефоны, смартфоны', '9'),
(11, 'MBM', 'http://parser.loc/', 1, '11111', 'ul#lightSliderTumb>li.thumbnail>img', 'null', '//*[@id=\'ajax_content\']/div[3]/div[10]/div/div[3]/a', '//*[@id=\'ajax_content\']/div[3]/div[10]/div/div[4]/div[2]', 'https://reg.megabitcomp.ru/catalog/mobilnye_telefony/?page=2', '7', 'null', '8', '9');

-- --------------------------------------------------------

--
-- Структура таблицы `sites`
--

CREATE TABLE `sites` (
  `id` int(11) NOT NULL,
  `site_id` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `sites`
--

INSERT INTO `sites` (`id`, `site_id`) VALUES
(1, 2);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `html`
--
ALTER TABLE `html`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `html`
--
ALTER TABLE `html`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
