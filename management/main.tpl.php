<?php
if (defined(strtoupper(get_class($GLOBALS['bel_cms']->controller)))) {
	$name = constant(strtoupper(get_class($GLOBALS['bel_cms']->controller)));
} else {
	$name = get_class($GLOBALS['bel_cms']->controller);
}
?>
<!DOCTYPE html>
<html>
<head>
	<base href="<?=GetHost::getBaseUrl()?>">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>BEL-CMS | Management</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
	<link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/plugins/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="assets/plugins/ionicons/css/ionicons.min.css">
	<link rel="stylesheet" href="management/css/simple-line-icons.css">
	<link rel="stylesheet" href="assets/styles/notification.css">
	<link rel="stylesheet" href="assets/plugins/datepicker/css/bootstrap-datepicker3.min.css">
	<link rel="stylesheet" href="assets/plugins/datatables/jquery.dataTables.min.css">
	<link rel="stylesheet" href="management/css/AdminLTE.min.css">
	<link rel="stylesheet" href="management/css/_all-skins.min.css">
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>

<body class="hold-transition skin-green-light sidebar-mini">
<div class="wrapper">
	<header class="main-header">
		<a href="#" class="logo">
			<span class="logo-mini">Admin</span>
			<span class="logo-lg"><b>Bel</b>CMS</span>
		</a>
		<nav class="navbar navbar-static-top" role="navigation">
			<a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
				<span class="sr-only">Toggle navigation</span>
			</a>
			<div class="navbar-custom-menu">
				<ul class="nav navbar-nav">
					<li>
						<a href="login/logout?management&ajax" class="alertAjaxLink"><i class="fa fa-sign-out"></i></a>
					</li>
				</ul>
			</div>
		</nav>
	</header>
	<aside class="main-sidebar">
		<section class="sidebar">
			<div class="user-panel">
				<div class="pull-left info">
					<p><?=AutoUser::ReturnUser()->email;?></p>
				</div>
			</div>
			<ul class="sidebar-menu" data-widget="tree">
				<li class="nav-item">
					<a class="nav-link" href="dashboard?management"><i class="fa fa-dashboard"></i> <span><?=DASHBOARD?></span></a>
				</li>
				<li class="header"><?=PARAMETERS?></li>
				<li>
					<a class="nav-link" href="PrefGen?management">
						<?=GENERAL?>
						<span class="pull-right-container">
							<span class="pull-right"><i class="icon-shield"></i></span>
						</span>
					</a>
				</li>
				<li>
					<a class="nav-link" href="PrefPages?management">
						<?=PAGES?>
						<span class="pull-right-container">
							<span class="pull-right"><i class="icon-book-open"></i></span>
						</span>
					</a>
				</li>
				<li>
					<a class="nav-link" href="PrefWidgets?management">
						<?=WIDGETS?>
						<span class="pull-right-container">
							<span class="pull-right"><i class="icon-puzzle"></i></span>
						</span>
					</a>
				</li>
				<li>
					<a class="nav-link" href="Prefgrps?management">
						<?=GROUPS?>
						<span class="pull-right-container">
							<span class="pull-right"><i class="icon-key"></i></span>
						</span>
					</a>
				</li>
				<li class="header"><?=NAVIGATION?></li>
				<li class="treeview">
					<a href="#">
						<?=PAGES?>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">
						<?php
						foreach ($this->listPages as $k => $n):
							$name = defined(strtoupper($n)) ? constant(strtoupper($n)) : ucfirst($n);
						?>
						<li>
							<a class="nav-link" href="<?=$n?>?management"><i class="fa fa-caret-right"></i> <?=$name?></a>
						</li>
						<?php
						endforeach;
						?>
					</ul>
				</li>
				<li class="treeview">
					<a  href="#">
						<?=WIDGETS?>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">
						<?php
						foreach ($this->listWidgets as $k => $n):
							$name = defined(strtoupper($n)) ? constant(strtoupper($n)) : ucfirst($n);
						?>
						<li>
							<a href="<?=$n?>?management"><i class="fa fa-caret-right"></i></i> <?=$name?></a>
						</li>
						<?php
						endforeach;
						?>
					</ul>
				</li>
				<li class="header">
					<?=EXTRAS?>
				</li>
				<li class="nav-item">
					<a href="#">
						<?=LISCENCE?>
						<span class="pull-right-container">
							<span class="pull-right"><i class="icon-info"></i></span>
						</span>
					</a>
				</li>
				<li>
					<a href="https://bel-cms.be/forum">
						<?=FORUM_OFFICIAL?>
						<span class="pull-right-container">
							<span class="pull-right"><i class="icon-speech"></i></span>
						</span>
					</a>
				</li>
			</ul>
		</section>
	</aside>
	<div class="content-wrapper">
		<section class="content-header">
			<h1>
				<?=$this->TitleName?>
				<small><?=$this->optionalDescription?></small>
			</h1>
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><?=HOME?></li>
				<li class="breadcrumb-item active"><?=$name?></li>
			</ol>
		</section>
		<section class="content container-fluid">
			<?=$this->_page?>
		</section>
	</div>
	<footer class="main-footer">
		<div class="pull-right hidden-xs">
			Version 0.0.2
		</div>
		<strong>Copyright &copy; 2015 - <?=date('Y')?>  <a href="https://bel-cms.be">Bel-CMS</a>  </strong> All rights reserved.
	</footer>

	<div class="control-sidebar-bg"></div>
</div>

<script src="assets/plugins/jquery/jquery-3.3.1.min.js"></script>
<script src="assets/plugins/bootstrap/js/popper.min.js"></script>
<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/plugins/datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="assets/plugins/tinymce/jquery.tinymce.min.js"></script>
<script src="assets/plugins/tinymce/tinymce.min.js"></script>
<script src="assets/plugins/datatables/datatables.min.js"></script>
<script src="<?=ROOT_MANAGEMENT_TPL?>/js/adminlte.min.js"></script>
<script src="assets/plugins/tipsy/jquery.tipsy.js"></script>
<script src="assets/plugins/core.js"></script>
</body>
</html>
