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

final class BelCMS extends Dispatcher
{
	private $_page,
			$_template;
	public  $render = null,
		    $load;

	function __construct ()
	{
		parent::__construct();
		new Config;
		if ($this->controller != 'shoutbox') {
			new Visitors;
		}
		$this->page = $this->controller;
	}

	function _init ()
	{
		ob_start();

		new AutoUser;

		if (isset($_SESSION['MANAGEMENT']) && $_SESSION['MANAGEMENT'] === true && AutoUser::isLogged() === false) {
			common::Redirect('User/Login');
			return;
		}

		self::loadController();

		if ($this->IsJquery === true) {
			echo json_encode($this->controller->jquery);
		} else if ($this->IsEcho() === true) {
			echo $this->controller->affiche;
		} else {
			if (defined('MANAGEMENT')) {
				$full = false;
				if (strtolower($this->page) == 'login') {
					$full = true;
				}
				self::getManagement($this->_page, $full);
			} else {
				self::getTemplate($this->_page);
			}
			echo $this->_template;
		}

		$this->render = ob_get_contents();

		if (ob_get_length() != 0) { 
			ob_end_clean();
		}
	}


	private function loadController ()
	{
		ob_start();

		if (defined('MANAGEMENT')) {
			$arrayIntern = array('login', 'dashboard', 'prefgen', 'prefaccess', 'prefgrps');
			if (in_array($this->controller, $arrayIntern)) {
				$dir = ROOT_MANAGEMENT.'pages'.DS.$this->controller.DS.'controller.php';
			} else {
				$dir = DIR_PAGES.$this->controller.DS.'management'.DS.'controller.php';
			}
		} else {
			$dir = DIR_PAGES.$this->controller.DS.'controller.php';
		}

		if (is_file($dir)) {
			require $dir;
			$this->controller = new $this->controller();
			if (method_exists($this->controller, $this->view)) {
				unset($this->links[0], $this->links[1]);
				call_user_func_array(array($this->controller,$this->view),$this->links);
				$this->_page = $this->controller->page;
			} else {
				$error_name    = 'Methode of controller no found';
				$error_content = 'function <strong>'.$this->view.'</strong> in controller no found';
				require DIR_ASSET_TPL.'error'.DS.'404.php';
				$buffer = ob_get_contents();
				$this->_page = $buffer;
			}
		} else {
			ob_start();
			$error_name    = 'Page no found';
			$error_content = '<strong>file controller and view no found : </strong>';
			require DIR_ASSET_TPL.'error'.DS.'404.php';
			$buffer = ob_get_contents();
			$this->_page = $buffer;
		}

		if (ob_get_length() != 0) { 
			ob_end_clean();
		}
	}


	private function getTemplate ($page = null)
	{
		$template = new Template; // tpl perso en param (futur)
		$template->page($page);
		$this->_template = $template->render();
	}

	private function getManagement ($page = null, $full = false)
	{
		$management = new Management;
		$management->full($full);
		$management->page($page);
		$this->_template = $management->render();
	}
}