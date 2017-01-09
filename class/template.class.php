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

final class Template
{
	#########################################
	# Déclaration des variables autorise
	#########################################
	private static $authorizedVar = array (
		'css',
		'js',
		'keywords',
		'description',
		'title',
		'base_href',
		'dir',
		'dir_html',
		'breadcrumb',
		'username',
		'avatar',
		'load'
	);
	public 	$view;

	function __construct($name = false)
	{
		if ($GLOBALS['CONFIG'] === false) {
			New Config;
		}

		$this->name = $name === false ? CMS_TPL_WEBSITE : $name;
		$this->dir             = self::TplName ();
		$this->title           = CMS_WEBSITE_NAME;
		$this->keywords        = CMS_WEBSITE_KEYWORDS;
		$this->description     = CMS_WEBSITE_DESCRIPTION;
		$this->base_href       = BASE_URL;
		$this->css             = self::CascadingStyleSheets ();
		$this->js              = self::JavaScript ();
		$this->breadcrumb      = self::BreadCrumb ();
		if (User::ReturnUser() !== false) {
			User::ResetSession();
			$user = User::ReturnUser();
			if (is_object($user)) {
				$this->avatar          = $user->avatar;
				$this->username        = $user->username;
			} else if (is_array($user)) {
				$this->avatar          = $user['avatar'];
				$this->username        = $user['username'];
			} else {
				$this->avatar          = 'assets/imagery/default_avatar.jpg';
				$this->username        = UNKNOWN;
			}
		} else {
		$this->avatar          = 'assets/imagery/default_avatar.jpg';
		$this->username        = UNKNOWN;
		}
		$this->load            = number_format(microtime(true)-$GLOBALS['timestart'], 3);
		$this->view            = self::View();
	}
	#########################################
	# Vérification de la disponibilite du nom
	#########################################
	private function TplName ()
	{
		$return   = ROOT_TPL_DFT;
		$dir_html = ROOT_HTML_DFT.CMS_DEFAULT_TPL.DS;

		if (!empty($this->name)) {
			if (in_array($this->name, Common::scanDirectory(ROOT_TPL))) {
				$return = ROOT_TPL.CMS_TPL_WEBSITE.DS;
				$dir_html = ROOT_HTML.$this->name.DS;
			}
		}

		$this->dir_html = $dir_html;

		return $return;
	}
	public function View () {
		ob_start("ob_gzhandler");

		self::GetTemplate();

		$buffer = ob_get_contents();

		foreach (self::$authorizedVar as $var) {
			$buffer = str_replace('{'.trim($var).'}', $this->$var, $buffer);
		}

		ob_end_clean();

		return $buffer;

	}

	private function GetTemplate () {
		$scan = Common::ScanDirectory(ROOT_TPL);
		foreach ($scan as $nameTpl) {
			if (in_array($this->name, $scan, true)) {
				$file = ROOT_TPL.$this->name.DS.'template.php';
			} else {
				$file = null;
			}
			if (is_file($file)) {
				require_once $file;
			} else {
				if (!empty($this->name)) {
					new Notification('ERROR', 'require file '.$this->name.' no found', 'red');
				}
				require_once ROOT_TPL_DFT.'default'.DS.'template.php';
			}
		}
	}
	#########################################
	# Gestions des styles (css)
	#########################################
	public function CascadingStyleSheets ()
	{
		$files          = array();
		$return         = '';
		/* widgets */
		/* BDD */
		$BDD = New BDD();
		$BDD->table('TABLE_WIDGETS');
		$BDD->fields(array('name','title','groups_access'));
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
			$file_widgets = ROOT_WIDGETS.$v->name.DS.'css'.DS.'styles.css';
			if (is_file($file_widgets)) {
				$files[] = 'widgets/'.$v->name.'/css'.DS.'styles.css';
			}
		}
		unset($BDD);
		/* global cms css */
		$files[] = 'assets/styles/global.css';
		/* flag svg css */
		$files[] = 'assets/styles/flag-icon.min.css';
		/* plugins css */
		if (CMS_JQUERY_UI == 'on') {
			$files[] = 'assets/plugins/jquery_ui/jquery-ui.min.css';
		}
		if (CMS_BOOTSTRAP == 'on') {
			$files[] = 'assets/plugins/bootstrap/css/bootstrap.min.css';
		}

