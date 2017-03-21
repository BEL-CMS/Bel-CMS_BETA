<?php

final class GetHost {

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