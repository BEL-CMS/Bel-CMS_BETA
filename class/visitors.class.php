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
		self::CheckCurrentIp();
		self::NbLastVisitor();
		self::InsertIpVisitorDay();
		self::NbVisitorMonth();
	}

	private function CheckCurrentIp ()
	{
		$sql = New BDD();
		$sql->table('TABLE_VISITORS');
		$sql->where(array('name' => 'ip' , 'value' => Common::GetIp()));
		$sql->queryOne();

		if ($sql->rowCount == 0) {
			unset($sql);
			$sql = New BDD();
			$sql->table('TABLE_VISITORS');
			$insert['date_page'] = time();
			$insert['page']      = GET_PAGE;
			$insert['ip']        = Common::GetIp();
			$sql->sqlData($insert); unset($insert);
			$sql->insert();
		} else {
			$sql = New BDD();
			$sql->table('TABLE_VISITORS');
			$sql->where(array('name' => 'ip' , 'value' => Common::GetIp()));
			$update['date_page'] = time();
			$update['page']      = GET_PAGE;
			$sql->sqlData($update);
			$sql->update();
		}
	}
	private function InsertIpVisitorDay ()
	{
		$month = date('m');
		$file = ROOT_VISITORS.'visitors.'.$month.'.ini';
		$ini = New iniParser($file);
		$date = date('d');
		$ini->set($date, Common::GetIp());
		$ini->save();
		$get = $ini->get(date('d'));

		$sql = New BDD();
		$sql->table('TABLE_STATS');
		$sql->where(array('name'=>'id','value'=> 3));
		$sql->sqlData(array('value' => count($get)));
		$sql->update();

	}

	private function NbLastVisitor ()
	{
		$month = date('m');
		$file = ROOT_VISITORS.'visitors.'.$month.'.ini';
		$ini = New iniParser($file);
		$date = date('d', strtotime('-1 days'));
		$ini->set($date, '000.000.000.000');
		$ini->save();
		$get = $ini->get($date);

		foreach ($get as $k => $v) {
			if ('000.000.000.000' == $k) {
				$count = true;
			} else {
				$count = false;
			}
		}

		$sql = New BDD();
		$sql->table('TABLE_STATS');
		$sql->where(array('name'=>'id','value'=> 2));
		if ($count) {
			$get = count($get) -1;
		} else {
			$get = count($get);
		}
		$sql->sqlData(array('value' => $get));
		$sql->update();

		return $get;

	}
	private function NbVisitorMonth ()
	{
		$allMonth = array(1,2,3,4,5,6,7,8,9,10,11,12);

		foreach ($allMonth as $k => $v) {
			$k = $k +1;
			$file = ROOT_VISITORS.'visitors.'.$k.'.ini';
			$ini = New iniParser($file);
		}
	}
}
New Visitors;
