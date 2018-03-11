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

class PrefAccess extends Pages
{
	var $models = array('ModelsPrefAccess');
	var $intern = 'true';

	public function index ()
	{
		$set['formPages']   = $this->ModelsPrefAccess->getFormPages();
		$this->set($set);
		$this->render('index');
	}

	public function pageaccess ($id)
	{
		$this->internManagement(true);
		$id = (int) $id;
		$set = $this->ModelsPrefAccess->getConfig($id);
		$this->set($set);
		$this->render('accesspages');
	}

	public function prefaccesssend ()
	{
		$this->internManagement(true);
		$return = $this->ModelsPrefAccess->sendPage($_POST);
		$this->error('Paramètres Page', $return['text'], $return['type']);
		$this->redirect('prefaccess?management', 3);
	}

}
