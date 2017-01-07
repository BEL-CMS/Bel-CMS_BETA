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
<div class="main">
	<div class="main-inner">
		<div class="container">
			<div class="row">

				<div class="span12">
					<div class="widget">
						<!-- debut Titre full -->
						<div class="widget-header">
							<i class="icon-th-large"></i>
							<h3><?=GROUPS.' : '. $pageName?></h3>
						</div>
						<!-- fin Titre full -->
						<!-- debut du contenue -->
						<div class="widget-content">
							<form action="Access/send_widgets_access?management" method="post" class="form-horizontal">
								<fieldset>
									<div class="control-group">
										<label class="control-label" for="label_name"><?=ACTIVE?></label>
										<div class="controls">
											<label class="checkbox">
												<?php
												$active = ($sql->data->activate == 1) ? 'checked="cheked"' : '';
												?>
												<input <?=$active?> name="activate" id="label_active" value="1" type="checkbox">
											</label>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="label_name"><?=CUSTOM_NAME?></label>
										<div class="controls">
											<input type="text" name="title" value="<?=$sql->data->title?>">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="label_name"><?=POSITION?></label>
										<div class="controls">
											<select name="pos">
												<?php
												$posTop    = $sql->data->pos == 'top'    ? 'selected="selected"' : '';
												$posRight  = $sql->data->pos == 'right'  ? 'selected="selected"' : '';
												$posBottom = $sql->data->pos == 'bottom' ? 'selected="selected"' : '';
												$posLeft   = $sql->data->pos == 'left'   ? 'selected="selected"' : '';
												?>
												<option <?=$posTop?> value="top"><?=TOP?></option>
												<option <?=$posRight?> value="right"><?=RIGHT?></option>
												<option <?=$posBottom?> value="bottom"><?=BOTTOM?></option>
												<option <?=$posLeft?> value="left"><?=LEFT?></option>
											</select>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="label_name"><?=POSITION?></label>
										<div class="controls">
											<input type="number" name="orderby" value="<?=$sql->data->orderby?>">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="label_name"><?=ACCESS_PAGE?></label>
										<div class="controls">
											<?php
											foreach ($groups as $k => $v):
												$checked = in_array($v, $groups_access) ? 'checked="cheked"' : '';
											?>
												<label class="checkbox">
													<input <?=$checked?> name="groups_access[]" id="label_<?=$k?>" value="<?=$k?>" type="checkbox"><?=$v?>
												</label>
											<?php
											endforeach;
											?>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="label_name"><?=TITLE_MANAGEMENT?></label>
										<div class="controls">
											<?php
											foreach ($groups as $k => $v):
												$checked = in_array($v, $groups_admin) ? 'checked="cheked"' : '';
											?>
												<label class="checkbox">
													<input <?=$checked?> name="groups_admin[]" id="label_<?=$k?>" value="<?=$k?>" type="checkbox"><?=$v?>
												</label>
											<?php
											endforeach;
											?>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="label_name"><?=PAGES?></label>
										<div class="controls">
											<?php
											foreach (Common::ScanDirectory(ROOT_PAGES) as $k => $v):
												$checked = in_array($v, $pages) ? 'checked="cheked"' : '';
											?>
												<label class="checkbox">
													<input <?=$checked?> name="pages[]" id="label_<?=$k?>" value="<?=$v?>" type="checkbox"><?=$v?>
												</label>
											<?php
											endforeach;
											?>
										</div>
									</div>
								<fieldset>
								<div class="control-group" style="margin-top: 15px;">
									<input type="hidden" name="page" value="<?=$sql->data->name?>">
									<input class="btn btn-primary" type="submit" value="<?=SAVE?>">
								</div>
							</form>
						</div>
						<!-- fin du contenue -->
					</div>
				</div>
		  	</div>

		</div>
	</div>
</div>
<?php
endif;