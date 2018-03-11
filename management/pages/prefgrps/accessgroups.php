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
	<form action="prefgrps/prefaccesssend?management" method="post" class="col-sm-12">
		<div class="card">
			<div class="card-header">
				<th><?=GROUPS.' : '. $pageName?></th>
			</div>
			<div class="card-body">
				<label class="switch switch-text switch-pill switch-success">
					<?php
					$active = ($data->activate == 1) ? 'checked="cheked"' : '';
					?>
					<input <?=$active?> name="activate" class="switch-input" value="1" type="checkbox">
					<span class="switch-label" data-on="On" data-off="Off"></span>
					<span class="switch-handle"></span>
				</label>
			</div>
		</div>

		<div class="card">
			<div class="card-header">
				<th>Accès groupes</th>
			</div>
			<div class="card-body">
				<table class="table table-bordered table-align-middle mb-0">
					<tbody>
						<?php
						foreach ($getGroups as $k => $v):
							$chkedGroupAccess = in_array($k, $access_groups) ? 'checked="checked"' : '';
							?>
						<tr>
							<td>
								<?=$v?>
							</td>
							<td>
								<label class="switch switch-text switch-secondary">
									<input <?=$chkedGroupAccess?> name="groups_access[]" class="switch-input" type="checkbox" value="<?=$k?>">
									<span class="switch-label" data-on="On" data-off="Off"></span>
									<span class="switch-handle"></span>
								</label>
							</td>
						</tr>
							<?php
						endforeach;
						?>
					</tbody>
				</table>
			</div>
		</div>

		<div class="card">
			<div class="card-header">
				<th><?=TITLE_MANAGEMENT?></th>
			</div>
			<div class="card-body">
				<table class="table table-bordered table-align-middle mb-0">
					<tbody>
						<?php
						unset($getGroups[0]);
						foreach ($getGroups as $k => $v):
							$readOnly = ($k == 1) ? 'readonly="readonly"' : '';
							$chkedGroupAccess = in_array($k, $access_admin) ? 'checked="checked"' : '';
							?>
						<tr>
							<td>
								<?=$v?>
							</td>
							<td>
								<label class="switch switch-text switch-secondary">
									<input <?=$chkedGroupAccess.$readOnly?> name="groups_admin[]" class="switch-input" type="checkbox" value="<?=$k?>">
									<span class="switch-label" data-on="On" data-off="Off"></span>
									<span class="switch-handle"></span>
								</label>
							</td>
						</tr>
							<?php
						endforeach;
						?>
					</tbody>
				</table>
			</div>
		</div>

		<div class="card">
			<div class="card-header">
				<th><?=CONFIG?></th>
			</div>
			<div class="card-body">
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

				<div class="form-group">
					<label class="control-label" for="label_name"><?=PAGES?></label>
					<div class="controls">
						<?php
						foreach (Common::ScanDirectory(DIR_PAGES) as $k => $v):
							$checked = in_array($v, $pages) ? 'checked="cheked"' : '';
						?>
							<label class="checkbox">
								<input <?=$checked?> name="pages[]" id="label_<?=$k?>" value="<?=$v?>" type="checkbox"> <?=$v?>
							</label><br>
						<?php
						endforeach;
						?>
					</div>
				</div>

			</div>
			<div class="card-footer">
				<input type="hidden" name="id" value="<?=$data->name?>">
				<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-dot-circle-o"></i> <?=SAVE?></button>
			</div>
		</div>
	</form>
</div>
<?php
endif;
