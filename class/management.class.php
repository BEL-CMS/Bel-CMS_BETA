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

$NameManagement = array(
	'admin',
	'Admin',
	'Management',
	'management'
);

foreach ($NameManagement as $k) {
	if (array_key_exists($k, $_REQUEST)) {
		define('MANAGEMENT', true);
		break;
	}
}

require_once ROOT_MANAGEMENT.'model.class.php';

class Management
{
	private $dir = array(
		'css',
		'js',
		'img'
	);

	private $page,
			$require,
			$model,
			$buffer,
			$id,
			$view,
			$action,
			$error = true,
			$dirName = 'management';

	public  $jquery,
			$data = array();

	function __construct()
	{
		if (!isset($_SESSION['LOGIN_MANAGEMENT'])) {
			$_SESSION['LOGIN_MANAGEMENT'] = false;
		}

		if ($GLOBALS['CONFIG'] === false) {
			New Config;
		}

		if (!User::ReturnUser()) {
			Common::redirect('user/login');
			exit;
		}

		require_once ROOT_MANAGEMENT.DS.'lang.php';

		$this->model = New ModelManagement;

		foreach ($this->dir as $key) {
			$this->$key = self::$key();
		}

		$this->view = trim(constant('GET_ACTION'));
		$this->id   = Common::secureRequest(constant('GET_ID'));
		$this->page = strtolower(constant('GET_PAGE'));

		$this->return = self::View();

		$this->pageManagement = ROOT_MANAGEMENT.'page'.DS;
	}

