<?php
/**
 * Bel-CMS [Content management system]
 * @version 0.0.1
 * @link http://www.bel-cms.be
 * @link http://www.stive.eu
 * @license http://opensource.org/licenses/GPL-3.0 copyleft
 * @copyright 2014-2016 Bel-CMS
 * @author Stive - mail@stive.eu
 */

$error      = true;
$class      = null;
$insert     = null;
$sql        = null;

switch ($table) {
	case 'config':
		$drop = 'DROP TABLE IF EXISTS `'.$_SESSION['prefix'].$table.'`';
		$sql  = "CREATE TABLE IF NOT EXISTS `".$_SESSION['prefix'].$table."` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`name` varchar(32) NOT NULL,
			`value` text NOT NULL,
			PRIMARY KEY (`id`),
			UNIQUE KEY `name` (`name`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
		$insert = "INSERT INTO `".$_SESSION['prefix'].$table."` (`id`, `name`, `value`) VALUES
			(NULL, 'CMS_WEBSITE_NAME', ''),
			(NULL, 'CMS_TPL_WEBSITE', ''),
			(NULL, 'CMS_WEBSITE_DESCRIPTION', ''),
			(NULL, 'CMS_REGISTER_CHARTER', 'En poursuivant votre navigation sur ce site, vous acceptez nos conditions générales d\'utilisation et notamment que des cookies soient utilisés afin de vous connecter automatiquement.'),
			(NULL, 'CMS_MAIL_WEBSITE', ''),
			(NULL, 'CMS_WEBSITE_KEYWORDS', ''),
			(NULL, 'CMS_WEBSITE_LANG', 'fr'),
			(NULL, 'CMS_TPL_FULL', 'readmore, user, forum, downloads, members, page'),
			(NULL, 'BELCMS_DEBUG', '0'),
			(NULL, 'CMS_MAIL', ''),
			(NULL, 'CMS_JQUERY', 'on'),
			(NULL, 'CMS_JQUERY_UI', 'on'),
			(NULL, 'CMS_BOOTSTRAP', 'on');";
	break;

	case 'config_pages':
		$drop = 'DROP TABLE IF EXISTS `'.$_SESSION['prefix'].$table.'`';
		$sql  = "CREATE TABLE IF NOT EXISTS `".$_SESSION['prefix'].$table."` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`name` varchar(50) NOT NULL,
			`active` tinyint(1) NOT NULL,
			`access_groups` text NOT NULL,
			`access_admin` text NOT NULL,
			`config` text,
			PRIMARY KEY (`id`),
			UNIQUE KEY `name` (`name`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
		$insert = "INSERT INTO `".$_SESSION['prefix'].$table."` (`id`, `name`, `active`, `access_groups`, `access_admin`, `config`) VALUES
			(NULL, 'blog', 1, '#', '2|3', 'MAX_NEWS=3'),
			(NULL, 'user', 1, '#', '2|4', 'MAX_USER=1|MAX_TEST=1'),
			(NULL, 'forum', 1, '9', '3', NULL),
			(NULL, 'shoutbox', 1, '1', '2|3', 'MAX_MSG=25');";
	break;
	case 'groups':
		$drop = 'DROP TABLE IF EXISTS `'.$_SESSION['prefix'].$table.'`';
		$sql  = "CREATE TABLE IF NOT EXISTS `".$_SESSION['prefix'].$table."` (
			`id` int(10) NOT NULL AUTO_INCREMENT,
			`name` varchar(32) NOT NULL,
			`id_group` int(2) NOT NULL,
			PRIMARY KEY (`id`),
			UNIQUE KEY `name` (`name`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
		$insert = "INSERT INTO `".$_SESSION['prefix'].$table."`
 (`id`, `name`, `id_group`) VALUES
			('', 'Administrateur', 1),
			('', 'Membres', 2);";
	break;
	case 'mails_blacklist':
		$drop = 'DROP TABLE IF EXISTS `'.$_SESSION['prefix'].$table.'`';
		$sql  = "CREATE TABLE IF NOT EXISTS `".$_SESSION['prefix'].$table."` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`name` varchar(255) NOT NULL,
			PRIMARY KEY (`id`),
			UNIQUE KEY `name` (`name`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
		$insert = "INSERT INTO `".$_SESSION['prefix'].$table."` (`id`, `name`) VALUES
			(NULL, 'yopmail'),
			(NULL, 'jetable'),
			(NULL, 'mail-temporaire'),
			(NULL, 'ephemail'),
			(NULL, 'trashmail'),
			(NULL, 'kasmail'),
			(NULL, 'spamgourmet'),
			(NULL, 'tempomail'),
			(NULL, 'mytempemail'),
			(NULL, 'saynotospams'),
			(NULL, 'tempemail'),
			(NULL, 'mailinator'),
			(NULL, 'mytrashmail'),
			(NULL, 'mailexpire'),
			(NULL, 'maileater'),
			(NULL, 'guerrillamail'),
			(NULL, '10minutemail'),
			(NULL, 'dontreg'),
			(NULL, 'filzmail'),
			(NULL, 'spamfree24'),
			(NULL, 'brefmail'),
			(NULL, '0-mail'),
			(NULL, 'link2mail'),
			(NULL, 'dodgeit'),
			(NULL, 'e4ward'),
			(NULL, 'gishpuppy'),
			(NULL, 'haltospam'),
			(NULL, 'mailNull'),
			(NULL, 'nobulk'),
			(NULL, 'nospamfor'),
			(NULL, 'PookMail'),
			(NULL, 'shortmail'),
			(NULL, 'sneakemail'),
			(NULL, 'spam'),
			(NULL, 'spambob'),
			(NULL, 'spambox'),
			(NULL, 'spamDay'),
			(NULL, 'spamh0le'),
			(NULL, 'spaml'),
			(NULL, 'tempInbox'),
			(NULL, 'temporaryinbox'),
			(NULL, 'willhackforfood'),
			(NULL, 'willSelfdestruct'),
			(NULL, 'wuzupmail'),
			(NULL, 'mailhazard'),
			(NULL, 'mail');";
	break;
	case 'links':
		$drop = 'DROP TABLE IF EXISTS `'.$_SESSION['prefix'].$table.'`';
		$sql  = "CREATE TABLE IF NOT EXISTS `".$_SESSION['prefix'].$table."` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`link` varchar(255) NOT NULL,
			`countck` int(11) NOT NULL,
			PRIMARY KEY (`id`),
			UNIQUE KEY `link` (`link`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
	break;
	case 'page':
		$drop = 'DROP TABLE IF EXISTS `'.$_SESSION['prefix'].$table.'`';
		$sql  = "CREATE TABLE IF NOT EXISTS `".$_SESSION['prefix'].$table."` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`name` varchar(32) NOT NULL,
			`content` longtext,
			PRIMARY KEY (`id`),
			UNIQUE KEY `name` (`name`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
	break;
	case 'page_blog':
		$drop = 'DROP TABLE IF EXISTS `'.$_SESSION['prefix'].$table.'`';
		$sql  = "CREATE TABLE IF NOT EXISTS `".$_SESSION['prefix'].$table."` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`rewrite_name` varchar(128) NOT NULL,
			`name` varchar(128) NOT NULL,
			`date_create` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
			`author` varchar(32) NOT NULL,
			`content` text NOT NULL,
			`tags` text NOT NULL,
			`cat` varchar(16) NOT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
	break;
	case 'page_blog_cat':
		$drop = 'DROP TABLE IF EXISTS `'.$_SESSION['prefix'].$table.'`';
		$sql  = "CREATE TABLE IF NOT EXISTS `".$_SESSION['prefix'].$table."` (
			`id` int(11) NOT NULL,
			`name` varchar(16) CHARACTER SET latin1 NOT NULL,
			PRIMARY KEY (`id`),
			UNIQUE KEY `name` (`name`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
	break;
	case 'page_forum':
		$drop = 'DROP TABLE IF EXISTS `'.$_SESSION['prefix'].$table.'`';
		$sql  = "CREATE TABLE IF NOT EXISTS `".$_SESSION['prefix'].$table."` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`title` varchar(64) NOT NULL,
			`subtitle` varchar(128) NOT NULL,
			`groups` text NOT NULL,
			`activate` tinyint(1) DEFAULT '1',
			`orderby` int(11) NOT NULL DEFAULT '0',
			PRIMARY KEY (`id`),
			UNIQUE KEY `title` (`title`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
	break;
	case 'page_forum_post':
		$drop = 'DROP TABLE IF EXISTS `'.$_SESSION['prefix'].$table.'`';
		$sql  = "CREATE TABLE IF NOT EXISTS `".$_SESSION['prefix'].$table."` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`id_threads` int(11) NOT NULL,
			`title` varchar(128) NOT NULL,
			`author` varchar(32) NOT NULL,
			`options` text NOT NULL,
			`date_post` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
			`attachment` varchar(128) NOT NULL,
			`content` text NOT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
	break;
	case 'page_forum_posts':
		$drop = 'DROP TABLE IF EXISTS `'.$_SESSION['prefix'].$table.'`';
		$sql  = "CREATE TABLE IF NOT EXISTS `".$_SESSION['prefix'].$table."` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`id_post` int(11) NOT NULL,
			`author` varchar(32) NOT NULL,
			`options` text NOT NULL,
			`date_post` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
			`attachment` varchar(255) NOT NULL,
			`content` text NOT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
	break;
	case 'page_forum_threads':
		$drop = 'DROP TABLE IF EXISTS `'.$_SESSION['prefix'].$table.'`';
		$sql  = "CREATE TABLE IF NOT EXISTS `".$_SESSION['prefix'].$table."` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`id_forum` int(11) NOT NULL,
			`title` varchar(128) NOT NULL,
			`subtitle` varchar(256) NOT NULL,
			`orderby` int(11) DEFAULT NULL,
			`options` text NOT NULL,
			`icon` text NOT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
	break;
	case 'page_shoutbox':
		$drop = 'DROP TABLE IF EXISTS `'.$_SESSION['prefix'].$table.'`';
		$sql  = "CREATE TABLE IF NOT EXISTS `".$_SESSION['prefix'].$table."` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`hash_key` varchar(32) NOT NULL,
			`avatar` varchar(256) NOT NULL,
			`date_msg` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
			`msg` text,
			PRIMARY KEY (`id`),
			FULLTEXT KEY `msg` (`msg`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
	break;
	case 'page_users':
		$drop = 'DROP TABLE IF EXISTS `'.$_SESSION['prefix'].$table.'`';
		$sql  = "CREATE TABLE IF NOT EXISTS `".$_SESSION['prefix'].$table."` (
			`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			`username` varchar(32) NOT NULL,
			`password` char(255) NOT NULL,
			`email` varchar(128) NOT NULL,
			`avatar` varchar(255) NOT NULL,
			`hash_key` char(32) NOT NULL,
			`date_registration` datetime NOT NULL,
			`last_visit` datetime NOT NULL,
			`website` varchar(128) NOT NULL,
			`groups` text NOT NULL,
			`main_groups` text NOT NULL,
			`valid` int(1) NOT NULL,
			`ip` varchar(255) NOT NULL,
			`token` varchar(50) NOT NULL,
			PRIMARY KEY (`id`),
			UNIQUE KEY `mail` (`email`),
			UNIQUE KEY `name` (`username`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
	break;
	case 'page_users_management':
		$drop = 'DROP TABLE IF EXISTS `'.$_SESSION['prefix'].$table.'`';
		$sql  = "CREATE TABLE IF NOT EXISTS `".$_SESSION['prefix'].$table."` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`hash_key` varchar(32) NOT NULL,
			`pages` text,
			`widgets` text NOT NULL,
			PRIMARY KEY (`id`),
			UNIQUE KEY `hash_key` (`hash_key`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
	break;
	case 'page_users_profils':
		$drop = 'DROP TABLE IF EXISTS `'.$_SESSION['prefix'].$table.'`';
		$sql  = "CREATE TABLE IF NOT EXISTS `".$_SESSION['prefix'].$table."` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`hash_key` varchar(32) NOT NULL,
			`gender` varchar(11) NOT NULL,
			`public_mail` varchar(128) NOT NULL,
			`websites` text NOT NULL,
			`list_ip` text NOT NULL,
			`list_avatar` text NOT NULL,
			`config` text NOT NULL,
			`info_text` text NOT NULL,
			`birthday` date NOT NULL,
			`country` varchar(30) NOT NULL,
			`hight_avatar` varchar(255) NOT NULL,
			`friends` longtext NOT NULL,
			PRIMARY KEY (`id`),
			UNIQUE KEY `hash_key` (`hash_key`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
	break;
	case 'page_users_social':
		$drop = 'DROP TABLE IF EXISTS `'.$_SESSION['prefix'].$table.'`';
		$sql  = "CREATE TABLE IF NOT EXISTS `".$_SESSION['prefix'].$table."` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`hash_key` varchar(32) NOT NULL,
			`facebook` varchar(255) NOT NULL,
			`linkedin` varchar(128) NOT NULL,
			`twitter` varchar(35) NOT NULL,
			`googleplus` varchar(128) NOT NULL,
			`pinterest` varchar(35) NOT NULL,
			PRIMARY KEY (`id`),
			UNIQUE KEY `hash_key` (`hash_key`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
	break;
	case 'stats':
		$drop = 'DROP TABLE IF EXISTS `'.$_SESSION['prefix'].$table.'`';
		$sql  = "CREATE TABLE IF NOT EXISTS `".$_SESSION['prefix'].$table."` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`name` varchar(32) NOT NULL,
			`value` text NOT NULL,
			PRIMARY KEY (`id`),
			UNIQUE KEY `name` (`name`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
		$insert = "INSERT INTO `".$_SESSION['prefix'].$table."` (`id`, `name`, `value`) VALUES
			(NULL, 'record', '0'),
			(NULL, 'last', '0'),
			(NULL, 'today', '0'),
			(NULL, '01', '0'),
			(NULL, '02', '0'),
			(NULL, '03', '0'),
			(NULL, '04', '0'),
			(NULL, '05', '0'),
			(NULL, '06', '0'),
			(NULL, '07', '0'),
			(NULL, '08', '0'),
			(NULL, '09', '0'),
			(NULL, '10', '0'),
			(NULL, '11', '0'),
			(NULL, '12', '0');";
	break;
	case 'visitors':
		$drop = 'DROP TABLE IF EXISTS `'.$_SESSION['prefix'].$table.'`';
		$sql  = "CREATE TABLE IF NOT EXISTS `".$_SESSION['prefix'].$table."` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`date_page` varchar(12) NOT NULL,
			`page` varchar(32) NOT NULL,
			`ip` varchar(15) NOT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
	break;
	case 'widgets':
		$drop = 'DROP TABLE IF EXISTS `'.$_SESSION['prefix'].$table.'`';
		$sql  = "CREATE TABLE IF NOT EXISTS `".$_SESSION['prefix'].$table."` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`name` varchar(64) NOT NULL,
			`title` varchar(64) NOT NULL,
			`groups_access` varchar(255) NOT NULL,
			`groups_admin` varchar(255) NOT NULL,
			`activate` tinyint(1) DEFAULT NULL,
			`pos` varchar(6) NOT NULL,
			`orderby` int(11) NOT NULL,
			`pages` text NOT NULL,
			PRIMARY KEY (`id`),
			UNIQUE KEY `name` (`name`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
	break;
}

if (!is_null($sql)) {
	try {
		$cnx = new PDO('mysql:host='.$_SESSION['host'].';port='.$_SESSION['port'].';dbname='.$_SESSION['dbname'], $_SESSION['username'], $_SESSION['password']);
		$cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$cnx->exec($drop);
	} catch(PDOException $Exception) {
		$error = false;
		$class = '<span class="glyphicon glyphicon-remove"></span>';
	}
	unset($cnx);
}

if ($error) {
	try {
		$cnx = new PDO('mysql:host='.$_SESSION['host'].';port='.$_SESSION['port'].';dbname='.$_SESSION['dbname'], $_SESSION['username'], $_SESSION['password']);
		$cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$cnx->exec($sql);
		$class = '<span class="glyphicon glyphicon-ok"></span>';
	} catch(PDOException $Exception) {
		$error = false;
		$class = '<span class="glyphicon glyphicon-remove"></span>';
	}
	unset($cnx);
}

if ($error && !is_null($insert)) {
	try {
		$cnx = new PDO('mysql:host='.$_SESSION['host'].';port='.$_SESSION['port'].';dbname='.$_SESSION['dbname'], $_SESSION['username'], $_SESSION['password']);
		$cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$cnx->exec($insert);
		$class = '<span class="glyphicon glyphicon-ok"></span>';
	} catch(PDOException $Exception) {
		echo $Exception;
		$error = false;
		$class = '<span class="glyphicon glyphicon-remove"></span>';
	}
	unset($cnx);
}
