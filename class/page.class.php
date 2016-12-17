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

class Page
{
	public 	$data;
	private $page,
			$view,
			$id,
			$access,
			$error;

	function __construct($page = null, $view = null, $id = null)
	{
		if ($GLOBALS['CONFIG'] === false) {
			New Config;
		}

		$this->view = $view ? trim($view) : trim(constant('GET_ACTION'));
		$this->id   = $id   ? Common::secureRequest($id) : trim(constant('GET_ID'));

		if (defined('HOME_PAGE')) {
			if (is_file(ROOT_TPL.CMS_TPL_WEBSITE.'home.tpl.php')) {
				include ROOT_TPL.CMS_TPL_WEBSITE.'home.tpl.php';
			} else {
				if ($page == null) {
					self::TestPage(constant('GET_PAGE'));
				} else {
					self::TestPage(trim($page));
				}
			}
		} else {
			Notification::newPage('ERROR', 'Error: Page no found');
		}

		self::GetPage($page);
	}

	private function TestPage ($page) {
		if (Common::ExistsPage($page)) {
			$this->page   = $page;
			$this->access = Common::ExistsSubPage($this->page, $this->view);
			if ($this->access['model']) {
				self::PageModel();
			}
			if ($this->access['controller']) {
				self::PageController();
			} else {
				New Notification (ERROR, 'the controller is not found', 'red', false, false);
				$this->error = true;
			}
			if ($this->access['view']) {
				if (GET_PAGE != 'page') {
					if ($this->error === false) {
						self::PageView();
					} else {
						self::Notification();
					}
				} else if (GET_PAGE == 'page') {
					require ROOT_PAGES.$this->page.DS.'view.index.php';
				}
			} else {
				if ($this->error === false) {
					New Notification (ERROR, 'the view is not found', 'red');
				}
			}
		} else {
			New Notification (ERROR, 'Unknown page', 'red');
		}
	}

	private function GetPage ($page)
	{
		if ($page !== null) {
			$this->page = GET_PAGE;
		} else {
			if (in_array(strtolower($page), Common::ScanDirectory(ROOT_PAGES))) {
				$this->page;
			} else {
				$this->page = GET_PAGE;
			}
		}

	}

	private function PageModel ()
	{
		if (is_file(ROOT_PAGES.$this->page.DS.'model.php')) {
			require ROOT_PAGES.$this->page.DS.'model.php';
		}
	}

	private function PageView ()
	{
		$customPage = ROOT_TPL.CMS_TPL_WEBSITE.DS.'pages'.DS.$this->page.'.'.$this->view.'.php';
		if (is_file($customPage)) {
			require $customPage;
		} else {
			require ROOT_PAGES.$this->page.DS.'view.'.$this->view.'.php';
		}

	}

	private function PageController ()
	{
		include_once ROOT_PAGES.$this->page.DS.'controller.php';
		$NewClassController = 'ControllerPages'.$this->page;
		if (class_exists($NewClassController)) {
			$controller = New $NewClassController($this->id);
			$methode    = $this->view;
			if (GET_PAGE != 'page') {
				if (method_exists($controller, $methode)) {
					$controller->$methode();
					if (!empty($controller->access)) {
						$this->error = $controller->access;
					} else if (empty($controller->view)) {
						$this->data  = $controller->data;
						if (isset($controller->pagination)) {
							$this->pagination = $controller->pagination;
						}
						$this->error = false;
					} else {
						$this->data  = array();
						$this->error = $controller->view;
					}
				} else {
					New Notification ($this->page, 'Aucun sous-page existe pour cette categorie', 'red');
					$this->error = true;
				}
			} else if (GET_PAGE == 'page') {
				$this->data = $controller->data;
			}
		}
	}

	private function Notification ()
	{
		New Notification ($this->error[0], $this->error[1], $this->error[2]);
	}

}
