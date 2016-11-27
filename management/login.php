<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Login - BEL-CMS - MANAGEMENT</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
	<link href="{css}bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="{css}bootstrap-responsive.min.css" rel="stylesheet" type="text/css">
	<link href="{css}font-awesome.css" rel="stylesheet">
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600" rel="stylesheet">
	<link href="{css}style.css" rel="stylesheet" type="text/css">
	<link href="{css}pages/signin.css" rel="stylesheet" type="text/css">
	<link href="../../assets/styles/global.css" rel="stylesheet" type="text/css">
</head>
<body id="management_login">
	<div class="account-container">

		<div class="content clearfix">

			<form class="alertAjaxForm" action="/login?Management&ajax" method="post" title="">

				<img id="logo_bel_cms_login" src="/assets/imagery/logo_bel_cms.png" alt="Logo_BEL_CMS">

				<div class="login-fields">

					<div class="field">
						<label for="username">Username</label>
						<input type="email" id="username" name="privatemail" value="" placeholder="Private Email" class="login username-field">
					</div>
					<div class="field">
						<label for="username">Password</label>
						<input type="password" id="username" name="password" value="" placeholder="Password" class="login password-field">
					</div>

				</div>

				<div class="login-actions">
					<a style="float: left;" href="/Home" class="button btn btn-warning btn-large">Retour</a>
					<input type="submit" class="button btn btn-success btn-large" value="Sign In">
				</div>

			</form>

		</div>

	</div>

	<script src="{js}jquery-1.7.2.min.js"></script>
	<script src="{js}bootstrap.js"></script>
	<script src="{js}signin.js"></script>
	<script src="../../assets/plugins/core.js"></script>

</body>

</html>
