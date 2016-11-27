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

class Widgets
{
	public  $title,
			$view;
	private $widgets,
			$content,
			$pos = null,
			$ajax,
			$data;

	function __construct($pos = null, $name = false, $ajax = false)
	{
		/* BDD */
		$BDD = New BDD();
		$BDD->table('TABLE_WIDGETS');
		$BDD->fields(array('name','title','groups_access','pos', 'pages'));

		if ($name !== false) {
			$pos = null;
			$tmp_where[] = array(
				'name'  => 'name',
				'value' => $name
			);
		}
		if ($name !== false) {
			$tmp_where[] = array(
				'name'  => 'activate',
				'value' => 1
			);

		}

		if ($pos !== null) {
			if ($pos == 'top' or $pos == 'right' or $pos == 'bottom' or $pos == 'left') {
				$tmp_where[] = array(
					'name'  => 'pos',
					'value' => $pos
				);
				$this->pos = $pos;
			}
		}

		$BDD->where($tmp_where);
		$BDD->orderby(array(array('name' => 'orderby', 'type' => 'ASC')));
		$BDD->queryAll();
		$this->widgets = $BDD->data;

		foreach ($this->widgets as $k => $v) {
			if ($v->groups_access != 0) {
				if ($_SESSION['user'] === false) {
					unset($this->widgets[$k]);
				} else {
					$groups_access = explode('|', $v->groups_access);
					$ok = false;
					foreach ($groups_access as $key => $value) {
						if (in_array($value, $_SESSION['user']->groups)) {
							$ok = true;
							break;
						}
					}
					if ($ok === false) {
						unset($this->widgets[$k]);
					}
				}
			}
		}

		foreach ($this->widgets as $k => $v) {
			$access_pages = explode('|', $v->pages);
			if (!in_array(GET_PAGE, $access_pages)) {
				unset($this->widgets[$k]);
			}
			unset($access_pages);
		}

		unset($BDD);
		/* END BDD */
		$this->ajax = $ajax;
		self::view($name);
	}

	public function view ($name) {
		$return = array();
		if ($name !== false) {

		}

		foreach ($this->widgets as $k => $v) {
			if (!empty($v->title)) {
				$this->title = $v->title;
			}
			echo self::file($v->name);
		}
	}

	private function file ($widget_title = null) {
		ob_start();
		$dir = ROOT_HTML_DFT.'widgets'.DS.'index.php';
		$dir_custom = ROOT_HTML.CMS_WEBSITE_NAME.DS.'custom'.DS.'widgets_index.php';

		if ($this->pos !== null) {
			$dir_custom_pos = ROOT_HTML.CMS_WEBSITE_NAME.DS.'custom'.DS.'widgets_'.$this->pos.'.php';
		} else {
			$dir_custom_pos = $dir_custom;
		}

		$widget_content = self::content($widget_title);

		if (!empty($this->title)) {
			$widget_title = $this->title;
			unset($this->title);
		}

		if (!empty($widget_content)) {
			if (is_file($dir_custom_pos)) {
				require $dir_custom_pos;
			} else if (is_file($dir_custom)) {
				require $dir_custom;
			} else if (is_file($dir)) {
				require $dir;
			} else {
				New Notification('Widgets', 'Error: require dir');
			}
		}

		$view = ob_get_contents();

		ob_end_clean();
		return $view;
	}
	private function content ($name = false, $custom = false) {
		ob_start();
		$dir = ROOT_WIDGETS.$name.DS.'index.php';
		$custom = ROOT_WIDGETS.$name.DS.$custom;
		$this->data = self::GetController($name);
		if ($custom !== false and is_file($custom)) {
			require $custom;
		} else if (is_file($dir)) {
			require $dir;
		} else {
			New Notification('Widgets', 'Error: Page content no found');
		}
		$content = ob_get_flush();
		ob_clean();
		return $content;
	}
	private function GetController ($name) {
		$dir = ROOT_WIDGETS.$name.DS.'controller.php';
		if (is_file($dir)) {
			$nameModel = 'WidgetsModel'.ucfirst($name);
			if (!class_exists($nameModel)) {
				self::GetModel($name);
			}
			require_once $dir;
			if ($this->ajax !== false) {
				$name = $this->ajax;
			}
			$nameController = 'WidgetsController'.ucfirst($name);
			$controller = New $nameController($name);
			return $controller->data;
		}
	}
	private function GetModel ($name) {
		$dir = ROOT_WIDGETS.$name.DS.'model.php';
		if (is_file($dir)) {
			require_once $dir;
		}
	}
}
