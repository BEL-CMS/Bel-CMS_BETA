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
					<h3 class="panel-title"><i class="fa fa-user"></i> Reset your password or Forgot password</h3>
				</div>
				<div class="panel-body">
					<form action="User/Send" method="post">
						<div class="form-group input-icon-left">
							<i class="fa fa-user"></i>
							<input type="text" class="form-control" name="value" placeholder="Your name or private mail" required="required" autocomplete="off">
						</div>
						<div class="form-group input-icon-left">
							<i class="fa fa-lock"></i>
							<input type="text" class="form-control" name="token" placeholder="Enter your token" autocomplete="off">
						</div>

						<input type="submit" value="Recovery" class="btn btn-primary btn-block">

						<input type="hidden" name="send" value="lostpassword">

					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
endif;
?>