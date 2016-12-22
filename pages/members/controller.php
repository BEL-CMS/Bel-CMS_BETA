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

class ControllerPagesMembers extends ModelPagesMembers
{
	public 	$data,
			$view,
			$access = false;

	public function __construct()
	{
		if (isset($_SESSION['pages']->blog->config['MAX_MEMBERS'])) {
			$this->nbpp = (int) $_SESSION['pages']->blog->config['MAX_MEMBERS'];
		} else {
			$this->nbpp = (int) 10;
		}
	}

	public function index ()
	{
		$where = "WHERE `groups` LIKE '%3%'";
		$this->pagination = Common::Pagination($this->nbpp, GET_PAGE, TABLE_USERS, $where);
		$this->data = parent::GetUsers(3);
	}
	public function view ()
	{
		$id = constant('GET_ID');
		if ($id !== false) {
			$user = User::getInfosUser($id, true);
			if ($user['username'] == DELETE) {
				$this->view = array(ERROR, UNKNOW_MEMBER, 'red');
			} else {
				$groups = (array) $GLOBALS['GROUPS'];
				foreach ($user['groups'] as $k => $v) {
					$group = isset($groups[$v]) ? $groups[$v] : UNKNOW_GROUP;
					$user['groups'][$k] = $group;
				}
				$this->data = $user;
				$this->data['forum'] = parent::GetLastPost($this->data['hash_key']);
			}
		} else {
			Common::Redirect('Members', 2);
			$this->view = array(ERROR, 'Aucun Membres', 'red');
		}
	}
	public function AddFriend ()
	{
		$id = constant('GET_ID');
		$user = User::getInfosUser($id, true);
		if ($user['username'] == DELETE) {
			$_SESSION['JQUERY'] = array('type' => 'danger', 'text' => UNKNOW_MEMBER);
		} else {
			parent::addFriendSQL ($user['hash_key']);
			if (parent::addFriendSQL ($user['hash_key'] == null)) {
				$_SESSION['JQUERY'] = array('type' => 'danger', 'text' => ADD_FRIEND_ERROR);
			} else {
				$_SESSION['JQUERY'] = array('type' => 'success', 'text' => ADD_FRIEND_SUCCESS);
			}
		}
	}
}