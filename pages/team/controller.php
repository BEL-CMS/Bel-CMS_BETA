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

class ControllerPagesTeam extends ModelPagesTeam
{
	public 	$data,
			$view,
			$access = false;
	#####################################
	# Start Class
	#####################################
	function __construct()
	{

	}
	public function index ()
	{
		foreach (parent::GetGroups() as $k => $v) {
			if ($v->id_group != 3) {
				$members[$v->name] = parent::GetUsers($v->id_group);
			}
		}
		$this->data = $members;
	}
}