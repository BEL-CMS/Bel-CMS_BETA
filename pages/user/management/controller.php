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

class ControllerManagementUser extends ModelsManagementUser
{
	public 	$data,
			$view,
			$pagination,
			$error = null;

	function __construct($id = null)
	{
		if (isset($_SESSION['pages']->user->config['MAX_USER_ADMIN'])) {
			$this->nbpp = (int) $_SESSION['pages']->user->config['MAX_USER_ADMIN'];
		} else {
			$this->nbpp = (int) 25;
		}
	}

	public function index ()
	{
		$this->pagination = Common::Pagination($this->nbpp, GET_PAGE, TABLE_USERS);
		$this->data = parent::GetUsers();
	}

	public function edit ()
	{
		$this->data['listSocial'] = parent::ListSocial();
		$this->data['social']     = parent::GetUsersSocial(GET_ID);
		$this->data['private']    = parent::GetUsers(GET_ID);
		$this->data['profil']     = parent::GetUsersProfil(GET_ID);
	}

	public function del ()
	{
		$return = parent::DelUser(GET_ID);
		$this->data = $return;
		Common::Redirect('User?management', 2);
	}

	public function senduser ()
	{
		$return = parent::SendEdit($_POST);
		$this->data = $return;
		Common::Redirect('User?management', 2);
	}

	public function send ()
	{
		if ($_POST['send'] == 'new') {
			$return = parent::SendNew($_POST);
		} else if ($_POST['send'] == 'parameter') {
			$return = parent::UpdateParameter($_POST);
		}
		$this->data = $return;
		Common::Redirect('User?management', 2);
	}
}