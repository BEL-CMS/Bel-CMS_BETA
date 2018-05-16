<?php
/**
 * Bel-CMS [Content management system]
 * @version 0.3.0
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

if (AutoUser::isLogged() === false):
?>
<form action="User/Send" role="form" class="form-horizontal" method="post">
	<div class="col-md-12">
		<div class="row">
			<div class="col-md-6 mx-auto">
				<div class="card rounded-0">
					<div class="card-header">
						<h3 class="mb-0">Sign In to <?=CMS_WEBSITE_NAME?><a class="btn btn-link float-right" href="user/lostpassword">Reset your password</a></h3>
					</div>
					<div class="card-body">
						<form class="form" role="form" autocomplete="off" id="formLogin" novalidate="" method="POST">
							<div class="form-group">
								<label>E-Mail Address or username</label>
								<input type="text" class="form-control form-control-lg rounded-0" name="username" placeholder="E-Mail Address or name" required="required" autofocus>
								<div class="invalid-feedback">Oops, you missed this one.</div>
							</div>
							<div class="form-group">
								<label>Password</label>
								<input type="password" class="form-control form-control-lg rounded-0" name="password" placeholder="Password" required="required">
								<div class="invalid-feedback">Enter your password too!</div>
							</div>
							<div class="form-check">
								<label class="form-check-label">
									<input type="checkbox" class="form-check-input" name="remember" value="true" checked>
								Remember me on this computer
								</label>
							</div>
						</form>
					</div>
					<div class="card-footer">
						Don't have <?=CMS_WEBSITE_NAME?> account? <a class="btn btn-link" href="user/register">Sign Up Now</a>
						<button type="submit" class="btn btn-success btn-lg float-right"><i class="fa fa-sign-in"></i> Login</button>
						<input type="hidden" name="send" value="login">
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
<?php
endif;
?>
