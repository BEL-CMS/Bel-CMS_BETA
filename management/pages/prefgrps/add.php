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
<div class="box-body">
	<a class="btn btn-app" href="Prefgrps?management">
		<i class="fa fa-key"></i><?=GROUPS?>
	</a>
	<a class="btn btn-app" href="Prefgrps/add?management">
		<i class="fa fa-plus"></i><?=ADD?>
	</a>
</div>

<form action="Prefgrps/sendadd?management" method="post">
	<div class="box">
		<div class="box">
			<div class="box-header with-border">
				<h3 class="box-title"><?=GROUPS?> </h3>
			</div>
			<div class="box-body">
				<div class="form-group">
					<label for="name"><?=NAME_TO_GROUP?></label>
					<input class="form-control" id="name" name="name" placeholder="<?=NAME_TO_GROUP?>" type="text">
					<span class="help-block"><?=HELP_NAME_TO_GROUP?>
			</div>
			<div class="box-footer">
				<button type="submit" class="btn btn-primary"><?=ADD_THE_GROUPS?></button>
			</div>
		</div>
	</div>
</span>
<?php
endif;
