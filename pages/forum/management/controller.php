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

class ControllerManagementForum extends ModelManagementForum
{
	public 	$data,
			$view,
			$pagination,
			$error = null;

	function __construct()
	{

	}

	public function index ()
	{
		$this->data = parent::GetThreads();
	}

	public function addforum ()
	{
		$this->data = parent::GetForum();
	}

	public function send ()
	{
		if ($_POST['send'] == 'addforum') {
			$return = parent::SendAddForum($_POST);
		}
		$this->data = $return;
		Common::Redirect('Forum?management', 2);
	}
}