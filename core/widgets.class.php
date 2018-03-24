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

class Widgets
{
	var $vars = array();
	var $widgets = null;

	function __construct () {
		if (isset($this->models)){
			foreach($this->models as $v){
				$this->loadModel($v);
			}
		}
	}

	function set ($d) {
		$this->vars = array_merge($this->vars,$d);
	}

	function error ($title, $msg, $type)
	{
		ob_start();
		new notification ($title, $msg, $type);
		$this->widgets = ob_get_contents();
		ob_end_clean();
	}

	function render($filename) {
		if (self::accessWidgets(strtolower(get_class($this))) !== false) {
			extract($this->vars);
			ob_start();
			$dir = DIR_WIDGETS.strtolower(get_class($this)).DS.$filename.'.php';
			$custom = DIR_TPL.CMS_TPL_WEBSITE.DS.'custom'.DS.'widgets.'.lcfirst(get_class($this)).'.php';
			if (is_file($custom)) {
				require_once $custom;
			} else if (is_file($dir)) {
				require_once $dir;
			} else {
				$error_name    = 'file no found';
				$error_content = '<strong>file : '.$filename.' no found : </strong>';
				require DIR_ASSET_TPL.'error'.DS.'404.php';
			}
			$this->widgets = ob_get_contents();
			if (ob_get_length() != 0) {
				ob_end_clean();
			}
		}
	}

	function loadModel ($name)
	{
		if (is_file(DIR_WIDGETS.strtolower(get_class($this)).DS.'models.php')) {
			require_once DIR_WIDGETS.strtolower(get_class($this)).DS.'models.php';
			$this->$name = new $name();
		} else {
			ob_start();
			$error_name    = 'file no found';
			$error_content = '<strong>file models no found</strong> : <br>'.DIR_PAGES.get_class($this).DS.'models.php';
			require DIR_ASSET_TPL.'error'.DS.'404.php';
			$this->widgets = ob_get_contents();
			ob_end_clean();
		}
	}

	#########################################
	# Access widgets
	#########################################
	function accessWidgets ($widgets)
	{
		$access = (bool) false;

		if (AutoUser::isLogged() === true) {
			$groups = AutoUser::getInfosUser($_SESSION['user']->hash_key)->groups;
		} else {
			$groups = array();
		}

		$sql = New BDD;
		$sql->table('TABLE_WIDGETS');
		$sql->where(array('name' => 'name', 'value' => $widgets));
		$sql->queryOne();

		$sql->data->groups_access = explode('|', $sql->data->groups_access);

		foreach ($sql->data->groups_access as $k => $v) {
			if ($v == 0 or in_array(1, $groups)) {
				$access = (bool) true;
				break;
			}
			if (isset($_SESSION['user'])) {
				if (in_array($v, $groups)) {
					$access = (bool) true;
					break;
				} else {
					$access = (bool) false;
				}
			}
		}

		return $access;
	}
}
