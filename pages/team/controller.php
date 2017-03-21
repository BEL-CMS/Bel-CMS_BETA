<?php
/**
 * Bel-CMS [Content management system]
 * @version 0.0.1
 * @link http://www.bel-cms.be
 * @link http://www.stive.eu
 * @license http://opensource.org/licenses/GPL-3.0 copyleft
 * @copyright 2014 Bel-CMS
 * @author Stive - mail@stive.eu
 */

class Team extends Pages
{
	var $models = array('ModelsTeam');

	#####################################
	# Start Class
	#####################################
	function __construct()
	{
		parent::__construct();
	}
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