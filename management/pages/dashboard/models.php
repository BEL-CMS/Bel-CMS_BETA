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

class ModelsDashboard
{
	public function visitNow ()
	{
		$sql = new BDD;
		$sql->table('TABLE_VISITORS');
		$less  = date('Y-m-d H:i:s', strtotime('-5 minutes'));
		$where = "WHERE `visitor_date` > '$less'";
		$sql->where($where);
		$sql->limit(10);
		$sql->queryAll();
		$return = $sql->data;

		return $return;
	}

	public function visitMonth ()
	{
		$currentMonth = date('m');
		$currentYear  = date('Y');
		$sql = new BDD;
		$sql->table('TABLE_VISITORS');
		$where = "WHERE `visitor_month` = '".$currentMonth."' AND `visitor_year` = '".$currentYear."'";
		$sql->where($where);
		$sql->queryAll();
		$return = $sql->data;

		return $return;
	}
}