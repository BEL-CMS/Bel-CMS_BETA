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

final class Template extends Dispatcher
{
	const DEFAULT_TPL = 'default';
	var     $_file;
	private $_name,
			$_host;

	private static $authorizedVar = array (
		'css',
		'js',
		'keywords',
		'description',
		'title',
		'base_href',
		'host',
		'breadcrumb',
		'username',
		'avatar',
	);

	function __construct($custom = false)
	{
		parent::__construct();

		self::nameTpl($custom);

		$this->title           = CMS_WEBSITE_NAME;
		$this->keywords        = CMS_WEBSITE_KEYWORDS;
		$this->description     = CMS_WEBSITE_DESCRIPTION;
		$this->host            = $this->_host;
		$this->base_href       = GetHost::getBaseUrl ();
		$this->dir             = self::TplName ();
		$this->css             = self::CascadingStyleSheets ();
		$this->js              = self::JavaScript ();
		$this->breadcrumb      = self::BreadCrumb ();
		$this->full_page       = self::fullPage();
		$this->isAdmin         = true;
		if (AutoUser::isLogged() === true) {
			$user = AutoUser::getNameAvatar($_SESSION['user']->hash_key);
			$this->avatar   = $_SESSION['user']->avatar;
			$this->username = $_SESSION['user']->username;

		} else {
			$this->avatar   = 'assets/imagery/default_avatar.jpg';
			$this->username = UNKNOWN;
		}
	}

	public function page ($page) {
		$this->_page = $page;
	}

	public function nameTpl ($name = false)
	{
		if ($name !== false) {
			$this->_name = $name;
			$this->_host = DIR_TPL.$name.DS;
		} else {
			$cms_tpl_website = strlen(constant('CMS_TPL_WEBSITE') > 0) ? false : true;
			if ($cms_tpl_website) {
				$this->_name = constant('CMS_TPL_WEBSITE');
				$this->_host = ROOT_HTML.constant('CMS_TPL_WEBSITE').DS;
			} else {
				$this->_name = self::DEFAULT_TPL;
				$this->_host = ROOT_HTML_DFT.self::DEFAULT_TPL;
			}
		}
	}

	#########################################
	# Vérification de la disponibilite du nom
	#########################################
	private function TplName ()
	{
	}

