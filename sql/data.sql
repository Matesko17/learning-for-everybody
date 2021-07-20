-- --------------------------------------------------------
-- Hostitel:                     127.0.0.1
-- Verze serveru:                10.1.19-MariaDB - mariadb.org binary distribution
-- OS serveru:                   Win32
-- HeidiSQL Verze:               9.5.0.5196
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Exportování dat pro tabulku nw24.horybory_language: ~1 rows (přibližně)
/*!40000 ALTER TABLE `horybory_language` DISABLE KEYS */;
INSERT IGNORE INTO `horybory_language` (`id`, `code`, `name`) VALUES
	(1, 'cs', 'Čeština');
/*!40000 ALTER TABLE `horybory_language` ENABLE KEYS */;

-- Exportování dat pro tabulku nw24.horybory_menu: ~2 rows (přibližně)
/*!40000 ALTER TABLE `horybory_menu` DISABLE KEYS */;
INSERT IGNORE INTO `horybory_menu` (`id`, `ident`) VALUES
	(1, 'main'),
	(2, 'left-menu');
/*!40000 ALTER TABLE `horybory_menu` ENABLE KEYS */;

-- Exportování dat pro tabulku nw24.horybory_menu_item: ~14 rows (přibližně)
/*!40000 ALTER TABLE `horybory_menu_item` DISABLE KEYS */;
INSERT IGNORE INTO `horybory_menu_item` (`id`, `menu_id`, `parent_id`, `blank`, `dropdown`, `order`, `tree_access_rights`, `created_at`, `updated_at`) VALUES
	(1, 1, NULL, NULL, 0, 1, 0, NULL, NULL),
	(2, 1, NULL, NULL, 0, 3, NULL, NULL, NULL),
	(3, 1, NULL, NULL, 0, 10, NULL, NULL, NULL),
	(4, 2, NULL, NULL, 0, 12, NULL, NULL, NULL),
	(5, 2, NULL, NULL, 0, 13, NULL, NULL, NULL),
	(6, 2, 4, NULL, 0, 15, NULL, NULL, NULL),
	(7, 1, NULL, NULL, 1, 16, NULL, NULL, NULL),
	(8, 1, 7, NULL, NULL, 2, NULL, NULL, NULL),
	(9, 1, 7, 1, 0, 3, NULL, NULL, NULL),
	(10, 1, NULL, NULL, 0, 4, NULL, NULL, NULL),
	(11, 1, NULL, 0, 0, 19, NULL, NULL, NULL),
	(12, 1, NULL, 0, 0, 20, NULL, NULL, NULL),
	(13, 1, NULL, 0, 0, 21, NULL, NULL, NULL),
	(14, 1, 8, 1, 0, 3, NULL, NULL, NULL);
/*!40000 ALTER TABLE `horybory_menu_item` ENABLE KEYS */;

-- Exportování dat pro tabulku nw24.horybory_menu_item_translation: ~14 rows (přibližně)
/*!40000 ALTER TABLE `horybory_menu_item_translation` DISABLE KEYS */;
INSERT IGNORE INTO `horybory_menu_item_translation` (`id`, `route_alias_id`, `translatable_id`, `title`, `link`, `anchor`, `locale`, `created_at`, `updated_at`) VALUES
	(2, NULL, 1, 'Galerie', NULL, '#pardubice', 'cs', NULL, NULL),
	(3, NULL, 2, 'Novinky', NULL, NULL, 'cs', NULL, NULL),
	(4, NULL, 3, 'Kontakt', NULL, '#kontakt', 'cs', NULL, NULL),
	(5, NULL, 4, 'Levé menu', NULL, NULL, 'cs', NULL, NULL),
	(6, NULL, 5, 'Řešení stropů, balkónů', NULL, NULL, 'cs', NULL, NULL),
	(7, NULL, 6, 'Levé menu 1', NULL, NULL, 'cs', NULL, NULL),
	(8, NULL, 7, 'Dropdown page', NULL, NULL, 'cs', NULL, NULL),
	(9, NULL, 8, 'dropdown 1', NULL, NULL, 'cs', NULL, NULL),
	(10, NULL, 9, 'dropdown 2', 'http://q2.cz', '#Content', 'cs', NULL, NULL),
	(11, NULL, 10, 'Typografie', NULL, NULL, 'cs', NULL, NULL),
	(12, NULL, 11, 'Vytvoření stránky', NULL, NULL, 'cs', NULL, NULL),
	(13, NULL, 12, 'Vytvoření aktuality', NULL, NULL, 'cs', NULL, NULL),
	(14, NULL, 13, 'Vytvoření galerie', NULL, NULL, 'cs', NULL, NULL),
	(15, NULL, 14, 'dropdown 3', NULL, NULL, 'cs', NULL, NULL);
/*!40000 ALTER TABLE `horybory_menu_item_translation` ENABLE KEYS */;

-- Exportování dat pro tabulku nw24.horybory_page: ~2 rows (přibližně)
/*!40000 ALTER TABLE `horybory_page` DISABLE KEYS */;
INSERT IGNORE INTO `horybory_page` (`id`, `ident`, `created_at`, `updated_at`) VALUES
	(1, 'mycustompage', NULL, NULL),
	(2, 'test', NULL, NULL);
/*!40000 ALTER TABLE `horybory_page` ENABLE KEYS */;

