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

			<div class="col-lg-12" style="margin: 20px 0">
				<?php $this->LoadWidgets('top') ?>
			</div>

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

				<div class="col-sm-3 col-sm-offset-1 blog-sidebar">
					<?php $this->LoadWidgets('right') ?>
				</div>
				<?php endif; ?>

			</div>

		</div>

		<footer class="blog-footer">
			<p>Blog template built for <a href="http://getbootstrap.com">Bootstrap</a> by <a href="https://twitter.com/mdo">@mdo</a>.</p>
			<p>
				<a href="#">Back to top</a>
			</p>
		</footer>
		{js}
	</body>
</html>
