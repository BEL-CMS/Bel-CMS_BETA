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

final class Visitors
{

	function __construct()
	{
		self::DeleteOldVisitor();
		self::UpdateVisit();
		self::InsertIpVisitorDay();
	}

	private function DeleteOldVisitor ()
	{
		$time = time() - (60 * CMS_VISITORS_TIME);
		$sql = New BDD();
		$sql->table('TABLE_VISITORS');
		$sql->where(array('name' => 'date_page' , 'value' => $time, 'op' => '<'));
		$sql->delete();
	}

	private function UpdateVisit ()
	{
		$sql = New BDD();
		$sql->table('TABLE_VISITORS');
		$sql->where(array('name' => 'ip' , 'value' => Common::GetIp()));
		$sql->queryOne();
		$count = $sql->rowCount;
		unset($sql);

		if ($count == 0) {
			$sql = New BDD();
			$sql->table('TABLE_VISITORS');
			$insert['date_page'] = time();
			if (REQUEST_AJAX === false && REQUEST_ECHO === false) {
				$insert['page'] = Common::translate(GET_PAGE);
			}
			if (isset($_SESSION['user']->username)) {
				$insert['main_group'] = (int) $_SESSION['user']->main_groups;
			} else {
				$insert['main_group'] = 0;
			}
			$insert['ip'] = Common::GetIp();
			$sql->sqlData($insert);
			$sql->insert();
		} else {
			$sql = New BDD();
			$sql->table('TABLE_VISITORS');
			$sql->where(array('name' => 'ip' , 'value' => Common::GetIp()));
			$update['date_page'] = time();
			if (REQUEST_AJAX === false && REQUEST_ECHO === false) {
				$update['page']  = Common::translate(GET_PAGE);
			}
			if (isset($_SESSION['user']->username)) {
				$update['main_group'] = (int) $_SESSION['user']->main_groups;
			} else {
				$update['main_group'] = 0;
			}
			$sql->sqlData($update);
			$sql->update();
		}
	}
	private function InsertIpVisitorDay ()
	{
		$ip   = ip2long(Common::GetIp());
		$date = date('d-m-Y');
		$file = ROOT_VISITORS.'visitors_'.$date.'.php';
		$ini  = New iniParser($file);
		$ini->set('VISITORS', $ip);
		$ini->save();
	}

	public static function GetVisitorsDay ()
	{
		$date = date('d-m-Y');
		$file = ROOT_VISITORS.'visitors_'.$date.'.php';
		$ini  = New iniParser($file);
		$get = $ini->get('VISITORS');
		return count($get);
	}
	public static function GetVisitorsLasterday()
	{
		$date = date('d-m-Y', strtotime(date('Y-m-d').' - 1 DAY'));
		$file = ROOT_VISITORS.'visitors_'.$date.'.php';
		if (is_file($file)) {
			$ini  = New iniParser($file);
			$get = $ini->get('VISITORS');
		} else {
			$get = null;
		}
		return count($get);
	}
}