-- Exportování dat pro tabulku nw24.horybory_page_translation: ~8 rows (přibližně)
/*!40000 ALTER TABLE `horybory_page_translation` DISABLE KEYS */;
INSERT IGNORE INTO `horybory_page_translation` (`id`, `translatable_id`, `title`, `text`, `visible`, `locale`, `created_at`, `updated_at`) VALUES
	(1, 1, 'title', '<p>sdfsfddsa vvvvv</p>\r\n', 1, 'cs', NULL, NULL),
	(2, 1, 'English', '<p>gru gru i am glum</p>\r\n', 1, 'en', NULL, NULL),
	(3, 1, 'Deutsche + sdfdsfs xxxxx', '<p>gru gru ich bin glum</p>\r\n', 1, 'de', NULL, NULL),
	(5, 2, 'test image', '<p>czech csss dsf dsafasdfdsafasdf asd fas</p>\r\n', 1, 'cs', NULL, NULL),
	(6, 2, 'test', '<p>x</p>\r\n', 1, 'en', NULL, NULL),
	(7, 2, 'test', '<p>deutsche dsf dsfa sdafdsa fsad fsad</p>\r\n', 1, 'de', NULL, NULL),
	(8, 2, 'test', '<p>rus d</p>\r\n', 1, 'ru', NULL, NULL),
	(10, 1, 'Russian custom page', '<p>gru gru po ruski neponemajem</p>\r\n', 1, 'ru', NULL, NULL);
/*!40000 ALTER TABLE `horybory_page_translation` ENABLE KEYS */;

-- Exportování dat pro tabulku nw24.horybory_route: ~4 rows (přibližně)
/*!40000 ALTER TABLE `horybory_route` DISABLE KEYS */;
INSERT IGNORE INTO `horybory_route` (`id`, `presenter`, `action`) VALUES
	(3, 'Demo', 'default'),
	(1, 'News', 'default'),
	(4, 'Page', 'default'),
	(2, 'Poll', 'default');
/*!40000 ALTER TABLE `horybory_route` ENABLE KEYS */;

-- Exportování dat pro tabulku nw24.horybory_route_alias: ~6 rows (přibližně)
/*!40000 ALTER TABLE `horybory_route_alias` DISABLE KEYS */;
INSERT IGNORE INTO `horybory_route_alias` (`id`, `route_id`, `language`, `slug`, `item`, `parameters`, `deleted`, `added`) VALUES
	(3, 4, 'cs', 'title', 1, NULL, NULL, '2018-12-03 17:38:12'),
	(4, 4, 'en', 'english', 1, NULL, NULL, '2018-12-03 17:38:27'),
	(5, 4, 'sk', 'english', 1, NULL, NULL, '2018-12-03 17:41:24'),
	(6, 4, 'es', 'english', 1, NULL, NULL, '2018-12-03 17:41:29'),
	(7, 4, 'de', 'deutsche-sdfdsfs-xxxxx', 1, NULL, NULL, '2018-12-03 17:41:30'),
	(8, 4, 'pl', 'english', 1, NULL, NULL, '2018-12-03 17:41:34');
/*!40000 ALTER TABLE `horybory_route_alias` ENABLE KEYS */;

-- Exportování dat pro tabulku nw24.horybory_route_seo: ~0 rows (přibližně)
/*!40000 ALTER TABLE `horybory_route_seo` DISABLE KEYS */;
/*!40000 ALTER TABLE `horybory_route_seo` ENABLE KEYS */;

-- Exportování dat pro tabulku nw24.horybory_site_setting: ~13 rows (přibližně)
/*!40000 ALTER TABLE `horybory_site_setting` DISABLE KEYS */;
INSERT IGNORE INTO `horybory_site_setting` (`id`, `key`, `value`, `lang`) VALUES
	(1, 'mainLang', 'cs', NULL),
	(2, 'allowLang', '{"cs":"česky","en":"english","sk":"slovenština","es":"espanol","de":"Deutsch", "pl":"Polski"}', NULL),
	(3, 'aliasLang', '{"de":"en","sk":"cs","fr":"en"}', NULL),
	(4, 'homeTitle', 'Netteweb v.2.4.1 new', 'cs'),
	(5, 'homeDesc', 'nový netteweb ve verzi 2.4.1', 'cs'),
	(6, 'homeTitle', 'Netteweb v.2.4.1 en verze', 'en'),
	(7, 'homeDesc', 'New netteweb in version 2.4.1', 'en'),
	(9, 'separator', '|', NULL),
	(10, 'ga', '123456', NULL),
	(11, 'gtm', '654321', NULL),
	(12, 'titleSuffix', 'Q2 interactive', 'cs'),
	(13, 'headAuthor', 'Q2 Interactive | info@q2.cz | www.q2.cz', 'cs'),
	(15, 'headRobots', 'all,follow', 'cs');
/*!40000 ALTER TABLE `horybory_site_setting` ENABLE KEYS */;

-- Exportování dat pro tabulku nw24.horybory_text: ~0 rows (přibližně)
/*!40000 ALTER TABLE `horybory_text` DISABLE KEYS */;
INSERT IGNORE INTO `horybory_text` (`id`, `ident`, `created_at`, `updated_at`) VALUES
	(1, 'test', NULL, NULL);
/*!40000 ALTER TABLE `horybory_text` ENABLE KEYS */;

-- Exportování dat pro tabulku nw24.horybory_text_translation: ~0 rows (přibližně)
/*!40000 ALTER TABLE `horybory_text_translation` DISABLE KEYS */;
INSERT IGNORE INTO `horybory_text_translation` (`id`, `translatable_id`, `title`, `content`, `locale`, `created_at`, `updated_at`) VALUES
	(1, 1, 'název', '<b>obsah</b>', 'cs', NULL, NULL),
	(4, 1, 'title', '<b>content</b>', 'en', NULL, NULL);
/*!40000 ALTER TABLE `horybory_text_translation` ENABLE KEYS */;

-- Exportování dat pro tabulku nw24.horybory_translation: ~0 rows (přibližně)
/*!40000 ALTER TABLE `horybory_translation` DISABLE KEYS */;
/*!40000 ALTER TABLE `horybory_translation` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
