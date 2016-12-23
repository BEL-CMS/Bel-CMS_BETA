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

final class ControllerPagesComments extends ModelPagesComments
{
	public 	$data,
			$view;
	private $error = false;

	function __construct()
	{
		$this->user = User::ReturnUser();
	}
	public function send ()
	{
		if ($this->user !== false) {
			if (empty($_POST['text'])) {
				$_SESSION['JQUERY'] = array('type' => 'dander', 'text' => COMMENT_EMPTY);
				$this->error = true;
			}
			if ($this->error === false && empty($_POST['url'])) {
				$_SESSION['JQUERY'] = array('type' => 'dander', 'text' => URL_EMPTY);
				$this->error = true;
			}
			if ($this->error === false) {
				$insert = parent::insertComment($_POST);
				if ($insert === false) {
					$_SESSION['JQUERY'] = array('type' => 'danger', 'text' => COMMENT_SEND_FALSE);
				} else {
					$_SESSION['JQUERY'] = array('type' => $insert['type'], 'text' => $insert['text']);
				}
			}
		}
	}
}