	private function getFile ()
	{
		$file = DIR_TPL.$this->_name.DS.'template.php';
		if (is_file($file)) {
			$_file = $file;
		} else {
			$_file = DIR_ASSET_TPL.'default'.DS.'template.php';
		}
		return $_file;
	}
	function render ()
	{
		ob_start("ob_gzhandler");
		if (is_file(self::getFile())) {
			require self::getFile();
			$buffer = ob_get_contents();
			foreach (self::$authorizedVar as $var) {
				$buffer = str_replace('{'.trim($var).'}', $this->$var, $buffer);
			}
		} else {
			$error_name    = 'template file no found';
			$error_content = '<strong>file template no found : </strong> : <br>'.$_file.'<br>';
			require DIR_ASSET_TPL.'error'.DS.'404.php';
			$buffer = ob_get_contents();
		}
		ob_end_clean();
		return $buffer;
	}
	#########################################
	# Gestions des styles (css)
	#########################################
	public function cascadingStyleSheets ()
	{
		$files          = array();
		$return         = '';
		/* widgets */
		/* BDD */
		$sql = New BDD();
		$sql->table('TABLE_WIDGETS');
		$sql->fields(array('name','title','groups_access'));
		$sql->queryAll();
		foreach ($sql->data as $k => $v) {
			$file_widgets = DIR_WIDGETS.$v->name.DS.'css'.DS.'styles.css';
			if (is_file($file_widgets)) {
				$files[] = 'widgets/'.$v->name.'/css'.DS.'styles.css';
			}
		}
		unset($sql);
		/* global cms css */
		$files[] = 'assets/styles/global.css';
		// notification */
		$files[] = 'assets/styles/notification.css';
		/* flag svg css */
		$files[] = 'assets/styles/flag-icon.min.css';
		/* plugins css */
		if (CMS_JQUERY_UI == 'on') {
			$files[] = 'assets/plugins/jquery_ui/jquery-ui.min.css';
			$files[] = 'assets/plugins/jquery_ui/jquery-ui.structure.min.css';
		}
		if (CMS_BOOTSTRAP == 'on') {
			$files[] = 'assets/plugins/bootstrap/css/bootstrap.min.css';
		}

		$files[] = 'assets/plugins/font-awesome/css/font-awesome.min.css';
		$files[] = 'assets/plugins/animate/animate.min.css';
		$files[] = 'assets/plugins/animate/animate.delay.css';

		if (is_file(ROOT.'pages'.DS.$this->controller.DS.'css'.DS.'styles.css')) {
			$files[] = 'pages'.DS.$this->controller.DS.'css'.DS.'styles.css';
		}

		foreach ($files as $v) {
			$return .= '	<link href="'.$v.'" rel="stylesheet" type="text/css" media="all">'.PHP_EOL;
		}

		return trim($return);

	}
	#########################################
	# Gestions des scripts (js)
	#########################################
	public function javaScript ()
	{
		$files          = array();
		$return         = '';
		/* widgets */
		/* BDD */
		$sql = New BDD();
		$sql->table('TABLE_WIDGETS');
		$sql->fields(array('name','title','groups_access'));
		$sql->queryAll();
		/* plugins js */
		if (CMS_JQUERY == 'on') {
			$files[] = 'assets/plugins/jquery/jquery-3.3.1.min.js';
		}
		if (CMS_JQUERY_UI == 'on') {
			$files[] = 'assets/plugins/jquery_ui/js/jquery-ui.min.js';
		}
		if (CMS_BOOTSTRAP == 'on') {
			$files[] = 'assets/plugins/bootstrap/js/popper.min.js';
			$files[] = 'assets/plugins/bootstrap/js/bootstrap.min.js';
		}

		$files[] = 'assets/plugins/emojify/emojify.js';

		$files[] = 'assets/plugins/tinymce/jquery.tinymce.min.js';
		$files[] = 'assets/plugins/tinymce/tinymce.min.js';
		$files[] = 'assets/plugins/core.js';

		if (is_file(ROOT.'pages'.DS.$this->controller.DS.'js'.DS.'javascripts.js')) {
			$files[] = 'pages'.DS.$this->controller.DS.'js'.DS.'javascripts.js';
		}

		foreach ($sql->data as $k => $v) {
			$file_widgets = DIR_WIDGETS.$v->name.DS.'js/javascripts.js';
			if (is_file($file_widgets)) {
				$files[] = 'widgets/'.$v->name.'/js/javascripts.js';
			}
		}

		foreach ($files as $v) {
			$return .= '	<script type="text/javascript" src="'.$v.'"></script>'.PHP_EOL;
		}
		return trim($return);

	}
	#########################################
	# Breadcrumb
	#########################################
	public function breadCrumb ()
	{
		$return  = '<nav aria-label="breadcrumb"><ol class="breadcrumb">';
		$return .= '<li class="breadcrumb-item"><a href="Home">'.constant('HOME').'</a></li>';

		if ($this->controller != 'blog') {
			$return .= '<li class="breadcrumb-item"><a href="'.$this->controller.'">'.Common::translate($this->controller).'</a></li>';
			if ($this->view != 'index') {
				$return .= '<li class="breadcrumb-item"><a href="'.ucfirst($this->controller).'/'.$this->view.'">'.Common::translate($this->view).'</a></li>';
				//if (!empty($this->links[2]) && !is_numeric($this->links[2])) {
				if (!empty($this->links[2])) {

					$return .= '<li class="breadcrumb-item active"><a href="'.ucfirst($this->controller).'/'.$this->view.'/'.$this->links[2].'">'.$this->links[2].'</a></li>';
				}
			}
		}
		$return .= '</ol></nav>';
		return $return;
	}
	#########################################
	# Full Page (return Bool)
	#########################################
	public function fullPage ()
	{
		$data = explode(',', strtolower(CMS_TPL_FULL));

		foreach ($data as $k => $v) {
			$data[$k] = strtolower(trim($v));
		}

		$return = (in_array($this->controller, $data)) ? true : false;

		if ($return === false) {
			$return = (in_array($this->view, $data)) ? true : false;
		}

		return $return;
	}
	#####################################
	# Widgets
	#####################################
	function initWidgets ($pos = false, $name = false)
	{
		$sql = New BDD;
		$sql->table('TABLE_WIDGETS');
		$sql->fields(array('name','title','groups_access','pos', 'pages'));
		if ($pos !== false) {
			if ($pos == 'top' or $pos == 'right' or $pos == 'bottom' or $pos == 'left') {
				$tmp_where[] = array(
					'name'  => 'pos',
					'value' => $pos
				);
			}
		}
		if ($name === false) {
			$tmp_where[] = array(
				'name'  => 'activate',
				'value' => 1
			);

		}
		$sql->where($tmp_where);
		$sql->orderby(array(array('name' => 'orderby', 'type' => 'ASC')));
		$sql->queryAll();

		foreach ($sql->data as $k => $v) {
			if ($v->groups_access != 0) {
				if (AutoUser::isLogged() === false) {
					unset($sql->data[$k]);
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
						unset($sql->data[$k]);
					}
				}
			}
		}

		foreach ($sql->data as $k => $v) {
			$access_pages = explode('|', $v->pages);
			if (!empty($v->pages)) {
				if (!in_array($this->controller, $access_pages)) {
					unset($sql->data[$k]);
				}
			}
			unset($access_pages);
		}
		return $sql->data;
	}

	function LoadWidgets($pos)
	{
		$wdigets = self::initWidgets($pos);
		foreach ($wdigets as $k => $v) {
			$dir = DIR_WIDGETS.$v->name.DS.'controller.php';
			if (is_file($dir)) {
				require_once $dir;
			}
			$v->name = new $v->name;
			$v->name->index();
			echo $v->name->widgets;
		}
	}
}
