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

class RequireFiles
{
	var $files = array();

	function __construct ()
	{
		$this->files = array(
			function_exists('password_hash') ? '' : DIR_CORE.'password.php',
			DIR_CORE.'error.class.php',
			DIR_CORE.'common.class.php',
			DIR_CORE.'notification.class.php',
			DIR_CORE.'host.class.php',
			DIR_CONFIG.'config.inc.php',
			DIR_CONFIG.'config.table.php',
			DIR_CORE.'spdo.class.php',
			DIR_CORE.'ini.class.php',
			DIR_CORE.'visitors.class.php',
			DIR_CORE.'user.class.php',
			DIR_CORE.'dispatcher.class.php',
			DIR_CORE.'config.class.php',
			DIR_CORE.'widgets.class.php',
			DIR_CORE.'comment.class.php',
			DIR_CORE.'pages.class.php',
			DIR_CORE.'template.class.php',
			DIR_CORE.'belcms.class.php',
		);

		self::getFiles();
	}
	private function getFiles ()
	{
		require DIR_CORE.'common.class.php';
		// fix bogue : array(0)
		if (function_exists('password_hash')) {
			unset($this->files[0]);
		}
		foreach ($this->files as $file) {
			if (is_file($file)) {
				require_once $file;
			} else {
				throw new Exception ('Fichier '.$file.' manquant.');
				break;
			}
		}

	}
}