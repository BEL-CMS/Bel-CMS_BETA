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

class Access
{
	public 	$groups;

	function __construct()
	{
		$this->groups = self::GetGroups();
		Access::GetConfigPages();
	}

	private function GetGroups ()
	{
		$return = array();

		$BDD = New BDD();
		$BDD->table('TABLE_GROUPS');
		$BDD->fields(array('name', 'id_group'));
		$BDD->queryAll();

		$results = $BDD->data;

		unset($BDD);

		foreach ($results as $k => $v) {
			$return[(int) $v->id_group] = (string) ucfirst($v->name);
		}

		return $return;
	}

	public static function GetConfigPages ($page = false, $purge = false)
	{
		$returnPage = false;

		if ($purge === false) {

			if (!isset($_SESSION['pages']) || !is_object($_SESSION['pages'])) {
				$_SESSION['pages'] = new stdClass();
			}

	 		$return = array();

			$BDD = New BDD();
			$BDD->table('TABLE_PAGES_CONFIG');
			$BDD->fields(array('name', 'active', 'access_groups', 'access_admin', 'config'));
			$BDD->queryAll();

			$results = $BDD->data;

			unset($BDD);

			foreach ($results as $k => $v) {
				$name = $v->name; unset($v->name);
				$v->access_groups = explode('|', $v->access_groups);
				$v->access_admin  = explode('|', $v->access_admin);

				if (count($v->config) == 0) {
					$v->config = array();
				} else {
					$v->config = explode('|', $v->config);

					foreach ($v->config as $kc => $vc) {
						$tmp_config = explode('=', $vc);
						$v->config[$tmp_config[0]] = $tmp_config[1];
						unset($v->config[$kc]);
					}
				}

				$_SESSION['pages']->$name = $v;
				$return = $_SESSION['pages'];
				$returnPage = true;
			}
		}

		if ($page !== false && isset($_SESSION['pages']->$page)) {
			$return = $_SESSION['pages']->$page;
		}
		return $return;

		if ($page === false && isset($_SESSION['pages'])) {
			$returnPage = true;
			$return = $_SESSION['pages'];
		} else if ($page !== false && isset($_SESSION['pages']->$page)) {
			$returnPage = true;
			$return = $_SESSION['pages']->$page;
		}

		if ($returnPage === false) {

			if (!isset($_SESSION['pages']) || !is_object($_SESSION['pages'])) {
				$_SESSION['pages'] = new stdClass();
			}

	 		$return = array();

			$BDD = New BDD();
			$BDD->table('TABLE_PAGES_CONFIG');
			$BDD->fields(array('name', 'active', 'access_groups', 'access_admin', 'config'));
			$BDD->queryAll();

			$results = $BDD->data;

			unset($BDD);

			foreach ($results as $k => $v) {
				$name = $v->name; unset($v->name);
				$v->access_groups = explode('|', $v->access_groups);
				$v->access_admin  = explode('|', $v->access_admin);

				if (count($v->config) == 0) {
					$v->config = array();
				} else {
					$v->config = explode('|', $v->config);

					foreach ($v->config as $kc => $vc) {
						$tmp_config = explode('=', $vc);
						$v->config[$tmp_config[0]] = $tmp_config[1];
						unset($v->config[$kc]);
					}
				}

				$_SESSION['pages']->$name = $v;
				$return = $_SESSION['pages'];
				$returnPage = true;
			}
		}

		if ($returnPage === false && $page !== false && isset($_SESSION['pages']->$page)) {
			$return = $_SESSION['pages']->$page;
		}
		return $return;

	}

}
