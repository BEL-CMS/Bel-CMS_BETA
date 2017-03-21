<?php
/**
 * Bel-CMS [Content management system]
 * @version 0.0.1
 * @link http://www.bel-cms.be
 * @link http://www.stive.eu
 * @license http://opensource.org/licenses/GPL-3.0 copyleft
 * @copyright 2014 Bel-CMS
 * @author Stive - mail@stive.eu
 */
if (!defined('CHECK_INDEX')) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 403 Direct access forbidden');
    exit(ERROR_INDEX);
}
if (AutoUser::isLogged() === false):
?>
<div class="container relative">
	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 pull-none margin-auto">
			<div class="panel panel-default panel-login">
				<div class="panel-heading">
					<h3 class="panel-title"><i class="fa fa-user"></i> Sign In to <?php echo CMS_WEBSITE_NAME; ?></h3>
				</div>
				<div class="panel-body">
					<form action="User/Send" method="post">
						<div class="form-group input-icon-left">
							<i class="fa fa-user"></i>
							<input type="text" class="form-control" name="username" placeholder="Username" required="required">
						</div>
						<div class="form-group input-icon-left">
							<i class="fa fa-lock"></i>
							<input type="password" class="form-control" name="password" placeholder="Password" required="required">
						</div>

						<input type="submit" value="Sign In" class="btn btn-primary btn-block">

						<input type="hidden" name="send" value="login">

						<div class="form-actions">
							<div class="checkbox checkbox-primary">
								<input name="remember" value="true" type="checkbox" id="checkbox">
								<label for="checkbox">Remember me</label>
							</div>
						</div>
					</form>
				</div>
				<div class="panel-footer">
					<p>Reset your password or Forgot password ? <a href="user/lostpassword">Recovery</a></p>
					<p>Don't have <?php echo CMS_WEBSITE_NAME; ?> account? <a href="user/register">Sign Up Now</a></p>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
endif;
?>
