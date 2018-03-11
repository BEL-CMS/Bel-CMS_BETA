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

# inclus le fichier lang fr { prevoir multi-lang }
include ROOT_MANAGEMENT.'lang.fr.php';
# nom pour accèder au management
$NameManagement = array(
	'admin',
	'Admin',
	'Management',
	'management'
);
# definit le management sur true;
foreach ($NameManagement as $k) {
	if (array_key_exists($k, $_REQUEST)) {
		define('MANAGEMENT', true);
		break;
	}
}

class Management
{
	function __construct ()
	{
		new Access();
		# reset de la session des pages
		Config::getConfigPages(true);
		# reset la session des groupes
		Config::GetGroups(true);
		# vérifie le login management
		if (!isset($_SESSION['LOGIN_MANAGEMENT'])) {
			$_SESSION['LOGIN_MANAGEMENT'] = false;
		}
		# vérifie si l'user est loguer
		if (!AutoUser::ReturnUser()) {
			Common::redirect('user/login');
			exit;
		}
		# vérifie si l'user est loguer pour le mangement
		if ($_SESSION['LOGIN_MANAGEMENT'] !== true) {
			if (strtolower(get_class($GLOBALS['bel_cms']->controller)) != 'login') {
				Common::redirect('login?management');
				exit;
			}
		} else if ($_SESSION['LOGIN_MANAGEMENT'] === true) {
			if (strtolower(get_class($GLOBALS['bel_cms']->controller)) == 'login') {
				Common::redirect('dashboard?management');
				exit;
			}
		}

	}

	public function page ($page) {
		$this->_page = $page;
	}

	public function full ($a) {
		$this->full = $a;
	}

	function render ()
	{
		ob_start("ob_gzhandler");

		if ($this->full !== true) {
			$file = ROOT_MANAGEMENT.'main.tpl.php';
			if (is_file($file)) {
				$this->listPages   = self::listingPages();
				$this->listWidgets = self::listingWidgets();
				require $file;
				$buffer = ob_get_contents();
			} else {
				$error_name    = 'file no found';
				$error_content = '<strong>file "main.tpl.php" no found : </strong>';
				require DIR_ASSET_TPL.'error'.DS.'404.php';
			}
		} else {
			echo $this->_page;
		}
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}
	#####################################
	# Scan Page & Widgets / Access
	#####################################
	private function listingPages () {

		$scanDir = Common::ScanDirectory(DIR_PAGES);

		$pages	 = array();

		foreach ($scanDir as $k => $v) {
			if (
				file_exists(DIR_PAGES.$v.DS.'management') && 
				file_exists(DIR_PAGES.$v.DS.'management'.DS.'index.php') &&
				file_exists(DIR_PAGES.$v.DS.'management'.DS.'controller.php')
			   ) {
				$pages[] = $v;
			}
		}
		return $pages;
	}
	public function listingWidgets () {

		$scanDir = Common::ScanDirectory(DIR_WIDGETS);

		$widgets = array();

		foreach ($scanDir as $k => $v) {
			if (
				file_exists(DIR_WIDGETS.$v.DS.'management') && 
				file_exists(DIR_WIDGETS.$v.DS.'management'.DS.'index.php') &&
				file_exists(DIR_WIDGETS.$v.DS.'management'.DS.'controller.php')
			   ) {
				$widgets[] = $v;
			}
		}

		return $widgets;
	}
}

class Access
{
	public 	$groups;

	function __construct()
	{
		$this->groups = self::GetGroups();
	}

	private function GetGroups ()
	{
		return $_SESSION['groups'];
	}

	public static function AccessManagementWidgets ($name = false)
	{
		$access = (bool) false;

		if ($name !== false) {
			$access = (object) array();

			$sql = New BDD;
			$sql->table('TABLE_WIDGETS');
			$sql->queryAll();
			foreach ($sql->data as $k => $v) {
				$accessPages[$v->name] = explode('|', $v->groups_admin);
			}

			foreach ($accessPages[$name] as $k => $v) {
				if (in_array($v, $_SESSION['user']->groups)) {
					$access = (bool) true;
					break;
				} else {
					$access = (bool) false;
				}
			}

		}
		return $access;
	}

	public static function AccessManagementPage ($name = false)
	{
		$access = (bool) false;

		if ($name !== false) {
			$access = (object) array();

			$sql = New BDD;
			$sql->table('TABLE_PAGES_CONFIG');
			$sql->queryAll();
			foreach ($sql->data as $k => $v) {
				$accessPages[$v->name] = explode('|', $v->access_admin);
			}

			if ($name == 'dashboard') {
				$access = (bool) true;
			} else {
				foreach ($accessPages[$name] as $k => $v) {
					if (in_array($v, $_SESSION['user']->groups)) {
						$access = (bool) true;
						break;
					} else {
						$access = (bool) false;
					}
				}
			}
		}
		return $access;
	}
}