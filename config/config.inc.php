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

if (!defined('CHECK_INDEX')) {
	header($_SERVER['SERVER_PROTOCOL'] . ' 403 Direct access forbidden');
	exit(ERROR_INDEX);
}

$BDD = 'local';

$databases['local'] = array(
	#####################################
	# Réglages MySQL - LOCAL
	#####################################
	'DB_DRIVER'   => 'mysql',
	'DB_NAME'     => 'new',
	'DB_USER'     => 'root',
	'DB_PASSWORD' => 'usbw',
	'DB_HOST'     => 'localhost',
	'DB_PREFIX'   => 'belcms_',
	'DB_PORT'     => '3307'
);

$databases['server'] = array(
	#####################################
	# Réglages MySQL - SERVEUR
	#####################################
	'DB_DRIVER'   => 'mysql',
	'DB_NAME'     => 'demopwr',
	'DB_USER'     => 'demopwr',
	'DB_PASSWORD' => 'rphm86sf',
	'DB_HOST'     => 'localhost',
	'DB_PREFIX'   => ' ',
	'DB_PORT'     => '3306'
);

Common::constant($databases[$BDD]); unset($databases, $BDD);
