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

	public function index ()
	{
		$where = "WHERE `groups` LIKE '%3%'";
		$this->pagination = Common::PaginationHtml(15, TABLE_USERS, $where);
		$this->data = parent::GetUsers(3);
	}
	public function view ()
	{
		$id = constant('GET_ID');
		if ($id !== false) {
			$user = User::getInfosUser($id, true);
			$groups = (array) $GLOBALS['GROUPS'];
			foreach ($user['groups'] as $k => $v) {
				$user['groups'][$k] = $groups[$v];
			}
			$this->data = $user;
			$this->data['forum'] = parent::GetLastPost($this->data['hash_key']);
		} else {
			Common::Redirect('Members', 2);
			$this->view = array(ERROR, 'Aucun Membres', 'red');
		}
	}
}