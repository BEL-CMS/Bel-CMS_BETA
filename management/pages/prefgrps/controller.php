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

class Prefgrps extends Pages
{
	var $models = array('ModelsGrpsAccess');
	var $intern = 'true';

	public function index ()
	{
		$set['formWidgets'] = $this->ModelsGrpsAccess->getFormWidgets();
		$this->set($set);
		$this->render('index');
	}

	public function parameter ($id)
	{
		$this->internManagement(true);
		$id = (int) $id;

		$getGroups        = Config::GetGroups();
		$getGroups[0]     = GUEST;

		$data = $this->ModelsGrpsAccess->getParameters($id);

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
		$return = $this->ModelsGrpsAccess->sendParameters($_POST);
		$this->error('Paramètres groupe', $return['text'], $return['type']);
		$this->redirect('prefgrps?management', 2);
	}

}
