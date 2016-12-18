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

class ControllerManagementShoutbox extends ModelManagementShoutobx
{
	public 	$data,
			$view,
			$error = null;

	function __construct($id = null)
	{

	}

	public function index ()
	{
		$this->data = parent::getMsg();
	}

	public function edit ()
	{
		$this->data = parent::getMsg(GET_ID);
	}

	public function send ()
	{
		$this->data = parent::sendEditMsg(GET_ID, $_REQUEST['msg']);
		Common::Redirect('shoutbox?Management', 2);
	}
}