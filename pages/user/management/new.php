<?php
/**
 * Bel-CMS [Content management system]
 * @version 0.0.1
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
if (isset($_SESSION['LOGIN_MANAGEMENT']) && $_SESSION['LOGIN_MANAGEMENT'] === true):
?>
<div class="row">
	<div class="col-sm-12">
		<div class="card-header">
			<strong><?=USER.' : '.ADD?></strong>
		</div>
		<form action="User/send?management" method="post">
			<div class="card">
				<div class="card-body">
					<div class="form-group">
						<label class="control-label" for="label_name"><?=USERNAME?> :</label>
						<div class="controls">
							<input class="form-control" name="username" type="text" class="span6" id="label_name" placeholder="Nom d'utilisateur" required="required">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label" for="label_pass"><?=PASSWORD?> :</label>
						<div class="controls">
							<input class="form-control" name="password" type="password" class="span6" placeholder="******" id="label_pass" required="required">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label" for="label_mail"><?=MAIL?> :</label>
						<div class="controls">
							<input class="form-control" name="email" type="email" class="span6" id="label_mail" placeholder="<?=MAIL.' '.constant('PRIVATE')?>" required="required">
						</div>
					</div>
				</div>
				<div class="card-footer">
					<input type="hidden" name="send" value="new">
					<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-dot-circle-o"></i> <?=ADD?></button>
				</div>
			</div>
		</form>
	</div>
</div>
<?php
endif;
