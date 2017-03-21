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

class WidgetsControllerInfosusers extends WidgetsModelInfosusers
{
	public $data;
	protected $groups;

	function __construct()
	{
		$this->groups = parent::GetGroups();
		foreach ($this->groups as $k => $v) {
			$name = defined(strtoupper($v->name)) ? constant($v->name) : $v->name;
			$groups[ucfirst($name)] = parent::GetUsersNb($v->id_group);
		}
		$this->data['users']     = $groups;
		foreach (parent::GetConnected() as $k => $v) {
			$this->data['connected'][$v->name] = $v->value;
		}
		$this->data['current'] = count(parent::NbNow());
		return $this->data;
	}
}
