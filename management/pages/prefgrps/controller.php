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

class PrefGrps extends Pages
{
	var $models = array('ModelsPrefGrps');
	var $intern = 'true';

	public function __construct ()
	{
		parent::__construct();
		$this->name = defined('MANAGEMENT_TITLE_NAME') ? MANAGEMENT_TITLE_NAME : get_class($this);
	}

	public function index ()
	{
		$count  = $this->ModelsPrefGrps->GetCountGrps();
		$groups = $this->ModelsPrefGrps->GetGroups();
		foreach ($groups as $k => $v) {
			$set['groups'][$k] = $v;
			$set['groups'][$k]->name  = defined($v->name) ? constant($v->name) : (string) ucfirst($v->name);
			$set['groups'][$k]->count = $count[$v->id_group];
		}
		$this->set($set);
		$this->render('index');
	}

	public function edit ($id)
	{
		Common::Constant('MANAGEMENT_OPTIONAL_DESCRIPTION', EDIT);
		$set['group'] = $this->ModelsPrefGrps->GetGroups($id);
		$this->set($set);
		$this->render('edit');
	}

	public function sendedit ($id)
	{
		$return = $this->ModelsPrefGrps->EditGroup($id);
		$this->error($this->name, $return['text'], $return['type']);
		$this->redirect('Prefgrps?management', 2);
	}

	public function add ()
	{
		Common::Constant('MANAGEMENT_OPTIONAL_DESCRIPTION', ADD);
		$this->render('add');
	}

	public function sendadd ()
	{
		$return = $this->ModelsPrefGrps->AddGroup($this->data);
		$this->error($this->name, $return['text'], $return['type']);
		$this->redirect('Prefgrps?management', 2);
	}

	public function del ($id = false)
	{
		$return = $this->ModelsPrefGrps->DelGroup($id);
		$this->error($this->name, $return['text'], $return['type']);
		$this->redirect('Prefgrps?management', 2);
	}
}
