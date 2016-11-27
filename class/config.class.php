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
class Config
{
	function __construct()
	{
		$GLOBALS['CONFIG'] = true;
		self::GetConfig();
		self::GetLangs();
		self::GetGroups();
	}

	protected function GetConfig ()
	{
		$return = array();

		$BDD = New BDD();
		$BDD->table('TABLE_CONFIG');
		$BDD->fields(array('name', 'value'));
		$BDD->queryAll();
		$results = $BDD->data;

		unset($BDD);

		foreach ($results as $k => $v) {
			$return[mb_strtoupper($v->name)] = (string) $v->value;
		}

		Common::Constant($return);
	}

	protected function GetGroups ()
	{
		$groups = New Access;
		$groups = (object) $groups->groups;
		$GLOBALS['GROUPS']  = $groups;
	}

	protected function GetLangs ()
	{
		if (defined('CMS_WEBSITE_LANG')) {
			$return = CMS_WEBSITE_LANG;
		} else {
			$BDD = New BDD();
			$BDD->table('TABLE_CONFIG');
			$BDD->where(array('name' => 'name', 'value' => 'CMS_WEBSITE_LANG'));
			$BDD->fields(array('name', 'value'));
			$BDD->queryOne();
			Common::Constant($BDD->data->name, $BDD->data->value);
			unset($BDD);
		}
		$fileLangBase = ROOT_LANG.'base.lang.'.CMS_WEBSITE_LANG.'.php';
		if (is_file($fileLangBase)) {
			include $fileLangBase;
		} else {
			Notification::newPage('ERROR FILE', 'file ['.$fileLangBase.'] no found');
		}
		if (defined('GET_PAGE')) {
			$fileLangPage = ROOT_PAGES.DS.mb_strtolower(GET_PAGE).DS.'lang'.DS.'lang.'.CMS_WEBSITE_LANG.'.php';
			if (is_file($fileLangPage)) {
				include $fileLangPage;
			}
		}
	}
}