	private function loginManagement () {
		if (GET_PAGE == 'login') {
			if (!empty($_POST['privatemail']) && !empty($_POST['password'])) {
				$this->jquery = $this->model->login($_POST['privatemail'], $_POST['password']);
			}
		}
		require ROOT_MANAGEMENT_TPL.'login.php';
	}
	#####################################
	# Get CSS / JS / IMG / LANG
	#####################################
	private function css ()
	{
		return ROOT_MANAGEMENT_TPL.'css'.DS;
	}
	private function js ()
	{
		return ROOT_MANAGEMENT_TPL.'js'.DS;
	}
	private function img ()
	{
		return ROOT_MANAGEMENT_TPL.'img'.DS;
	}
	private function lang ($file = false)
	{
		if (is_file($file)) {
			include $file;
		}
	}
	#####################################
	# Get View and Test
	#####################################
	private function View ()
	{
		ob_start();

		if ($_SESSION['LOGIN_MANAGEMENT'] !== true) {
			self::loginManagement();
		} else {
			$this->require = GET_PAGE == 'login' ? 'Dashboard' : GET_PAGE;
			if ($this->require == 'Dashboard') Common::Redirect('Dashboard?Management');
			$pageSelf = array('dashboard', 'logout', 'parameter');
			if (in_array($this->page, $pageSelf)) {
				$page = $this->page;
				self::$page();
			} else {
				if ($this->ExistsPages($this->page)) {
					self::PageManagement(ROOT_PAGES.$this->page.DS.$this->dirName);
					self::lang(ROOT_PAGES.$this->page.DS.'lang'.CMS_WEBSITE_LANG.'php');
				} else {
					$error   = 1;
					$existsPages = false;
				}
				if (isset($error)) {
					if ($this->ExistsWidgets($this->page)) {
						self::PageManagement(ROOT_WIDGETS.$this->page.DS.$this->dirName);
						self::lang(ROOT_WIDGETS.$this->page.DS.'lang'.CMS_WEBSITE_LANG.'php');
						unset($error);
					} else {
						$error = 3;
						$existsWidgets = false;
					}
				}
				if (isset($existsPages) and isset($existsWidgets)) {
					self::PageError(5);
					unset($error);
				}
				if (isset($error)) {
					self::PageError($error);
				}
			}
		}

		$buffer = ob_get_contents();

		foreach ($this->dir as $var) {
			$buffer = str_replace('{'.trim($var).'}', $this->$var, $buffer);
		}

		ob_end_clean();

		return $buffer;
	}
	#####################################
	# Get MVC
	#####################################
	private function PageManagement ($d)
	{
		ob_start("ob_gzhandler");

		$error = false;

		$file = $d.DS.$this->view.'.php';
		if (is_file($file)) {
			$file_model      = $d.DS.'model.php';
			$file_controller = $d.DS.'controller.php';
			if (is_file($file_model)) require $file_model;
			if (is_file($file_controller)) {
				include_once $file_controller;
				$NewClassController = 'ControllerManagement'.ucfirst($this->page);
				if (class_exists($NewClassController)) {
					$controller = New $NewClassController($this->id);
					$methode    = $this->view;
					if (method_exists($controller, $methode)) {
						$controller->$methode();
						if ($controller->error !== null) {
							if ($controller->error == 1) {
								self::PageError(1);
							} else if ($controller->error == 2) {
								self::PageError(1);
							} else {
								self::PageError(0);
							}
							$error = true;
						}
						$this->data = $controller->data;
						if (isset($controller->pagination)) {
							$this->pagination = $controller->pagination;
						}
					}
				}
			} else {
				$error = true;
				self::PageError(7);
			}
			if ($error === false) {
				require $file;
				$this->buffer = ob_get_contents();
			}
		} else {
			self::PageError(6);
		}

		ob_end_clean();
		self::html();
	}
	#####################################
	# Main HTML
	#####################################
	private function html() {
		?>
			<!DOCTYPE html>
			<html lang="en">
			<head>
			<meta charset="utf-8">
			<title><?=ucfirst(GET_PAGE)?> - BEL-CMS - MANAGEMENT</title>
			<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
			<meta name="apple-mobile-web-app-capable" content="yes">
			<link href="/{css}bootstrap.min.css" rel="stylesheet">
			<link href="/{css}bootstrap-responsive.min.css" rel="stylesheet">
			<link href="/{css}font-awesome.css" rel="stylesheet">
			<link href="/{css}style.css" rel="stylesheet">
			<link href="/{css}pages/dashboard.css" rel="stylesheet">
			<!--[if lt IE 9]>
			    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
			<![endif]-->
			</head>
			<body>
			<div class="navbar navbar-fixed-top">
				<div class="navbar-inner">
					<div class="container">
						<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</a>
						<a class="brand" href="dashboard?management">BEL-CMS - MANAGEMENT</a>
						<div class="nav-collapse">
							<ul class="nav pull-right">
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown">
										<i class="icon-user"></i> <?=CMS_WEBSITE_NAME?> <b class="caret"></b>
									</a>
									<ul class="dropdown-menu">
										<li><a href="/Home"><?=BACK?></a></li>
										<li><a class="alertAjaxLink" title="" href="/Logout?Management&ajax"><?=SIGN_OUT?></a></li>
									</ul>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>

			<div class="subnavbar">
				<div class="subnavbar-inner">
					<div class="container">
						<ul class="mainnav">
							<li class="active">
								<a href="dashboard?management"><i class="icon-home"></i><span>Dashboard</span></a>
							</li>
							<li>
								<a href="Blog?management"><i class="icon-pencil"></i><span>Blog</span> </a>
							</li>
							<li>
								<a href="Downloads?management"><i class="icon-download-alt "></i><span>Downloads</span> </a>
							</li>
							<li>
								<a href="Video?management"><i class="icon-facetime-video"></i><span>Video</span> </a>
							</li>
							<li>
								<a href="Links?management"><i class="icon-link"></i><span>Links</span> </a>
							</li>
							<li>
								<a href="Gallery?management"><i class="icon-picture"></i><span>Gallery</span> </a>
							</li>
							<li class="dropdown">
								<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"> <i class="icon-long-arrow-down"></i>
									<span>Pages</span> <b class="caret"></b>
								</a>
								<ul class="dropdown-menu">
								<?php
								foreach ($this->ExistsPages() as $name => $access):
									$traduct = defined(strtoupper($name)) ? constant(strtoupper($name)) : $name;
									if ($name != 'dashboard') {
										if ($access === true):
											echo '<a href="'.$name.'?Management">'.$traduct.'</a>';
										endif;
									}
								endforeach;
								?>
								</ul>
							</li>
							<li class="dropdown">
								<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"> <i class="icon-long-arrow-down"></i>
									<span>Widgets</span> <b class="caret"></b>
								</a>
								<ul class="dropdown-menu">
								<?php
								foreach ($this->ExistsWidgets() as $name => $access):
									$traduct = defined(strtoupper($name)) ? constant(strtoupper($name)) : $name;
									if ($name != 'dashboard'):
										if ($access === true):
											echo '<a href="'.$name.'?Management">'.$traduct.'</a>';
										endif;
									endif;
								endforeach;
								?>
								</ul>
							</li>
						</ul>
					</div>
				</div>
			</div>

			<?=$this->buffer?>

			<div class="footer">
				<div class="footer-inner">
					<div class="container">
						<div class="row">
							<div class="span12">
								<span style="float: right;"><?= date('d / m / Y', time()); ?></span>
								<a style="float: left;" href="http://bel-cms.be"> &copy; Bel-CMS.BE</a>
							</div>
						</div>
					</div>
				</div>
			</div>

			<script src="/{js}jquery-1.7.2.min.js"></script>
			<script src="/{js}excanvas.min.js"></script>
			<script src="/{js}chart.min.js" type="text/javascript"></script>
			<script src="/{js}bootstrap.js"></script>
			<script src="/{js}base.js"></script>
			<script src="../../assets/plugins/core.js"></script>

			</body>
			</html>
		<?php
	}
	#####################################
	# Scan Page & Widgets / Access
	#####################################
	private function ExistsPages($search = false) {

		$scanDir = Common::ScanDirectory(ROOT_PAGES);
		$pages	 = array();

		foreach ($scanDir as $k => $v) {
			if (
				file_exists(ROOT_PAGES.$v.DS.$this->dirName) && 
				file_exists(ROOT_PAGES.$v.DS.$this->dirName.DS.'index.php') &&
				file_exists(ROOT_PAGES.$v.DS.$this->dirName.DS.'controller.php')
			   ) {
				$pages[] = $v;
			}
		}

		$access = (object) array();

		$BDD = New BDD;
		$BDD->table('TABLE_MANAGEMENT');
		$BDD->where(array('name' => 'hash_key', 'value' => $_SESSION['user']->hash_key));
		$BDD->fields(array('pages'));
		$BDD->queryOne();
		$results = $BDD->data;

		if ($results) {
			$returnPage = explode('|', $results->pages);
			foreach ($pages as $p) {
				if ($p == 'dashboard') {
					$access->$p = (bool) true;
				} else {
					if (in_array($p, $returnPage)) {
						$access->$p = (bool) true;
					} else {
						$access->$p = (bool) false;
					}
				}
			}
		}

		if ($search !== false) {
			foreach ($pages as $p) {
				if ($p == $search) {
					return true;
					break;
				}
			}
			return false;
		}

		return $access;

	}
	private function ExistsWidgets($search = false) {

		$scanDir = Common::ScanDirectory(ROOT_WIDGETS);

		$widgets = array();

		foreach ($scanDir as $k => $v) {
			if (
				file_exists(ROOT_WIDGETS.$v.DS.$this->dirName) && 
				file_exists(ROOT_WIDGETS.$v.DS.$this->dirName.DS.'index.php') &&
				file_exists(ROOT_WIDGETS.$v.DS.$this->dirName.DS.'controller.php')
			   ) {
				$widgets[] = $v;
			}
		}

		$access  = (object) array();

		$BDD = New BDD;
		$BDD->table('TABLE_MANAGEMENT');
		$BDD->where(array('name' => 'hash_key', 'value' => $_SESSION['user']->hash_key));
		$BDD->fields(array('widgets'));
		$BDD->queryOne();
		$results = $BDD->data;

		if ($results) {
			$returnWidgets = explode('|', $results->widgets);
			foreach ($widgets as $p) {
				if (in_array($p, $returnWidgets)) {
					$access->$p = (bool) true;
				} else {
					$access->$p = (bool) false;
				}
			}
		}

		if ($search !== false) {
			foreach ($widgets as $p) {
				if ($p == $search) {
					return true;
					break;
				}
			}
			return false;
		}

		return $access;
	}
	#####################################
	# Error
	#####################################
	private function PageError ($error = '404') {
		// 1 = no exist page
		// 2 = no access page
		// 3 = no existe widgets
		// 4 = no access widgets
		// 5 = no exist page or widgets
		// 6 = no file exist
		// controller = controller file no exist
		// inner  = error internal file
		// 404 default
		if ($this->error === true) {
			ob_start();
			if ($error == '404') {
				$title = '404';
			} else if ($error == 1) {
				$title = 'The requested page does not exist';
			} else if ($error == 2) {
				$title = 'The requested page does not access';
			} else if ($error == 3) {
				$title = 'The requested widgets does not exist';
			} else if ($error == 4) {
				$title = 'The requested widgets does not access';
			} else if ($error == 5) {
				$title = 'The requested widgets or page does not exist';
			} else if ($error == 6) {
				$title = 'The requested file does not exist';
			} else if ($error == 'controller') {
				$title = 'The requested controller does not exist';
			} else if ($error == 'inner') {
				$title = 'The requested internal page does not exist';
			} else {
				$title = ERROR_UNKNOWN_MANAGEMENT;
			}
			require ROOT_MANAGEMENT_TPL.'error.php';
			$this->buffer = ob_get_contents();
			ob_end_clean();
			self::html();
			$this->error = false;
		}
	}
	#####################################
	# Internal page
	#####################################
	private function dashboard()
	{
		ob_start("ob_gzhandler");

		if (is_file(ROOT_MANAGEMENT.$this->page.'.php')) {
			require ROOT_MANAGEMENT.$this->page.'.php';
			$this->buffer = ob_get_contents();
		} else {
			self::PageError('inner');
		}

		ob_end_clean();
		self::html();
	}
	private function logout ()
	{
		$this->jquery = $this->model->logout();
	}
	private function parameter()
	{
		if (!empty($_POST)) {
			foreach ($_POST as $k => $v) {
				if ($k == 8) {
					$v = implode(',', $v);
				}
				$sql = New BDD();
				$sql->table('TABLE_CONFIG');
				$sql->where(array('name'=>'id','value'=>$k));
				$sql->sqlData(array('value' => $v));
				$sql->update();
				unset($sql);
			}

			$save = array(
				'type' => 'success',
				'text' => SAVE_BDD_SUCCESS
			);

			Common::Redirect('Parameter?management', 2);

		} else {
			$formText     = array('CMS_WEBSITE_NAME', 'CMS_WEBSITE_DESCRIPTION', 'CMS_MAIL_WEBSITE', 'CMS_WEBSITE_KEYWORDS');
			$formRadio    = array(
				'CMS_JQUERY'      => array('on', 'off'),
				'CMS_JQUERY_UI'   => array('on', 'off'),
				'CMS_BOOTSTRAP'   => array('on', 'off'),
				'CMS_TPL_WEBSITE' => self::getTpl()
			);
			$formCheckbox = array(
				'CMS_TPL_FULL'    => Common::ScanDirectory(ROOT_PAGES)
			);

			$sql = New BDD();
			$sql->table('TABLE_CONFIG');
			$sql->orderby(array(array('name' => 'name', 'type' => 'DESC')));
			$sql->queryAll();
			$return = $sql->data;

			$form  = '';

			foreach ($return as $d) {
				$input = '';
				$name  = (defined('ADMIN_'.$d->name)) ? constant('ADMIN_'.$d->name) : $d->name;
				$help  = (defined('ADMIN_'.$d->name.'_HELP')) ? constant('ADMIN_'.$d->name.'_HELP') : null;
				if (in_array($d->name, $formText)) {
					$input = '<input name="'.$d->id.'" type="text" class="span6" id="label_'.$d->id.'" value="'.$d->value.'">';
				} else if (array_key_exists($d->name, $formRadio)) {
					foreach ($formRadio[$d->name] as $a) {
						$checked = $a == $d->value ? 'checked="checked"' : '';
						$input .= '<label class="radio inline">';
						$input .= '<input '.$checked.' name="'.$d->id.'" type="radio" id="label_'.$d->id.'" value="'.$a.'">'.$a;
						$input .= '</label>';
					}
				} else if (array_key_exists($d->name, $formCheckbox)) {
					$value = explode(',', $d->value);
					foreach ($formCheckbox[$d->name] as $a) {
						$checked = in_array($a, preg_replace('/\s+/', '', $value)) ? 'checked="checked"' : '';
						$input .= '<label class="checkbox inline">';
						$input .= '<input '.$checked.' name="'.$d->id.'[]" type="checkbox" id="label_'.$d->id.'" value="'.$a.'">'.$a;
						$input .= '</label>';
					}
				} else {
					$input = '<input name="'.$d->id.'" type="text" class="span6" id="label_'.$d->id.'" value="'.$d->value.'">';
				}
				$form .= '<div class="control-group">';
				$form .= '<label class="control-label" for="label_'.$d->id.'">'.$name.'</label>';
				$form .= '<div class="controls">';
				$form .= $input;
				$form .= $help;
				$form .= '</div>';
				$form .= '</div>';
				unset($name,$help, $input);
			}
		}

		ob_start("ob_gzhandler");

		if (is_file(ROOT_MANAGEMENT.$this->page.'.php')) {
			require ROOT_MANAGEMENT.$this->page.'.php';
			$this->buffer = ob_get_contents();
		} else {
			self::PageError('inner');
		}

		ob_end_clean();
		self::html();
	}
	private function getTpl ()
	{
		$return = Common::ScanDirectory(ROOT_TPL);

		if (count($return) !== 0) {
			foreach ($return as $k => $n) {
				if (!is_file(ROOT_TPL.$n.DS.'template.php')) {
					unset($return[$k]);
				}
			}
		}

		return $return;
	}
}
