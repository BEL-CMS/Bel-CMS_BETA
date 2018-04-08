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

class Comments extends Pages
{
	var $models = array('ModelsComments');

	public function send ()
	{
		if (AutoUser::ReturnUser() !== false) {
			if (empty($_POST['text'])) {
				$this->jquery = array('type' => 'danger', 'text' => COMMENT_EMPTY );
				return;
			}
			if (empty($_POST['url'])) {
				$this->jquery = array('type' => 'danger', 'text' => URL_EMPTY );
				return;
			}
			$insert = $this->ModelsComments->insertComment($this->data);
			if ($insert === false) {
				$this->jquery = array('type' => $insert['type'], 'text' => $insert['text'] );
			} else {
				$this->jquery = array('type' => $insert['type'], 'text' => $insert['text'] );
			}
		}
	}
}
