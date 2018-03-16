<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Login - BEL-CMS - MANAGEMENT</title>
	<link href="assets/styles/global.css" rel="stylesheet">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?=ROOT_MANAGEMENT_TPL?>css/simple-line-icons.css">
	<link rel="stylesheet" href="<?=ROOT_MANAGEMENT_TPL?>css/login.css" rel="stylesheet">
</head>
<body>
	<div class="container">
		<div class="row center">
			<div id="block_login" class="col-md-8">
				<div class="row">
					<div id="block_login_left" class="col-md-6">
						<img id="logo_bel_cms_login" src="assets/imagery/logo_bel_cms.png" alt="Logo_BEL_CMS" style="width: 100%;margin-bottom: 25px;">
						<form class="alertAjaxForm" action="login/send?Management&ajax" method="post">
							<div class="input-group mb-2">
								<div class="input-group-prepend">
									<div class="input-group-text"><i class="icon-user"></i></div>
								</div>
								<input type="email" name="privatemail" class="form-control" id="email" placeholder="e-mail">
							</div>
							<div class="input-group mb-2">
								<div class="input-group-prepend">
									<div class="input-group-text"><i class="icon-lock"></i></div>
								</div>
								<input type="password" name="password" class="form-control" id="password" placeholder="mot de passe">
							</div>
							<div class="row">
								<div class="col-12">
									<button type="submit" class="btn btn-primary">Login</button>
								</div>
							</div>
						</form>
					</div>
					<div id="block_login_right" class="col-md-6">
						<div>
							<h2>Management</h2>
							<p>Veuillez utilise votre courriel personnel pour pouvoir vous connecter au management</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script src="assets/plugins/jquery/jquery-1.11.1.min"></script>
	<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
	<script src="assets/plugins/core.js"></script>
</body>
</html>
