<!DOCTYPE html>
<html lang="en">
	<head>
		<base href="{base_href}">
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="{description}">
		<title>{title}</title>
		{css}
		<link href="assets/tpl/default/blog.css" rel="stylesheet">
	</head>

	<body>

		<div class="blog-masthead">
			<div class="container">
				<nav class="blog-nav">
					<a class="blog-nav-item active" href="Home">Home</a>
					<a class="blog-nav-item" href="Forum">Forum</a>
					<a class="blog-nav-item" href="Downloads">Downloads</a>
					<a class="blog-nav-item" href="User">User</a>
					<a class="blog-nav-item" style="float: right" href="?Management">Management</a>
				</nav>
			</div>
		</div>

		<div class="container">
			<?php
			if (!empty($this->LoadWidgets('top'))):
			?>
			<div class="col-lg-12" style="margin: 20px 0">
				<?php $this->LoadWidgets('top') ?>
			</div>
			<?php
			endif;?>

			<div class="blog-header">
				<h1 class="blog-title">BEL-CMS</h1>
				<p class="lead blog-description">The default template.</p>
			</div>

			<div class="row">

				<div class="container">
				{breadcrumb}
				</div>

				<?php if ($this->full_page): ?>
				<div class="col-lg-12">
					<?php echo $this->_page; ?>
				</div>
				<?php else: ?>

				<div class="col-sm-8 blog-main">
					<?php echo $this->_page; ?>
				</div>

				<div class="col-sm-4 col-sm-offset-1 blog-sidebar">
					<?php $this->LoadWidgets('right') ?>
				</div>
				<?php endif; ?>

			</div>

		</div>

		<footer id="tpl_footer">
			<div class="container">
				<div class="row pt-5 mb-3 text-center d-flex justify-content-center">
					<div class="col-md-3 mb-6">
						<h6 class="text-uppercase font-weight-bold">
							<a href="#!">Licence GPL-3.0 (C)</a>
						</h6>
					</div>
					<div class="col-md-3 mb-6">
						<h6 class="text-uppercase font-weight-bold">
							<a href="#!">CGU</a>
						</h6>
					</div>
					<div class="col-md-3 mb-6">
						<h6 class="text-uppercase font-weight-bold">
							<a href="#!">Mentions légales</a>
						</h6>
					</div>
					<div class="col-md-3 mb-6">
						<h6 class="text-uppercase font-weight-bold">
							<a href="#!">Contact</a>
						</h6>
					</div>
				</div>
				<hr style="margin: 0 15%;">
				<div class="row d-flex text-center justify-content-center mb-md-0 mb-4">
					<div class="col-md-8 col-12 mt-5">
						<p style="line-height: 1.7rem;">
							<a href="https://bel-cms.be" title="Bel-CMS">Bel-CMS</a> est un CMS open source. Il permet d'installer et d'administrer un site web de manière simple et interactive. Il nécessite simplement un hébergement PHP/MySQL pour fonctionner.</p>
					</div>
				</div>
				<hr class="clearfix d-md-none" style="margin: 10% 15% 5%;">
				<div class="row pb-3">
					<div class="col-md-12">
						<div class="mb-5 text-center justify-content-center">
							<a class="fb-ic" href="https://facebook.com/Bel.CMS/">
								<i class="fa fa-facebook fa-lg white-text mr-md-4"> </i>
							</a>
							<a class="tw-ic" href="https://twitter.com/bel_cms">
								<i class="fa fa-twitter fa-lg white-text mr-md-4"> </i>
							</a>
							<a class="gplus-ic" href="https://plus.google.com/+Bel-cmsBe?hl=fr">
								<i class="fa fa-google-plus fa-lg white-text mr-md-4"> </i>
							</a>
						</div>
					</div>
				</div>
			</div>
			<div id="tpl_footer_copyright" class="py-3 text-center">
				<p>Copyright © 2015 - <?=date('Y')?> <a href="https://bel-cms.be" title="Bel-CMS">&trade; Bel-CMS</a></p>
			</div>
		</footer>

		{js}
	</body>
</html>
