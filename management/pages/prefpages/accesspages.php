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
	$active = ($checkActive == 1) ? 'checked="cheked"' : '';
?>
<form action="PrefPages/prefaccesssend?management" method="post">
	<div class="box box-success">
		<div class="box-header with-border">
			<h3 class="box-title">Page <?=ACTIVE?></h3>
		</div>
		<div class="box-body">
			<div class="form-group">
				<label>
					<input <?=$active?> name="active" value="1" class="flat-red" type="checkbox">
				</label>
			</div>
		</div>
	</div>

	<div class="box box-info">
		<div class="box-header with-border">
			<h3 class="box-title"><?=ACCESS_PAGE?></h3>
		</div>
		<div class="box-body">
			<?php
			foreach ($getGroups as $k => $v):
				$chkedGroupAccess = in_array($k, $checkAccessPage) ? 'checked="checked"' : '';
				?>
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon">
							<input type="checkbox" name="access_groups[]" value="<?=$k?>" <?=$chkedGroupAccess?>>
						</span>
						<input class="form-control" type="text" value="<?=$v?>" readonly>
					</div>
				</div>
				<?php
			endforeach;
			?>
		</div>
	</div>

	<div class="box box-info">
		<div class="box-header with-border">
			<h3 class="box-title"><?=TITLE_MANAGEMENT?></h3>
		</div>
		<div class="box-body">
			<?php
			unset($getGroups[0]);
			foreach ($getGroups as $k => $v):
				$readOnly = ($k == 1) ? 'readonly="readonly"' : '';
				$chkedGroupAccess = in_array($k, $checkAccessAdmin) ? 'checked="checked"' : '';
				?>
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon">
							<input type="checkbox" name="access_admin[]" value="<?=$k?>" <?=$chkedGroupAccess.$readOnly?>>
						</span>
						<input class="form-control" type="text" value="<?=$v?>" readonly>
					</div>
				</div>
				<?php
			endforeach;
			?>
		</div>
	</div>

<?php
if ($config !== false):
?>
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title"><?=CONFIG?></h3>
		</div>
		<div class="box-body">
			<?php
			foreach ($config as $k => $v):
				$name = defined($k) ? constant($k) : $k;
			?>
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon">
							<input name="config[<?=$k?>]" type="number" min="1" max="64" value="<?=$v?>">
						</span>
						<input class="form-control" type="text" value="<?=$name?>" readonly>
					</div>
				</div>
			<?php
			endforeach;
			?>
		</div>
	</div>
<?php
endif;
?>
	<div class="box box-danger">
		<div class="box-body">
			<input type="hidden" name="id" value="<?=$pageId?>">
			<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-dot-circle-o"></i> <?=SAVE?></button>
		</div>
	</div>
</form>
<?php
endif;
