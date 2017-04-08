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
		$file_password = function_exists('password_hash') ? '' : DIR_CORE.'password.php';

		$this->files = array(
			$file_password,
			DIR_CORE.'error.class.php',
			DIR_CORE.'common.class.php',
			DIR_CORE.'notification.class.php',
			DIR_CORE.'host.class.php',
			DIR_CONFIG.'config.inc.php',
			DIR_CONFIG.'config.table.php',
			DIR_CORE.'spdo.class.php',
			DIR_CORE.'session.class.php',
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

		self::getFiles ();
	}
	function getFiles ()
	{
		foreach ($this->files as $file):
			if (!is_file($file)) {
				throw new Exception ('Fichier '.$file.' manquant.');
				break;
			} else {
				require $file;
			}
		endforeach;
	}
}