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

final class CheckFilesFolders
{
	private $folders = array(),
			$files   = array();
	public  $return  = true;

	function __construct()
	{
		self::TestFolders();
		if ($this->return === false) {
			throw new Exception ('tout les dossiers d\'origine ne sont pas present !');
		}
		self::TestFiles();
		if ($this->return === false) {
			throw new Exception ('Un fichier d\'origine au CMS n\'est pas present !');
		}

	}

	private function folders ()
	{
		$folders = array(
			'assets',
			'class',
			'config',
			'management',
			'pages',
			'templates',
			'uploads',
			'widgets'
		);
		sort($folders, SORT_LOCALE_STRING);
		return $folders;
	}
	private function files ($d = false)
	{
		$files = array(
			'assets'    =>	array('index.html'),
			'class'     =>	array(
								'index.html',
								'access.class.php',
								'belcms.class.php',
								'check_files_and_folder.class.php',
								'common.class.php',
								'config.class.php',
								'dispatcher.class.php',
								'error.class.php',
								'ini.class.php',
								'notification.class.php',
								'page.class.php',
								'password.php',
								'require_file.class.php',
								'spdo.class.php',
								'template.class.php'
						   	),
			'config'    => 	array(
								'index.html',
								'config.define.php',
								'config.inc.php',
								'config.table.php',
							),
			'management'=> array('index.html',
								 'login.php',
								 'lang.php',
								 'model.class.php'
							),
			'pages'     => array('index.html'),
			'templates' => array('index.html'),
			'uploads'   => array('index.html'),
			'widgets'   => array('index.html'),
		);

		foreach ($files as $dir => $f) {
			sort($f, SORT_STRING);
			$return[$dir] = $f;
		}
		if ($d === false) {
			return $return;
		} else {
			return $return[$d];
		}
	}
	private function TestFolders ()
	{
		foreach (self::folders() as $folders) {
			if (!@opendir($folders)) {
				$this->return = false;
				break;
			}
		}
	}
	private function TestFiles ()
	{
		$return = array();
		$array  = array();
		$dir = self::files();

		foreach ($dir as $dirs => $files) {
			if (is_dir($dirs)) {
				if ($dh = opendir($dirs)) {
					while (($file = readdir($dh)) !== false) {
						if ($file != '.' && $file != '..') {
							$return[$dirs][] = $file;
						}
					}
					closedir($dh);
				}
			}
		}

		foreach ($return as $dirs => $file) {
			sort($file, SORT_LOCALE_STRING);
			$array[$dirs] = $file;
		}

		$ok = '';

		foreach ($dir as $k_prim => $v_prim) {
			if (array_key_exists($k_prim, $array)) {
				foreach ($v_prim as $k_sec => $v_sec) {
					if (!in_array($v_sec, $array[$k_prim], true)) {
						$this->return = false;
						break;
					}
				}
			} else {
				$this->return = false;
				break;
			}
		}
	}
}
