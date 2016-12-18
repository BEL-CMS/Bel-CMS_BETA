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

final class RequireFiles
{
	var $array = array();

	function __construct()
	{
		$this->error = false;
		self::GetFiles();
	}

	private function GetFiles ()
	{
		$file_password = function_exists('password_hash') ? '' : ROOT_CLASS.'password.php';
		$this->array = array(
			$file_password,
			ROOT_CLASS.'ini.class.php',
			ROOT_CLASS.'common.class.php',
			ROOT_CLASS.'notification.class.php',
			ROOT_CONFIG.'config.inc.php',
			ROOT_CONFIG.'config.table.php',
			ROOT_CLASS.'spdo.class.php',
			ROOT_CLASS.'user.class.php',
			ROOT_CLASS.'config.class.php',
			ROOT_CLASS.'access.class.php',
			ROOT_CLASS.'widgets.class.php',
			ROOT_CLASS.'page.class.php',
			ROOT_CLASS.'management.class.php',
			ROOT_CLASS.'visitors.class.php',
			ROOT_CLASS.'template.class.php',
		);
		self::TestExistFiles();
	}

	private function TestExistFiles ()
	{
		foreach ($this->array as $f) {
			if (!empty($f)) {
				if (!is_file($f)) {
					throw new Exception('file not found : ' .$f);
					break;
				}
			}
		}
		if ($this->error === false) {
			self::IncludeFiles();
		}
	}
	private function IncludeFiles () {
		if ($this->error === false) {
			foreach ($this->array as $f) {
				if (!empty($f)) {
					include $f;
				}
			}
		}
	}
}
