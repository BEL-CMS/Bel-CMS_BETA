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
if ($this->data):
?>
<div class="container relative">
	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 pull-none margin-auto">
			<div class="panel panel-default panel-login">
				<div class="panel-heading">
					<h3 class="panel-title"><i class="fa fa-users"></i> Register to <?php echo CMS_WEBSITE_NAME; ?></h3>
				</div>
				<div class="panel-body">
					<form action="/User/Send&ajax" class="alertAjaxForm" method="post">
						<div class="form-group input-icon-left">
							<i class="fa fa-user"></i>
							<input type="text" class="form-control" name="username" placeholder="Username" autocomplete="off" required="required">
						</div>
						<div class="form-group input-icon-left">
							<i class="fa fa-envelope"></i>
							<input type="email" class="form-control" name="email" placeholder="Email" autocomplete="off" required="required">
						</div>
						<div class="form-group input-icon-left">
							<i class="fa fa-lock"></i>
							<input type="password" class="form-control" name="password" placeholder="Password" autocomplete="off" required="required">
						</div>
						<div class="form-group input-icon-left">
							<i class="fa fa-check"></i>
							<input type="password" class="form-control" name="password_repeat" placeholder="Repeat Password" autocomplete="off" required="required">
						</div>

						<div class="controls form-group">
							<div class="row">
								<div class="col-lg-2 no-padding-right">
									<span class="helper-left"><?php echo $_SESSION['TMP_QUERY_REGISTER']['NUMBER_1']; ?> + <?php echo $_SESSION['TMP_QUERY_REGISTER']['NUMBER_2']; ?> =</span>
								</div>
								<div class="col-lg-10">
										<input name="query_register" type="number" min="1" max="18" class="form-control" id="security-password" placeholder="Your Answer" autocomplete="off">
								</div>
							</div>
						</div>

						<div class="form-actions panel-footer">
							<p>Accept terms and service.</p>
							<div class="radio radio-control radio-success">
								<input type="radio" value="on" id="color-option1" name="terms">
								<label for="color-option1">Accept</label>
							</div>
							<div class="radio radio-control radio-danger">
								<input type="radio" value="off" checked="checked" id="color-radio2" name="terms">
								<label for="color-radio2">Decline</label>
							</div>
						</div>

						<input type="submit" class="btn btn-primary btn-block">
						<input type="hidden" name="send" value="register">

					</form>
				</div>
				<div class="panel-footer">
					Already have an account? <a href="user/login">Sign In Now</a>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
endif;
?>
