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
							<form action="Access/send_page_access?management" method="post" class="form-horizontal">
								<fieldset>
									<div class="control-group">
										<label class="control-label" for="label_name"><?=ACTIVE?></label>
										<div class="controls">
											<label class="checkbox">
												<?php
												$active = ($sql->data->active == 1) ? 'checked="cheked"' : '';
												?>
												<input <?=$active?> name="active" id="label_active" value="1" type="checkbox">
											</label>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="label_name"><?=ACCESS_PAGE?></label>
										<div class="controls">
											<?php
											foreach ($groups as $k => $v):
												$checked = in_array($v, $access_groups) ? 'checked="cheked"' : '';
											?>
												<label class="checkbox">
													<input <?=$checked?> name="access_groups[]" id="label_<?=$k?>" value="<?=$k?>" type="checkbox"><?=$v?>
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
												$checked = in_array($v, $access_admin) ? 'checked="cheked"' : '';
											?>
												<label class="checkbox">
													<input <?=$checked?> name="access_admin[]" id="label_<?=$k?>" value="<?=$k?>" type="checkbox"><?=$v?>
												</label>
											<?php
											endforeach;
											?>
										</div>
									</div>
									<?php
									if ($sql->data->config !== null):
									?>
									<div class="control-group">
										<label class="control-label" for="label_config"><?=CONFIG?></label>
										<div class="controls">
											<?php
											foreach ($config as $k => $v):
												$name = defined($k) ? constant($k) : $k;
											?>
												<label class="checkbox">
													<span class="span3"><?=$name?></span>
													<input <?=$checked?> name="config[<?=$k?>]" type="text" value="<?=$v?>">
												</label>
											<?php
											endforeach;
											?>
										</div>
									</div>
									<?php
									endif;
									?>
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