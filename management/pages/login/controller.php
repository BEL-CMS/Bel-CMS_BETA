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

class Login extends Pages
{
	var $models = array('ModelsLogin');
	var $intern = 'true';

	public function index ()
	{
		$this->internManagement(true);
		$this->render('index');
	}

	public function send ()
	{
		if (!empty($this->data['privatemail']) && !empty($this->data['password'])) {
			$this->jquery = $this->ModelsLogin->login($this->data['privatemail'], $this->data['password']);
		}
	}

	public function logout ()
	{
		$this->jquery = $this->ModelsLogin->logout();
	}
}
