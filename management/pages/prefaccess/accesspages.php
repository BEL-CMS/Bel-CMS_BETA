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
<div class="row">
	<form action="prefaccess/prefaccesssend?management" method="post" class="col-sm-12">

		<div class="card">
			<div class="card-header">
				<th>Page <?=ACTIVE?></th>
			</div>
			<div class="card-body">
				<label class="switch switch-text switch-pill switch-success">
					<input <?=$active?> name="active" value="1" class="switch-input" type="checkbox">
					<span class="switch-label" data-on="On" data-off="Off"></span>
					<span class="switch-handle"></span>
				</label>
			</div>
		</div>

		<div class="card">
			<div class="card-header">
				<th><?=ACCESS_PAGE?></th>
			</div>
			<div class="card-body">
				<table class="table table-bordered table-align-middle mb-0">
					<tbody>
						<?php
						foreach ($getGroups as $k => $v):
							$chkedGroupAccess = in_array($k, $checkAccessPage) ? 'checked="checked"' : '';
							?>
						<tr>
							<td>
								<?=$v?>
							</td>
							<td>
								<label class="switch switch-text switch-secondary">
									<input <?=$chkedGroupAccess?> name="access_groups[]" class="switch-input" type="checkbox" value="<?=$k?>">
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
							$chkedGroupAccess = in_array($k, $checkAccessAdmin) ? 'checked="checked"' : '';
							?>
						<tr>
							<td>
								<?=$v?>
							</td>
							<td>
								<label class="switch switch-text switch-secondary">
									<input <?=$chkedGroupAccess.$readOnly?> name="access_admin[]" class="switch-input" type="checkbox" value="<?=$k?>">
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

		<?php
		if ($config !== false):
		?>
		<div class="card">
			<div class="card-header">
				<th><?=CONFIG?></th>
			</div>
			<div class="card-body">
				<table class="table table-bordered table-align-middle mb-0">
					<tbody>
						<?php
						foreach ($config as $k => $v):
							$name = defined($k) ? constant($k) : $k;
						?>
						<tr>
							<td><?=$name?></td>
							<td><input name="config[<?=$k?>]" type="number" min="1" max="64" value="<?=$v?>"></td>
						</tr>
						<?php
						endforeach;
						?>
					</tbody>
				</table>
			</div>
		</div>
		<?php
		endif;
		?>

		<div class="card-footer">
			<input type="hidden" name="id" value="<?=$pageId?>">
			<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-dot-circle-o"></i> <?=SAVE?></button>
		</div>
	</form>
</div>
<?php
endif;
