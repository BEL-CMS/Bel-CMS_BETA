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

class PrefPages extends Pages
{
	var $models = array('ModelsPrefPages');
	var $intern = 'true';

	public function __construct ()
	{
		parent::__construct();
		$this->name = defined('MANAGEMENT_TITLE_NAME') ? MANAGEMENT_TITLE_NAME : get_class($this);
	}

	public function index ()
	{
		$set['formPages']   = $this->ModelsPrefPages->getFormPages();
		$this->set($set);
		$this->render('index');
	}

	public function pageaccess ($id)
	{
		$this->internManagement(true);
		$id = (int) $id;
		$set = $this->ModelsPrefPages->getConfig($id);
		Common::Constant('MANAGEMENT_OPTIONAL_DESCRIPTION', $set['traduct']);
		$this->set($set);
		$this->render('accesspages');
	}

	public function prefaccesssend ()
	{
		$this->internManagement(true);
		$return = $this->ModelsPrefPages->sendPage($_POST);
		$this->error($this->name, $return['text'], $return['type']);
		$this->redirect('PrefPages?management', 3);
	}

}
