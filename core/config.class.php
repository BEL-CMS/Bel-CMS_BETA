<?php

final class Config extends Dispatcher
{
	function __construct ()
	{
		parent::__construct();
		$return = array();

		$sql = New BDD();
		$sql->table('TABLE_CONFIG');
		$sql->fields(array('name', 'value'));
		$sql->queryAll();
		$config = $sql->data;
		unset($sql);
		foreach ($config as $k => $v) {
			$return[mb_strtoupper($v->name)] = (string) $v->value;
		}
		Common::Constant($return);
		self::GetLangs();
		self::getConfigPages();
	}

	private function getConfigPages ()
	{
		if (defined('CONFIG_PAGES')) {
			return;
		} else {
			$sql = New BDD();
			$sql->table('TABLE_PAGES_CONFIG');
			$sql->fields(array('name', 'active', 'access_groups', 'access_admin', 'config'));
			$sql->queryAll();
			$data = $sql->data;
			define('CONFIG_PAGES', true);
			foreach ($data as $k => $v) {
				$GLOBALS['CONFIG_PAGES'][$v->name] = array(
					'active' => $v->active,
					'groups' => $v->access_groups,
					'admin'  => $v->access_admin,
					'config' => Common::transformOpt($v->config)
				);
			}
		}
	}

	private function GetLangs ()
	{
		if (defined('CMS_WEBSITE_LANG')) {
			$return = CMS_WEBSITE_LANG;
		} else {
			$sql = New BDD();
			$sql->table('TABLE_CONFIG');
			$sql->where(array('name' => 'name', 'value' => 'CMS_WEBSITE_LANG'));
			$sql->fields(array('name', 'value'));
			$sql->queryOne();
			Common::Constant($sql->data->name, $sql->data->value);
			unset($sql);
		}
		$fileLangBase = DIR_LANG.'base.lang.'.CMS_WEBSITE_LANG.'.php';
		if (is_file($fileLangBase)) {
			include $fileLangBase;
		} else {
			throw new Exception('ERROR FILE : file ['.$fileLangBase.'] no found', 2);
		}
		$fileLangPage = DIR_PAGES.mb_strtolower($this->controller).DS.'lang'.DS.'lang.'.CMS_WEBSITE_LANG.'.php';
		if (is_file($fileLangPage)) {
			include $fileLangPage;
		}
	}

	public static function GetGroups ()
	{
		if (isset($_SESSION['groups']) && !empty($_SESSION['groups'])) {
			return $_SESSION['groups'];
		} else {
			$return = array();

			$sql = New BDD();
			$sql->table('TABLE_GROUPS');
			$sql->fields(array('name', 'id_group'));
			$sql->queryAll();
			$results = $sql->data;
			unset($sql);

			foreach ($results as $k => $v) {
				$return[(int) $v->id_group] = (string) ucfirst($v->name);
			}
			
			$_SESSION['groups'] = (object) $return;
			return $return;		
		}
	}
}