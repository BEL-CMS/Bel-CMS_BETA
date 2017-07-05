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

class Managements
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
			$action,
			$error = true,
			$dirName = 'management';

	public  $jquery,
			$data = array();

	function __construct()
	{
		new Access();

		if (!isset($_SESSION['LOGIN_MANAGEMENT'])) {
			$_SESSION['LOGIN_MANAGEMENT'] = false;
		}

		if (!AutoUser::ReturnUser()) {
			Common::redirect('user/login');
			exit;
		}

		require_once ROOT_MANAGEMENT.DS.'lang.php';

		$this->model = New ModelManagement;

		foreach ($this->dir as $key) {
			$this->$key = self::$key();
		}

		$this->get_action = trim(constant('GET_ACTION'));
		$this->id   = Common::secureRequest(constant('GET_ID'));
		$this->page = strtolower(constant('GET_PAGE'));

		$this->return = self::viewManagement();

		$this->pageManagement = ROOT_MANAGEMENT.'page'.DS;
	}
	#####################################
	# Login
	#####################################
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
			include_once $file;
		}
	}
	#####################################
	# Get View and Test
	#####################################
	private function viewManagement ()
	{
		ob_start();

		if ($_SESSION['LOGIN_MANAGEMENT'] !== true) {
			self::loginManagement();
		} else {
			$this->require = GET_PAGE == 'login' ? 'Dashboard' : GET_PAGE;
			if ($this->require == 'Dashboard') Common::Redirect('Dashboard?Management');
			$pageSelf = array('dashboard', 'logout', 'parameter', 'access');
			if (in_array($this->page, $pageSelf)) {
				if ($this->page == 'parameter' or $this->page == 'access') {
					if (in_array(1, $_SESSION['user']->groups)) {
						$page = $this->page;
						self::$page();
					} else {
						ob_start("ob_gzhandler");
						?>
						<div class="container">
							<div class="row">
								<div class="span12">
									<div class="alert">
										<?=NO_ACCESS_GROUP_PAGE?>
									</div>
								</div>
							</div>
						</div>
						<?php
						$this->buffer = ob_get_contents();
						ob_end_clean();
						self::html();
					}
				} else {
					$page = $this->page;
					self::$page();			
				}
			} else {
				if ($this->ExistsPages($this->page)) {
					if (Access::AccessManagementPage($this->page) === false):
						ob_start("ob_gzhandler");
						?>
						<div class="container">
							<div class="row">
								<div class="span12">
									<div class="alert">
										<?=NO_ACCESS_GROUP_PAGE?>
									</div>
								</div>
							</div>
						</div>
						<?php
						$this->buffer = ob_get_contents();
						ob_end_clean();
						self::html();
					else:
					self::lang(DIR_PAGES.$this->page.DS.'lang'.DS.'lang.'.CMS_WEBSITE_LANG.'.php');
					self::PageManagement(DIR_PAGES.$this->page.DS.$this->dirName);
					endif;
				} else {
					$error   = 1;
					$existsPages = false;
				}
				if (isset($error)) {
					if ($this->ExistsWidgets($this->page)) {
						if (Access::AccessManagementWidgets($this->page) === false):
							ob_start("ob_gzhandler");
							?>
							<div class="container">
								<div class="row">
									<div class="span12">
										<div class="alert">
											<?=NO_ACCESS_GROUP_PAGE?>
										</div>
									</div>
								</div>
							</div>
							<?php
							$this->buffer = ob_get_contents();
							ob_end_clean();
							self::html();
						else:
						self::lang(DIR_WIDGETS.$this->page.DS.'lang'.DS.'lang.'.CMS_WEBSITE_LANG.'.php');
						self::PageManagement(DIR_WIDGETS.$this->page.DS.$this->dirName);
						endif;
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

		if (ob_get_length() != 0) { 
			ob_end_clean();
		}

		return $buffer;
	}
	#####################################
	# Get MVC
	#####################################
	private function PageManagement ($d)
	{
		ob_start("ob_gzhandler");

		$error = false;

		$file = $d.DS.$this->get_action.'.php';
		if (is_file($file)) {
			$file_model      = $d.DS.'models.php';
			$file_controller = $d.DS.'controller.php';
			if (is_file($file_model)) require $file_model;
			if (is_file($file_controller)) {
				include_once $file_controller;
				$NewClassController = 'ControllerManagement'.ucfirst($this->page);
				if (class_exists($NewClassController)) {
					$controller = New $NewClassController($this->id);
					$methode    = $this->get_action;
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
			<title><?=ucfirst(GET_PAGE)?> - BEL-CMS - MANAGEMENT</title>
			<base href="<?=WEB_ROOT?>">
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
			<meta name="apple-mobile-web-app-capable" content="yes">
			<link href="/{css}bootstrap.min.css" rel="stylesheet">
			<link href="/{css}bootstrap-responsive.min.css" rel="stylesheet">
			<link href="/{css}font-awesome.min.css" rel="stylesheet">
			<link href="../../assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
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
										<i class="icon-user"></i> <?=$_SESSION['user']->email?> <b class="caret"></b>
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
							<li>
								<a href="dashboard?management"><i class="icon-home"></i><span>Dashboard</span></a>
							</li>
							<li>
								<a href="Blog?management"><i class="icon-pencil"></i><span>Blog</span> </a>
							</li>
							<li>
								<a href="Access?management"><i class="icon-lock"></i><span>Accès</span> </a>
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

			<script src="../../assets/plugins/jquery/jquery-1.11.1.min.js"></script>
			<script src="../../assets/plugins/jquery_ui/jquery-ui.min.js"></script>
			<script src="../../assets/plugins/bootstrap/js/bootstrap.min.js"></script>
			<script src="../../assets/plugins/tinymce/jquery.tinymce.min.js"></script>
			<script src="../../assets/plugins/tinymce/tinymce.min.js"></script>
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

		$scanDir = Common::ScanDirectory(DIR_PAGES);

		$pages	 = array();

		foreach ($scanDir as $k => $v) {
			if (
				file_exists(DIR_PAGES.$v.DS.$this->dirName) && 
				file_exists(DIR_PAGES.$v.DS.$this->dirName.DS.'index.php') &&
				file_exists(DIR_PAGES.$v.DS.$this->dirName.DS.'controller.php')
			   ) {
				$pages[] = $v;
			}
		}

		$access = (object) array();

		$sql = New BDD;
		$sql->table('TABLE_PAGES_CONFIG');
		$sql->queryAll();
		foreach ($sql->data as $k => $v) {
			$accessPages[$v->name] = explode('|', $v->access_admin);
		}

		foreach ($pages as $p) {
			if ($p == 'dashboard') {
				$access->$p = (bool) true;
			} else {
					foreach ($accessPages[$p] as $k => $v) {
						if (in_array($v, $_SESSION['user']->groups)) {
							$access->$p = (bool) true;
							break;
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

		$scanDir = Common::ScanDirectory(DIR_WIDGETS);

		$widgets = array();

		foreach ($scanDir as $k => $v) {
			if (
				file_exists(DIR_WIDGETS.$v.DS.$this->dirName) && 
				file_exists(DIR_WIDGETS.$v.DS.$this->dirName.DS.'index.php') &&
				file_exists(DIR_WIDGETS.$v.DS.$this->dirName.DS.'controller.php')
			   ) {
				$widgets[] = $v;
			}
		}

		$access  = (object) array();

		$sql = New BDD;
		$sql->table('TABLE_WIDGETS');
		$sql->queryAll();
		foreach ($sql->data as $k => $v) {
			$accessPages[$v->name] = explode('|', $v->groups_admin);
		}

		foreach ($widgets as $p) {
			foreach ($accessPages[$p] as $k => $v) {
				if (in_array($v, $_SESSION['user']->groups)) {
					$access->$p = (bool) true;
					break;
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
			$cms_tpl_full   = Common::ScanDirectory(DIR_PAGES);
			$cms_tpl_full[] = 'readmore';
			$formCheckbox = array(
				'CMS_TPL_FULL'    => $cms_tpl_full
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
	private function access()
	{
		if (GET_ACTION == 'index') {
			$page = ROOT_MANAGEMENT.'pages.php';
			$sql = New BDD();
			$sql->table('TABLE_PAGES_CONFIG');
			$sql->orderby(array(array('name' => 'name', 'type' => 'DESC')));
			$sql->queryAll();
			foreach ($sql->data as $k => $v) {
				$v->name = defined(strtoupper($v->name)) ? constant(strtoupper($v->name)) : ucfirst($v->name);
				if ($v->active == 1) {
					$v->active = ACTIVATE;
				} else {
					$v->active = DISABLE;
				}
				$formPages[$k] = $v;
			} unset($sql);

			$sql = New BDD();
			$sql->table('TABLE_WIDGETS');
			$sql->orderby(array(array('name' => 'name', 'type' => 'DESC')));
			$sql->queryAll();
			foreach ($sql->data as $k => $v) {
				$v->name = defined(strtoupper($v->name)) ? constant(strtoupper($v->name)) : ucfirst($v->name);
				if ($v->activate == 1) {
					$v->activate = ACTIVATE;
				} else {
					$v->activate = DISABLE;
				}
				$formWidgets[$k] = $v;
			} unset($sql);	
		} else if (GET_ACTION == 'page_access') {
			$page = ROOT_MANAGEMENT.'page_access.php';
			$sql = New BDD();
			$sql->table('TABLE_PAGES_CONFIG');
			$sql->where(array('name' => 'id', 'value' => (int) GET_ID));
			$sql->queryOne();
			$pageName = defined(strtoupper($sql->data->name)) ? constant(strtoupper($sql->data->name)) : ucfirst($sql->data->name);
			$sql->data->access_groups = explode('|', $sql->data->access_groups);
			$groups = (array) config::GetGroups();
			$groups[0] = GUEST;
			asort($groups);
			$groupsObj = config::GetGroups();
			foreach ($sql->data->access_groups as $k => $v) {
				if ($v == 0) {
					$access_groups[0] = GUEST;
				} else if (isset($groupsObj->{$v})) {
					$access_groups[$v] = $groupsObj->{$v};;
				}
			}
			$sql->data->access_admin  = explode('|', $sql->data->access_admin);
			foreach ($sql->data->access_admin as $k => $v) {
				if ($v == 0) {
					$access_admin[0] = GUEST;
				} else if (isset($groupsObj->{$v})) {
					$access_admin[$v] = $groupsObj->{$v};
				}
			}
			if ($sql->data->config !== null) {
				$config = Common::transformOpt($sql->data->config);
			}
		} else if (GET_ACTION == 'widgets_access') {
			$page = ROOT_MANAGEMENT.'widgets_access.php';
			$sql = New BDD();
			$sql->table('TABLE_WIDGETS');
			$sql->where(array('name' => 'id', 'value' => (int) GET_ID));
			$sql->queryOne();
			$pageName = defined(strtoupper($sql->data->name)) ? constant(strtoupper($sql->data->name)) : ucfirst($sql->data->name);
			$sql->data->groups_access = explode('|', $sql->data->groups_access);
			$groups = (array) config::GetGroups();
			$groups[0] = GUEST;
			asort($groups);
			$groups_access = array();
			$groupsObj = config::GetGroups();
			foreach ($sql->data->groups_access as $k => $v) {
				if ($v == 0) {
					$groups_access[0] = GUEST;
				} else if (isset($groupsObj->{$v})) {
					$groups_access[$v] = $groupsObj->{$v};
				}
			}
			### évite une erreur si un groupe est faux
			//$groups_access = isset($groups_access) ? $groups_access : array();
			###
			$groups_admin = array();
			$sql->data->groups_admin  = explode('|', $sql->data->groups_admin);
			foreach ($sql->data->groups_admin as $k => $v) {
				if ($v == 0) {
					$groups_admin[0] = GUEST;
				} else if (isset($groupsObj->{$v})) {
					$groups_admin[$v] = $groupsObj->{$v};
				}
			}
			### évite une erreur si un groupe est faux
			//$groups_admin = isset($groups_admin) ? $groups_admin : array();
			###
			$pages = explode('|', $sql->data->pages);
		} else if (GET_ACTION == 'send_page_access') {
			$page = ROOT_MANAGEMENT.'send.php';
			if (isset($_POST['active'])) {
				$data['active'] = (int) $_POST['active'];
			} else {
				$data['active'] = 0;
			}
			if (in_array(0, $_POST['access_groups'])) {
				$data['access_groups'] = 0;
			} else {
				$data['access_groups'] = implode('|', $_POST['access_groups']);
			}
			if (isset($_POST['access_admin'])) {
				$data['access_admin'] = implode('|', $_POST['access_admin']);
			} else {
				$data['access_admin'] = 1;
			}
			if (isset($_POST['config'])) {
				$data['config'] = Common::transformOpt($_POST['config'], true);
			}
			$sql = New BDD();
			$sql->table('TABLE_PAGES_CONFIG');
			$sql->where(array('name'=>'name', 'value' => $_POST['page']));
			$sql->sqlData($data);
			$sql->update();

			if ($sql->rowCount == 1) {
				$alert = array(
					'type' => 'success',
					'text' => NEW_PARAMETER_SUCCESS
				);
			} else {
				$alert = array(
					'type' => 'alert',
					'text' => NEW_PARAMETER_ERROR
				);
			}
			Common::redirect('access?management', 2);
		} else if (GET_ACTION == 'send_widgets_access') {
			$page = ROOT_MANAGEMENT.'send.php';
			if (isset($_POST['activate'])) {
				$data['activate'] = (int) $_POST['activate'];
			} else {
				$data['activate'] = 0;
			}
			if (isset($_POST['groups_access'])) {
				if (in_array(0, $_POST['groups_access'])) {
					$data['groups_access'] = 0;
				} else {
					$data['groups_access'] = implode('|', $_POST['groups_access']);
				}
			} else {
				$data['groups_access'] = 0;
			}
			$data['title']        = Common::VarSecure($_POST['title'], '');
			if (isset($_POST['groups_admin'])) {
				$data['groups_admin'] = implode('|', $_POST['groups_admin']);
			} else {
				$data['groups_admin'] = 1; 
			}
			$data['pos']          = $_POST['pos'];
			$data['orderby']      = (int) $_POST['orderby'];
			if (isset($_POST['pages'])) {
				$data['pages'] = implode('|', $_POST['pages']);
			} else {
				$data['pages'] = '';
			}
			$sql = New BDD();
			$sql->table('TABLE_WIDGETS');
			$sql->where(array('name'=>'name', 'value' => $_POST['page']));
			$sql->sqlData($data);
			$sql->update();

			if ($sql->rowCount == 1) {
				$alert = array(
					'type' => 'success',
					'text' => NEW_PARAMETER_SUCCESS
				);
			} else {
				$alert = array(
					'type' => 'alert',
					'text' => NEW_PARAMETER_ERROR
				);
			}
			Common::redirect('access?management', 2);
		} else if (GET_ACTION === 'main_groups') {
			$page = ROOT_MANAGEMENT.'main_groups.php';
			$sql = New BDD();
			$sql->table('TABLE_GROUPS');
			$sql->queryAll();
			$table = $sql->data;
		} else if (GET_ACTION == 'edit_groups') {
			$page = ROOT_MANAGEMENT.'edit_group.php';
			$sql = New BDD();
			$sql->table('TABLE_GROUPS');
			$sql->where(array('name'=>'id', 'value'=> GET_ID));
			$sql->queryOne();
			$input = $sql->data;
		} else if (GET_ACTION == 'send_edit_groups') {
			$page = ROOT_MANAGEMENT.'send.php';
			if (empty($_POST['name'])) {
				$alert = array(
					'type' => 'alert',
					'text' => NEW_PARAMETER_ERROR
				);
			} else {
				$_POST['name'] = Common::VarSecure($_POST['name'], null);
			}

			$sql = New BDD();
			$sql->table('TABLE_GROUPS');
			$sql->where(array('name'=>'id', 'value' => GET_ID));
			$sql->sqlData($_POST);
			$sql->update();

			if ($sql->rowCount == 1) {
				$alert = array(
					'type' => 'success',
					'text' => NEW_PARAMETER_SUCCESS
				);
				if (isset($_SESSION['groups'])) {
					# Mise à jour des groups
					unset($_SESSION['groups']);
					Config::GetGroups();
				}
			} else {
				$alert = array(
					'type' => 'alert',
					'text' => NEW_PARAMETER_ERROR
				);
			}
			
			Common::redirect('Access/main_groups?management', 2);
		} else if (GET_ACTION == 'add_group') {
			$page = ROOT_MANAGEMENT.'add_group.php';
		} else if (GET_ACTION == 'send_add_group') {
			$page = ROOT_MANAGEMENT.'send.php';

			if (empty($_POST['name'])) {
				$alert = array(
					'type' => 'alert',
					'text' => NEW_PARAMETER_ERROR
				);
			} else {
				$_POST['name'] = Common::VarSecure($_POST['name'], null);
			}

			$sql = New BDD();
			$sql->table('TABLE_GROUPS');
			$sql->where(array('name'=>'name', 'value' => $_POST['name']));
			$sql->queryAll();
			if ($sql->rowCount < 0) {
				$alert = array(
					'type' => 'alert',
					'text' => 'Ce nom <u>'.$_POST['name'].'</u> est déjà utilisé'
				);

				Common::redirect('Access/add_group?management', 2);
			} else {
				if (isset($sql)) {
					unset($sql);
				}

				$uniqueId = self::uniqueId('TABLE_GROUPS', 'id_group');
				$_POST['id_group'] = $uniqueId;

				$sql = New BDD();
				$sql->table('TABLE_GROUPS');
				$sql->sqldata($_POST);
				$sql->insert();

				if ($sql->rowCount == 1) {
					$alert = array(
						'type' => 'success',
						'text' => NEW_PARAMETER_SUCCESS
					);
					# Mise à jour des groups
					unset($_SESSION['groups']);
					Config::GetGroups();
				} else {
					$alert = array(
						'type' => 'alert',
						'text' => NEW_PARAMETER_ERROR
					);
				}

				Common::redirect('Access/main_groups?management', 2);
			}
		}

		ob_start("ob_gzhandler");

		if (is_file($page)) {
			require $page;
			$this->buffer = ob_get_contents();
		} else {
			self::PageError('inner');
		}

		ob_end_clean();
		self::html();
	}

	private function getTpl ()
	{
		$return = Common::ScanDirectory(DIR_TPL);

		if (count($return) !== 0) {
			foreach ($return as $k => $n) {
				if (!is_file(DIR_TPL.$n.DS.'template.php')) {
					unset($return[$k]);
				}
			}
		}

		return $return;
	}

	private function uniqueId ($table, $search_name) {
		$search_value = rand(1, 65535);
		$sql = New BDD();
		$sql->table($table);
		$sql->where(array('name'=> $search_name, 'value'=> $search_value));
		$sql->queryAll();
		if ($sql->rowCount == 0) {
			return $search_value;
		} else {
			return self::uniqueId($table, $search_name, rand(1,65535));
		}
	}
}
class Access
{
	public 	$groups;

	function __construct()
	{
		$this->groups = self::GetGroups();
		Access::GetConfigPages();
	}

	private function GetGroups ()
	{
		$return = array();

		$BDD = New BDD();
		$BDD->table('TABLE_GROUPS');
		$BDD->fields(array('name', 'id_group'));
		$BDD->queryAll();

		$results = $BDD->data;

		unset($BDD);

		foreach ($results as $k => $v) {
			$return[(int) $v->id_group] = (string) ucfirst($v->name);
		}

		return $return;
	}

	public static function GetConfigPages ($page = false, $purge = false)
	{
		$returnPage = false;

		if ($purge === false) {

			if (!isset($_SESSION['pages']) || !is_object($_SESSION['pages'])) {
				$_SESSION['pages'] = new stdClass();
			}

	 		$return = array();

			$BDD = New BDD();
			$BDD->table('TABLE_PAGES_CONFIG');
			$BDD->fields(array('name', 'active', 'access_groups', 'access_admin', 'config'));
			$BDD->queryAll();

			$results = $BDD->data;

			unset($BDD);

			foreach ($results as $k => $v) {
				$name = $v->name; unset($v->name);
				$v->access_groups = explode('|', $v->access_groups);
				$v->access_admin  = explode('|', $v->access_admin);

				if (count($v->config) == 0) {
					$v->config = array();
				} else {
					$v->config = explode('|', $v->config);

					foreach ($v->config as $kc => $vc) {
						$tmp_config = explode('=', $vc);
						$v->config[$tmp_config[0]] = $tmp_config[1];
						unset($v->config[$kc]);
					}
				}

				$_SESSION['pages']->$name = $v;
				$return = $_SESSION['pages'];
				$returnPage = true;
			}
		}

		if ($page !== false && isset($_SESSION['pages']->$page)) {
			$return = $_SESSION['pages']->$page;
		}
		return $return;

		if ($page === false && isset($_SESSION['pages'])) {
			$returnPage = true;
			$return = $_SESSION['pages'];
		} else if ($page !== false && isset($_SESSION['pages']->$page)) {
			$returnPage = true;
			$return = $_SESSION['pages']->$page;
		}

		if ($returnPage === false) {

			if (!isset($_SESSION['pages']) || !is_object($_SESSION['pages'])) {
				$_SESSION['pages'] = new stdClass();
			}

	 		$return = array();

			$BDD = New BDD();
			$BDD->table('TABLE_PAGES_CONFIG');
			$BDD->fields(array('name', 'active', 'access_groups', 'access_admin', 'config'));
			$BDD->queryAll();

			$results = $BDD->data;

			unset($BDD);

			foreach ($results as $k => $v) {
				$name = $v->name; unset($v->name);
				$v->access_groups = explode('|', $v->access_groups);
				$v->access_admin  = explode('|', $v->access_admin);

				if (count($v->config) == 0) {
					$v->config = array();
				} else {
					$v->config = explode('|', $v->config);

					foreach ($v->config as $kc => $vc) {
						$tmp_config = explode('=', $vc);
						$v->config[$tmp_config[0]] = $tmp_config[1];
						unset($v->config[$kc]);
					}
				}

				$_SESSION['pages']->$name = $v;
				$return = $_SESSION['pages'];
				$returnPage = true;
			}
		}

		if ($returnPage === false && $page !== false && isset($_SESSION['pages']->$page)) {
			$return = $_SESSION['pages']->$page;
		}
		return $return;

	}

	public static function AccessManagementWidgets ($name = false)
	{
		$access = (bool) false;

		if ($name !== false) {
			$access = (object) array();

			$sql = New BDD;
			$sql->table('TABLE_WIDGETS');
			$sql->queryAll();
			foreach ($sql->data as $k => $v) {
				$accessPages[$v->name] = explode('|', $v->groups_admin);
			}

			foreach ($accessPages[$name] as $k => $v) {
				if (in_array($v, $_SESSION['user']->groups)) {
					$access = (bool) true;
					break;
				} else {
					$access = (bool) false;
				}
			}

		}
		return $access;
	}

	public static function AccessManagementPage ($name = false)
	{
		$access = (bool) false;

		if ($name !== false) {
			$access = (object) array();

			$sql = New BDD;
			$sql->table('TABLE_PAGES_CONFIG');
			$sql->queryAll();
			foreach ($sql->data as $k => $v) {
				$accessPages[$v->name] = explode('|', $v->access_admin);
			}

			if ($name == 'dashboard') {
				$access = (bool) true;
			} else {
				foreach ($accessPages[$name] as $k => $v) {
					if (in_array($v, $_SESSION['user']->groups)) {
						$access = (bool) true;
						break;
					} else {
						$access = (bool) false;
					}
				}
			}
		}
		return $access;
	}
}