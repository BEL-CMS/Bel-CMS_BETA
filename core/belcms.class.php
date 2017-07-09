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
		new config;
	}

	function _init ()
	{
		ob_start();

		new AutoUser;

		if (isset($_SESSION['MANAGEMENT']) && $_SESSION['MANAGEMENT'] === true && AutoUser::isLogged() === false) {
			common::Redirect('User/Login');
			return;
		}
		if (defined('MANAGEMENT')) {
			$managements = New Managements;
			if ($this->IsJquery === true) {
				if (isset($managements->jquery['redirect'])) {
					$data['redirect'] = $managements->jquery['redirect'];
				}
				$data['type'] = $managements->jquery['type'];
				$data['text'] = $managements->jquery['text'];
				echo json_encode($data);
			} else {
				echo $managements->return;
			}
		} else {
			self::loadController();

			if ($this->IsJquery === true) {
				echo json_encode($this->controller->jquery);
			} else if ($this->IsEcho() === true) {
				echo $this->controller->affiche;
			} else {
				self::getTemplate($this->_page);
				echo $this->_template;
			}

			$this->render = ob_get_contents();
			$this->load = number_format(microtime(true)-$GLOBALS['timestart'], 3);

			if (ob_get_length() != 0) { 
				ob_end_clean();
			}
		}
	}


	private function loadController ()
	{
		ob_start();

		$dir = DIR_PAGES.$this->controller.DS.'controller.php';
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
		$template = new template(); // tpl perso en param (futur)
		$template->page($page);
		$this->_template = $template->render();
	}
}