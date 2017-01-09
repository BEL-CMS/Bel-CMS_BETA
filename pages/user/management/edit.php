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
							<h3><?=USER.' : '.EDIT?></h3>
						</div>
						<!-- fin Titre full -->
						<!-- debut du contenue -->
						<div class="widget-content">
							<!-- debut des boutton action -->
							<div class="form-actions">
								<button class="btn" onclick="window.location.href='User?management'"><i class="icon-home"></i> <?=HOME?></button>
								<button class="btn" onclick="window.location.href='User/New?management'"><i class="icon-plus"></i> <?=ADD?></button>
								<button class="btn" onclick="window.location.href='User/Parameter?management'"><i class="icon-wrench"></i> <?=PARAMETER?></button>
							</div>
							<ul class="nav nav-tabs">
								<li class="active">
									<a href="#private" data-toggle="tab"><?=constant('PRIVATE')?></a>
								</li>
								<li class="">
									<a href="#profil" data-toggle="tab"><?=PROFIL?></a>
								</li>
								<li class="">
									<a href="#social" data-toggle="tab"><?=SOCIAL?></a>
								</li>
							</ul>
							
							<form action="User/senduser/<?=$this->data['private']->hash_key?>?management" method="post" class="form-horizontal tab-content">
								<div id="private" class="tab-pane active">
									<fieldset>
										<div class="control-group">
											<label class="control-label" for="label_name"><?=USERNAME?> :</label>
											<div class="controls">
												<input name="username" type="text" class="span6" id="label_name" placeholder="Nom d'utilisateur" required="required" value="<?=$this->data['private']->username?>">
											</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="label_pass"><?=PASSWORD?> :</label>
											<div class="controls">
												<input name="password" type="password" class="span6" placeholder="A rempli uniquement en cas de changement !" id="label_pass" autocomplete="off" value="">
											</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="label_mail"><?=MAIL?> :</label>
											<div class="controls">
												<input name="email" type="email" class="span6" id="label_mail" placeholder="<?=MAIL.' '.constant('PRIVATE')?>" required="required" value="<?=$this->data['private']->email?>">
											</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="label_main_groups"><?=MAIN_GROUP?> :</label>
											<div class="controls">
												<select class="span6" name="main_groups">
												<?php
												foreach ($GLOBALS['GROUPS'] as $k => $v):
													$selected = $this->data['private']->main_groups == $k ? 'selected' : '';
													?>
													<option <?=$selected?> value="<?=$k?>"><?=$v?></option>
													<?php
												endforeach;
												?>
												</select>
											</div>
										</div>
										<div class="control-group">
											<label class="control-label"><?=GROUPS?> :</label>
											<div class="controls">
												<?php
												foreach ($GLOBALS['GROUPS'] as $k => $v):
													$groups  = explode('|', $this->data['private']->groups);
													$checked = in_array($k, $groups) ? 'checked="checked"' : '';
													?>
													<input class="span1" id="label_groups_<?=$k?>" <?=$checked?> type="checkbox" name="groups[]" value="<?=$k?>"><span for="label_groups_<?=$k?>"><?=$v?></span><br>
													<?php
												endforeach;
												?>
											</div>
										</div>
										<div class="control-group" style="margin-top: 15px;">
											<input class="btn btn-primary" type="submit" value="<?=EDIT?>">
										</div>
									<fieldset>
								</div>
								<div id="profil" class="tab-pane">
									<fieldset>
										<div class="control-group">
											<label class="control-label" for="label_mail_public"><?=MAIL?> :</label>
											<div class="controls">
												<input name="public_mail" type="email" class="span6" id="label_mail_public" placeholder="<?=MAIL.' '.constant('PUBLIC')?>" value="<?=$this->data['profil']->public_mail?>">
											</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="label_web"><?=WEBSITE?> :</label>
											<div class="controls">
												<input name="websites" type="url" class="span6" id="label_web" placeholder="http://" value="<?=$this->data['profil']->websites?>">
											</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="label_birthday"><?=BIRTHDAY?> :</label>
											<div class="controls">
												<input id="label_birthday" class="span6 datepicker" name="birthday" type="text" value="<?=$this->data['profil']->birthday?>">
											</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="label_country"><?=COUNTRY?> :</label>
											<div class="controls">
												<select name="country" class="span6">
													<?php
													foreach (Common::contryList() as $k => $v):
														$selected = $this->data['profil']->country == $v ? 'selected="selected"' : '';
														echo '<option '.$selected.' value="'.$v.'">'.$v.'</option>';
													endforeach;
													?>
												</select>
											</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="label_main_groups"><?=GENDER?> :</label>
											<div class="controls">
											<?php
												$this->data['profil']->gender = mb_strtoupper($this->data['profil']->gender);
												$genderM = strtoupper($this->data['profil']->gender) == strtoupper(constant('MALE')) ? 'selected' : '';
												$genderF = strtoupper($this->data['profil']->gender) == strtoupper(constant('FEMALE')) ? 'selected' : '';
												$genderU = strtoupper($this->data['profil']->gender) == strtoupper(constant('UNISEXUAL')) ? 'selected' : '';
											?>
												<select class="span6" name="gender">
													<option <?=$genderM?> value="male"><?=constant('MALE')?></option>
													<option <?=$genderF?> value="female"><?=constant('FEMALE')?></option>
													<option <?=$genderU?> value="unisexual"><?=constant('UNKNOWN')?></option>
												</select>
											</div>
										</div>
										<label class="control-label" for="label_main_groups"><?=DESCRIPTION?> :</label>
										<div class="controls">
											<textarea class="bel_cms_textarea_simple" name="info_text" placeholder="Votre description..."><?=$this->data['profil']->info_text; ?></textarea>
										</div>
										<div class="control-group" style="margin-top: 15px;">
											<input class="btn btn-primary" type="submit" value="<?=EDIT?>">
										</div>
									</fieldset>
								</div>
								<div id="social" class="tab-pane">
									<fieldset>
										<?php
										foreach ($this->data['listSocial'] as $name):
											$label = defined(strtoupper($name)) ? constant(strtoupper($name)) : ucfirst($name);
										?>
										<div class="control-group">
											<label class="control-label" for="label_<?=$name;?>"><?=$label?> :</label>
											<div class="controls">
												<input name="<?=$name?>" type="text" class="span6" id="label_<?=$label;?>" value="<?=$this->data['social']->$name?>">
											</div>
										</div>
										<?php
										endforeach;
										?>
										<div class="control-group" style="margin-top: 15px;">
											<input class="btn btn-primary" type="submit" value="<?=EDIT?>">
										</div>
									</fieldset>
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