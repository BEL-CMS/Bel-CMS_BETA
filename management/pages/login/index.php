<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Login - BEL-CMS - MANAGEMENT</title>
	<link href="assets/styles/global.css" rel="stylesheet">
	<link href="<?=ROOT_MANAGEMENT_TPL?>css/font-awesome.min.css" rel="stylesheet">
	<link href="<?=ROOT_MANAGEMENT_TPL?>css/simple-line-icons.css" rel="stylesheet">
	<link href="<?=ROOT_MANAGEMENT_TPL?>css/style.css" rel="stylesheet">
</head>
<body class="app flex-row align-items-center">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-8">
				<div class="card-group mb-0">
					<div class="card p-4">
						<form class="alertAjaxForm" action="login/send?Management&ajax" method="post">
							<div class="card-body">
								<img id="logo_bel_cms_login" src="assets/imagery/logo_bel_cms.png" alt="Logo_BEL_CMS" style="width: 100%;margin-bottom: 25px;">
								<div class="input-group mb-3">
									<span class="input-group-addon"><i class="icon-user"></i></span>
									<input type="email" name="privatemail" class="form-control" placeholder="e-mail">
								</div>
								<div class="input-group mb-4">
									<span class="input-group-addon"><i class="icon-lock"></i></span>
									<input type="password" name="password" class="form-control" placeholder="Password">
								</div>
								<div class="row">
									<div class="col-6">
										<button type="submit" class="btn btn-primary px-4">Login</button>
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="card text-white bg-primary py-5 d-md-down-none" style="width:44%">
						<div class="card-body text-center">
							<div>
								<h2>Management</h2>
								<p>Veuillez utilise votre courriel personnel pour pouvoir vous connecter au management</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script src="assets/plugins/jquery/jquery-1.11.1.min.js"></script>
	<script src="assets/plugins/js/bootstrap.min.js"></script>
	<script src="assets/plugins/core.js"></script>
</body>

</html>
