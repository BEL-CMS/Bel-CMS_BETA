<?php
/**
 * Bel-CMS [Content management system]
 * @version 0.3.0
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

class Team extends Pages
{
	var $models = array('ModelsTeam');

	public function index ()
	{
		foreach ($this->ModelsTeam->GetGroups() as $k => $v) {
			if ($v->id_group != 3) {
				$members[$v->name] = $this->ModelsTeam->GetUsers($v->id_group);
			}
		}
		$data['members'] = $members;
		$this->set($data);
		$this->render('index');
	}
}