		$files[] = 'assets/plugins/font-awesome/css/font-awesome.min.css';
		$files[] = 'assets/plugins/animate/animate.min.css';
		$files[] = 'assets/plugins/animate/animate.delay.css';

		if (is_file(ROOT.'pages'.DS.GET_PAGE.DS.'css'.DS.'styles.css')) {
			$files[] = 'pages'.DS.GET_PAGE.DS.'css'.DS.'styles.css';
		}

		foreach ($files as $v) {
			$return .= '	<link href="'.$v.'" rel="stylesheet" type="text/css" media="all">'.PHP_EOL;
		}

		return trim($return);

	}
	#########################################
	# Gestions des scripts (js)
	#########################################
	public function JavaScript ()
	{
		$files          = array();
		$return         = '';
		/* widgets */
		/* BDD */
		$BDD = New BDD();
		$BDD->table('TABLE_WIDGETS');
		$BDD->fields(array('name','title','groups_access'));
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
		unset($BDD);
		/* plugins js */
		if (CMS_JQUERY == 'on') {
			$files[] = 'assets/plugins/jquery/jquery-1.11.1.min.js';
		}
		if (CMS_JQUERY_UI == 'on') {
			$files[] = 'assets/plugins/jquery_ui/jquery-ui.min.js';
		}
		if (CMS_BOOTSTRAP == 'on') {
			$files[] = 'assets/plugins/bootstrap/js/bootstrap.min.js';
		}

		$files[] = 'assets/plugins/tinymce/jquery.tinymce.min.js';
		$files[] = 'assets/plugins/tinymce/tinymce.min.js';
		$files[] = 'assets/plugins/core.js';

		if (is_file(ROOT.'pages'.DS.GET_PAGE.DS.'js'.DS.'javascripts.js')) {
			$files[] = 'pages'.DS.GET_PAGE.DS.'js'.DS.'javascripts.js';
		}

		foreach ($this->widgets as $k => $v) {
			$file_widgets = ROOT_WIDGETS.$v->name.DS.'js/javascripts.js';
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
	# Gestions du breadcrumb
	#########################################
	public function BreadCrumb ()
	{
		$return  = '<ol class="breadcrumb">';
		$return .= '<li><a href="Home">'.constant('HOME').'</a></li>';

		if (!defined(GET_PAGE) || (GET_PAGE != 'index')) {
			$return .= '<li><a href="'.GET_PAGE.'">'.Common::translate(GET_PAGE).'</a></li>';
			if (defined(GET_ACTION) || GET_ACTION != 'index') {
				$return .= '<li><a href="'.ucfirst(GET_PAGE).'/'.GET_ACTION.'">'.Common::translate(GET_ACTION).'</a></li>';
				if (defined(GET_ID)) {
					$return .= '<li><a href="'.ucfirst(GET_PAGE).'/'.GET_ACTION.'/'.GET_ID.'">'.GET_ID.'</a></li>';
				}
			}
		}
		$return .= '</ol>';
		return $return;
	}
	#########################################
	# Full Template (return Bool)
	#########################################
	public static function full ()
	{
		$data = explode(',', strtolower(CMS_TPL_FULL));

		foreach ($data as $k => $v) {
			$data[$k] = strtolower(trim($v));
		}

		$page   = strtolower(constant('GET_PAGE'));
		$action = strtolower(constant('GET_ACTION'));

		$return = (in_array($page, $data)) ? true : false;

		if ($return === false) {
			$return = (in_array($action, $data)) ? true : false;
		}

		return $return;
	}
	public static function isAdmin()
	{
		/*
		if (isset($_COOKIE['BEL-CMS-COOKIE'])) {
			$cookie = explode('###', $_COOKIE['BEL-CMS-COOKIE']);

			$sql = New BDD;
			$sql->table('TABLE_MANAGEMENT');

			$sql->where(array(
				'name'  => 'hash_key',
				'value' => $cookie[1]
			));

			$sql->queryOne();

			if ($sql->rowCount != 0) {
				$return = true;
			} else {
				$return = false;
			}
		} else {
			$return = false;
		}
		return $return;
		*/
		return true;
	}
}
