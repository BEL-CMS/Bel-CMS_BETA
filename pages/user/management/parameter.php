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
			<strong><?=PARAMETERS?></strong>
		</div>
		<form action="User/send?management" method="post" class="form-horizontal">
			<div class="card">
				<div class="card-body">
					<div class="form-group">
						<label class="control-label" for="label_nb"><?=NB_USER?></label>
						<div class="controls">
							<input class="form-control" name="MAX_USER" type="number" id="label_nb" value="<?=$_SESSION['pages']->user->config['MAX_USER']?>" min="1" max="16">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label" for="label_nb_admin"><?=NB_USER_ADMIN?></label>
						<div class="controls">
							<input class="form-control" name="MAX_USER_ADMIN" type="number" id="label_nb_admin" value="<?=$_SESSION['pages']->user->config['MAX_USER_ADMIN']?>" min="1" max="25">
						</div>
					</div>
				</div>
				<div class="card-footer">
					<input type="hidden" name="send" value="parameter">
					<button type="submit" class="btn btn-primary"><?=SAVE?></button>
				</div>
			</div>
		</form>
	</div>
</div>
<?php
endif;
