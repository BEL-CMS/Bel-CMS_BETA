<?php
/**
 * Bel-CMS [Content management system]
 * @version 0.0.3
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

class Dashboard extends Pages
{
	var $models = array('ModelsDashboard');
	var $intern = 'true';

	public function index ()
	{
		$arrayNbConnected   = array();
		$initializeArrayDay = array();
		$nbLine             = 1;

		$set['conneted']    = $this->ModelsDashboard->visitNow();
		$visitMonth         = $this->ModelsDashboard->visitMonth();

		while ($nbLine <= date('t')) {
			$initializeArrayDay[$nbLine++] = 0;
		}

		foreach ($visitMonth as $k => $v) {
			if ($v->visitor_day[0] == 0) {
				$v->visitor_day = substr($v->visitor_day,1);
			}
			if (array_key_exists($v->visitor_day, $initializeArrayDay)) {
				$initializeArrayDay[$v->visitor_day]++;
			}
		}

		$set['month'] = $initializeArrayDay;
		$this->set($set);
		$this->render('index');
	}
}
