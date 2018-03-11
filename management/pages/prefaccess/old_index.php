		<form action="prefaccess/prefaccesssend?management" method="post">
			<div class="card">
				<div class="card-header">
					<strong><?=GROUPS.' : '. $traduct?></strong>
				</div>
				<div class="card-body">
					<div class="form-check">
						<?php
						$active = ($checkActive == 1) ? 'checked="cheked"' : '';
						?>
						<div class="col-md-9">
							<input <?=$active?> name="active" id="label_active" value="1" type="checkbox" style="vertical-align: middle;">
							<label class="form-check-label" for="defaultCheck1"><?=ACTIVE?></label>
						</div>
					</div>

					<div class="control-group">
						<span style="font-weight: bold;"><?=ACCESS_PAGE?></span>
					</div>
					<?php
					foreach ($getGroups as $k => $v):
						$chkedGroupAccess = in_array($k, $checkAccessPage) ? 'checked="checked"' : '';
						?>
						<div class="col-md-9">
							<div class="checkbox">
								<label for="checkbox1">
									<input <?=$chkedGroupAccess?> name="access_groups[]" id="label_<?=$k?>" value="<?=$k?>" type="checkbox"> <?=$v?>
								</label>
							</div>
						</div>
						<?php
					endforeach;
					?>

					<div class="control-group">
						<span style="font-weight: bold;"><?=TITLE_MANAGEMENT?></span>
					</div>
					<?php
					unset($getGroups[0]);
					foreach ($getGroups as $k => $v):
						$chkedGroupAccess = in_array($k, $checkAccessAdmin) ? 'checked="checked"' : '';
						?>
						<div class="col-md-9">
							<div class="checkbox">
								<label for="checkbox1">
									<input <?=$chkedGroupAccess?> name="access_admin[]" id="label_<?=$k?>" value="<?=$k?>" type="checkbox"> <?=$v?>
								</label>
							</div>
						</div>
						<?php
					endforeach;
					?>

						<?php
						if ($config !== null):
						?>
						<div class="control-group">
							<span style="font-weight: bold;"><?=CONFIG?></span>
							<div class="controls">
								<?php
								foreach ($config as $k => $v):
									$name = defined($k) ? constant($k) : $k;
								?>
									<div class="col-md-9">
										<div class="span3"><?=$name?></div>
										<input name="config[<?=$k?>]" type="number" min="1" max="64" value="<?=$v?>">
									</div>
								<?php
								endforeach;
								?>
							</div>
						</div>
						<?php
						endif;
						?>

					<div class="control-group" style="margin-top: 15px;">
						<input type="hidden" name="id" value="<?=$pageId?>">
					</div>
				</div>
				<div class="card-footer">
					<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-dot-circle-o"></i> <?=SAVE?></button>
				</div>
			</div>
		</form>
