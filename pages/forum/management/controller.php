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

class ControllerManagementForum extends ModelsManagementForum
{
	public 	$data,
			$view,
			$pagination,
			$error = null;

	public function index ()
	{
		$this->data = parent::GetThreads();
	}

	public function category ()
	{
		$this->data = parent::GetForum();
	}

	public function addforum ()
	{
		$this->data = parent::GetForum();
	}

	public function editforum ()
	{
		$this->data        = parent::GetThreads(GET_ID);
		$this->data->forum = parent::GetForum();
	}

	public function delForum ()
	{
		$this->data = parent::DelThreads(GET_ID);
		Common::Redirect('Forum?management', 2);
	}

	public function send ()
	{
		if ($_POST['send'] == 'addforum') {
			$return = parent::SendAddForum($_POST);
		} else if ($_POST['send'] == 'editforum') {
			$return = parent::SendEditForum($_POST);
		} else if ($_POST['send'] == 'addcat') {
			if (parent::isCat()) {
				$return = parent::SendAddCat($_POST);
			} else {
				$return = array(
					'type' => 'alert',
					'text' => ERROR_NO_CAT
				);
			}
		}
		$this->data = $return;
		Common::Redirect('Forum?management', 2);
	}
}