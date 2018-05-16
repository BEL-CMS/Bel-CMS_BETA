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

class Prefwidgets extends Pages
{
	var $models = array('ModelsPrefwidgets');
	var $intern = 'true';

	public function __construct ()
	{
		parent::__construct();
		$this->name = defined('MANAGEMENT_TITLE_NAME') ? MANAGEMENT_TITLE_NAME : get_class($this);
	}

	public function index ()
	{
		$set['formWidgets'] = $this->ModelsPrefwidgets->getFormWidgets();
		$this->set($set);
		$this->render('index');
	}

	public function parameter ($id)
	{
		$this->internManagement(true);
		$id = (int) $id;

		$getGroups        = Config::GetGroups();
		$getGroups[0]     = VISITORS;

		$data = $this->ModelsPrefwidgets->getParameters($id);

		$set['pageName']         = defined(strtoupper($data->name)) ? constant(strtoupper($data->name)) : ucfirst($data->name);
		$set['access_groups']    = explode('|', $data->groups_access);
		$set['access_admin']     = explode('|', $data->groups_admin);
		$set['getGroups']        = $getGroups;
		$set['data']             = $data;
		$set['pages']            = explode('|', $data->pages);

		$this->set($set);
		$this->render('accessgroups');
	}

	public function prefaccesssend ()
	{
		$this->internManagement(true);
		$return = $this->ModelsPrefwidgets->sendParameters($_POST);
		$this->error($this->name, $return['text'], $return['type']);
		$this->redirect('PrefWidgets?management', 2);
	}

}
