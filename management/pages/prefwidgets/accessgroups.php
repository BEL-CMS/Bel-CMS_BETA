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
<form action="PrefWidgets/prefaccesssend?management" method="post">
	<div class="box box-success">
		<div class="box-header with-border">
			<h3 class="box-title"><?=GROUPS.' : '. $pageName?></h3>
		</div>
		<div class="box-body">
			<div class="form-group">
				<label>
					<?php
					$active = ($data->activate == 1) ? 'checked="cheked"' : '';
					?>
					<input <?=$active?> name="activate" class="switch-input" value="1" type="checkbox">
				</label>
			</div>
		</div>
	</div>

	<div class="box box-success">
		<div class="box-header with-border">
			<h3 class="box-title">Accès groupes</h3>
		</div>
		<div class="box-body">
			<?php
			foreach ($getGroups as $k => $v):
				$chkedGroupAccess = in_array($k, $access_groups) ? 'checked="checked"' : '';
			?>
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon">
							<input type="checkbox" name="groups_access[]" value="<?=$k?>" <?=$chkedGroupAccess?>>
						</span>
						<input class="form-control" type="text" value="<?=$v?>" readonly>
					</div>
				</div>
			<?php
			endforeach;
			?>
		</div>
	</div>

	<div class="box box-success">
		<div class="box-header with-border">
			<h3 class="box-title"><?=TITLE_MANAGEMENT?></h3>
		</div>
		<div class="box-body">
			<?php
			unset($getGroups[0]);
			foreach ($getGroups as $k => $v):
				$readOnly = ($k == 1) ? 'readonly="readonly"' : '';
				$chkedGroupAccess = in_array($k, $access_admin) ? 'checked="checked"' : '';
			?>
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon">
							<input type="checkbox" name="groups_admin[]" value="<?=$k?>" <?=$chkedGroupAccess.$readOnly?>>
						</span>
						<input class="form-control" type="text" value="<?=$v?>" readonly>
					</div>
				</div>
			<?php
			endforeach;
			?>
		</div>
	</div>

	<div class="box box-success">
		<div class="box-header with-border">
			<h3 class="box-title"><?=CONFIG?></h3>
		</div>
		<div class="box-body">
			<div class="form-group">
				<label class="control-label" for="label_name"><?=CUSTOM_NAME?></label>
				<div class="controls">
					<input class="form-control" type="text" name="title" value="<?=$data->title?>">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label" for="label_name"><?=POSITION?></label>
				<div class="controls">
					<select class="form-control" name="pos">
						<?php
						$posTop    = $data->pos == 'top'    ? 'selected="selected"' : null;
						$posRight  = $data->pos == 'right'  ? 'selected="selected"' : null;
						$posBottom = $data->pos == 'bottom' ? 'selected="selected"' : null;
						$posLeft   = $data->pos == 'left'   ? 'selected="selected"' : null;
						?>
						<option <?=$posTop?> value="top"><?=TOP?></option>
						<option <?=$posRight?> value="right"><?=RIGHT?></option>
						<option <?=$posBottom?> value="bottom"><?=BOTTOM?></option>
						<option <?=$posLeft?> value="left"><?=LEFT?></option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label" for="label_name"><?=POSITION?></label>
				<div class="controls">
					<input class="form-control" type="number" name="orderby" value="<?=$data->orderby?>">
				</div>
			</div>

		</div>
	</div>

	<div class="box box-success">
		<div class="box-header with-border">
			<h3 class="box-title"><?=PAGES?></h3>
		</div>
		<div class="box-body">
			<?php
			foreach (Common::ScanDirectory(DIR_PAGES) as $k => $v):
				$checked = in_array($v, $pages) ? 'checked="cheked"' : '';
			?>
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon">
							<input type="checkbox" name="pages[]" value="<?=$v?>" <?=$checked?>>
						</span>
						<input class="form-control" type="text" value="<?=$v?>" readonly>
					</div>
				</div>
			<?php
			endforeach;
			?>
		</div>
	</div>

	<div class="box box-success">
		<div class="box-body">
			<input type="hidden" name="id" value="<?=$data->name?>">
			<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-dot-circle-o"></i> <?=SAVE?></button>
		</div>
	</div>
</form>
<?php
endif;
