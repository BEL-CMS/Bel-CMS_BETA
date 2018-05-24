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

class Members extends Pages
{
	var $models = array('ModelsMembers');
	private $_error = false;

	public function __construct()
	{
		parent::__construct();
		if (isset($_SESSION['pages']->user->config['MAX_USER'])) {
			$this->nbpp = (int) $_SESSION['pages']->user->config['MAX_USER'];
		} else {
			$this->nbpp = (int) 10;
		}
	}

	public function index ()
	{
		$set['pagination'] = $this->pagination($this->nbpp, GET_PAGE, TABLE_USERS);
		$set['members'] = $this->ModelsMembers->GetUsers();
		$this->set($set);
		$this->render('index');
	}

	public function view ($name)
	{
		$name = Common::VarSecure($name);
		if ($name !== false) {
			$user = AutoUser::getInfosUser($name, true);
			if ($user->username == DELETE) {
				$this->error(ERROR, UNKNOW_MEMBER);
			} else {
				$groups = Config::GetGroups();
				foreach ($user->groups as $k => $v) {
					$user->groups[$k] = $groups[$v];
				}
				$set['members'] = $user;
				$st2['forum'] = $this->ModelsMembers->GetLastPost($user->hash_key);
				$this->set($set);
				$this->set($st2);
				$this->render('view');
			}
		} else {
			Common::Redirect('Members', 2);
			$this->view = array(ERROR, 'Aucun Membres', 'red');
		}
	}

	public function AddFriend ($id)
	{
		$user = AutoUser::getInfosUser($id, true);
		if ($user->username == DELETE) {
			$this->jquery = array('type' => 'danger', 'text' => UNKNOW_MEMBER);
		} else {
			$return = $this->ModelsMembers->addFriendSQL ($user->hash_key);
			if ($return == null) {
				$this->jquery = array('type' => 'danger', 'text' => ADD_FRIEND_ERROR);
			} else {
				$this->jquery = array('type' => 'success', 'text' => ADD_FRIEND_SUCCESS);
			}
		}
	}

	public function json ()
	{
		$data = $this->ModelsMembers->getJson();
		$this->affiche = json_encode($data);
	}
}
