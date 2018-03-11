<?php
if (defined(strtoupper(get_class($GLOBALS['bel_cms']->controller)))) {
	$name = constant(strtoupper(get_class($GLOBALS['bel_cms']->controller)));
} else {
	$name = get_class($GLOBALS['bel_cms']->controller);
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
	<base href="<?=GetHost::getBaseUrl()?>">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>BEL-CMS | Management</title>
	<link href="management/css/font-awesome.min.css" rel="stylesheet">
	<link href="management/css/simple-line-icons.css" rel="stylesheet">
	<link href="management/css/style.css" rel="stylesheet">
	<link href="assets/plugins/datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet">
	<link href="assets/styles/notification.css" rel="stylesheet">
</head>
<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
	<header class="app-header navbar">
		<button class="navbar-toggler mobile-sidebar-toggler d-lg-none mr-auto" type="button">☰</button>
		<a class="navbar-brand" href="#"></a>
		<button class="navbar-toggler sidebar-minimizer d-md-down-none" type="button">☰</button>

		<ul class="nav navbar-nav d-md-down-none">
			<li class="nav-item px-3">
				<a class="nav-link" href="dashboard?management">Dashboard</a>
			</li>
			<li class="nav-item px-3">
				<a class="nav-link" href="users?management">Users</a>
			</li>
			<li class="nav-item px-3">
				<a class="nav-link alertAjaxLink" href="login/logout?management&ajax">Logout</a>
			</li>
		</ul>
		<ul class="nav ml-auto">
			<li class="nav-item">
				<span style="padding: 0 15px;"><?=AutoUser::ReturnUser()->email;?></span>
			</li>
		</ul>

	</header>

	<div class="app-body">
		<div class="sidebar">
			<nav class="sidebar-nav">
				<ul class="nav">
					<li class="nav-item">
						<a class="nav-link" href="dashboard?management"><i class="icon-home"></i> Dashboard</a>
					</li>
					<li class="nav-title">Paramètres</li>
					<li class="nav-item">
						<a class="nav-link" href="prefgen?management"><i class="icon-shield"></i> Général</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="prefaccess?management"><i class="icon-book-open"></i> Pages</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="prefgrps?management"><i class="icon-key"></i> Groupes</a>
					</li>
					<li class="nav-title">Navigation</li>
					<li class="nav-item nav-dropdown">
						<a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-docs"></i> Pages</a>
						<ul class="nav-dropdown-items">
							<?php
							foreach ($this->listPages as $k => $n):
							?>
							<li class="nav-item">
								<a class="nav-link" href="<?=$n?>?management"><i class="icon-puzzle"></i> <?=ucfirst($n)?></a>
							</li>
							<?php
							endforeach;
							?>
						</ul>
					</li>
					<li class="nav-item nav-dropdown">
						<a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-puzzle"></i> Widgets</a>
						<ul class="nav-dropdown-items">
							<?php
							foreach ($this->listWidgets as $k => $n):
							?>
							<li class="nav-item">
								<a class="nav-link" href="<?=$n?>?management"><i class="icon-puzzle"></i> <?=ucfirst($n)?></a>
							</li>
							<?php
							endforeach;
							?>
						</ul>
					</li>
					<li class="divider"></li>
					<li class="nav-title">
						Extras
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#"><i class="icon-info"></i> Licence GNU/GPL</a>
					</li>
					<li class="nav-item nav-dropdown">
						<a class="nav-link" href="https://bel-cms.be/forum"><i class="icon-speech"></i> Forum officiel</a>
					</li>
				</ul>
			</nav>
		</div>

		<!-- Main content -->
		<main class="main">

			<ol class="breadcrumb">
				<li class="breadcrumb-item">Home</li>
				<li class="breadcrumb-item active"><?=$name?></li>
			</ol>

			<div class="container-fluid">
				<div class="animated fadeIn">
					<?php

					?>
					<?=$this->_page?>
				</div>

			</div>

		</main>



	</div>

	<footer class="app-footer">
		<a href="https://bel-cms.be">BEL-CMS</a> © 2015-<?=date('Y')?>.
		<span class="float-right">Version 0.0.2</span>
	</footer>

	<script src="management/js/jquery-3.2.1.slim.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src="assets/plugins/datepicker/js/bootstrap-datepicker.min"></script>
	<script src="management/js/bootstrap.min.js"></script>
	<script src="management/js/popper.min.js"></script>
	<script src="assets/plugins/tinymce/jquery.tinymce.min.js"></script>
	<script src="assets/plugins/tinymce/tinymce.min.js"></script>
	<script src="management/js/app.js"></script>

</body>

</html>
