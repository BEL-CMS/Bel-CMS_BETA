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

class GetHost {

	public static function isHttps() {
		return (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") ||
			$_SERVER['SERVER_PORT'] == 443;
	}

	public static function getBaseUrl() {
		$protocol = self::isHttps() ? 'https' : 'http';
		if (isset($_SERVER["SERVER_PORT"])) {
			$port = ':' . $_SERVER["SERVER_PORT"];
		} else {
			$port = '';
		}
		if ($port == ':80' || $port == ':443') {
			$port = '';
		}
		$uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
		$cutoff = strpos($uri, 'index.php');
		$uri = substr($uri, 0, $cutoff);
		$serverName = getenv('HOSTNAME')!==false ? getenv('HOSTNAME') : (isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : '');
		return "$protocol://{$serverName}$port{$uri}/";	
	}
}

$define = array(
	'BASE_URL'            => GetHost::getBaseUrl(),
	'ROOT_INDEX'          => str_replace('index.php', '', $_SERVER['PHP_SELF']),
	'ROOT_CLASS'          => ROOT.'class'.DS,
	'ROOT_CONFIG'         => ROOT.'config'.DS,
	'ROOT_UPLOADS'        => ROOT.'uploads'.DS,
	'ROOT_VISITORS'       => ROOT.'visitors'.DS,
	'ROOT_ASSETS'         => ROOT.'assets'.DS,
	'ROOT_PAGES'          => ROOT.'pages'.DS,
	'ROOT_TPL'            => ROOT.'templates'.DS,
	'ROOT_LANG'           => ROOT.'assets'.DS.'lang'.DS,
	'ROOT_MANAGEMENT'     => ROOT.'management'.DS,
	'ROOT_WIDGETS'        => ROOT.'widgets'.DS,
	'ROOT_TPL_DFT'        => ROOT.'assets'.DS.'tpl'.DS,
	'ROOT_MANAGEMENT_TPL' => 'management'.DS,
	'ROOT_HTML'           => 'templates'.DS,
	'ROOT_HTML_DFT'       => 'assets'.DS.'tpl'.DS,
	'CMS_DEFAULT_TPL'     => 'default',
);

foreach ($define as $k => $v) {
	define ($k, $v);
}

unset($define);

$GLOBALS['CONFIG'] = false;
$GLOBALS['DISPATCHER'] = false;
$GLOBALS['CSS_GLOBAL'] = false;
