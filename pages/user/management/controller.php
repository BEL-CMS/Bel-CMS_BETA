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

class User extends Pages
{
	var $models = array('ModelsUser');

	function __construct($id = null)
	{
		parent::__construct ();
		if (isset($_SESSION['pages']->user->config['MAX_USER_ADMIN'])) {
			$this->nbpp = (int) $_SESSION['pages']->user->config['MAX_USER_ADMIN'];
		} else {
			$this->nbpp = (int) 25;
		}
	}

	public function index ()
	{
		$set['pagination'] = $this->pagination($this->nbpp, GET_PAGE, TABLE_USERS);
		$set['data'] = $this->ModelsUser->GetUsers();
		$this->set($set);
		$this->render('index');
	}

	public function edit ()
	{
		Common::Constant('MANAGEMENT_OPTIONAL_DESCRIPTION', EDIT);
		$set['listSocial'] = $this->ModelsUser->ListSocial();
		$set['social']     = $this->ModelsUser->GetUsersSocial(GET_ID);
		$set['private']    = $this->ModelsUser->GetUsers(GET_ID);
		$set['profil']     = $this->ModelsUser->GetUsersProfil(GET_ID);
		$this->set($set);
		$this->render('edit');
	}

	public function newuser ()
	{
		Common::Constant('MANAGEMENT_OPTIONAL_DESCRIPTION', ADD);
		$this->render('new');
	}

	public function del ()
	{
		$return = $this->ModelsUser->DelUser(GET_ID);
		$this->error(get_class($this), $return['text'], $return['type']);
		$this->redirect('User?management', 2);
	}

	public function parameter ()
	{
		Common::Constant('MANAGEMENT_OPTIONAL_DESCRIPTION', PARAMETER);
		$this->render('parameter');
	}

	public function senduser ()
	{
		$return = $this->ModelsUser->SendEdit($_POST);
		$this->error(get_class($this), $return['text'], $return['type']);
		$this->redirect('User?management', 2);
	}

	public function send ()
	{
		if ($_POST['send'] == 'new') {
			$return = $this->ModelsUser->SendNew($_POST);
		} else if ($_POST['send'] == 'parameter') {
			$return = $this->ModelsUser->UpdateParameter($_POST);
		}
		$this->error(get_class($this), $return['text'], $return['type']);
		$this->redirect('User?management', 2);
	}
}
