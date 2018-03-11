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
	if (true):
?>
<div class="row">
	<div class="col-sm-12">
		<div class="card">
			<div class="card-header">
				<strong>Sous-Pages</strong>
			</div>
			<div class="card-body">
				<ul class="nav nav-tabs" role="tablist">
					<li class="nav-item">
						<a class="nav-link active" data-toggle="tab" href="#private" role="tab" aria-controls="home"><?=constant('PRIVATE')?></a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#profil" role="tab" aria-controls="profile"><?=PROFIL?></a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#social" role="tab" aria-controls="messages"><?=SOCIAL?></a>
					</li>
				</ul>
			</div>
		</div>

		<form action="User/senduser/<?=$private->hash_key?>?management" method="post">
			<div class="tab-content">

				<div id="private" class="tab-pane active">
					<div class="card">
						<div class="card-header">
							<strong><?=constant('PRIVATE')?></strong>
						</div>
						<div class="card-body">
							<div class="form-group">
								<label class="control-label" for="label_name"><?=USERNAME?> :</label>
								<div class="controls">
									<input class="form-control" name="username" type="text" id="label_name" placeholder="Nom d'utilisateur" required="required" value="<?=$private->username?>">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label" for="label_pass"><?=PASSWORD?> :</label>
								<div class="controls">
									<input class="form-control" name="password" type="password" placeholder="A rempli uniquement en cas de changement !" id="label_pass" autocomplete="off" value="">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label" for="label_mail"><?=MAIL?> :</label>
								<div class="controls">
									<input class="form-control" name="email" type="email"  id="label_mail" placeholder="<?=MAIL.' '.constant('PRIVATE')?>" required="required" value="<?=$private->email?>">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label" for="label_main_groups"><?=MAIN_GROUP?> :</label>
								<div class="controls">
									<select class="form-control" name="main_groups">
									<?php
									foreach (config::GetGroups() as $k => $v):
										$selected = $private->main_groups == $k ? 'selected' : '';
										?>
										<option <?=$selected?> value="<?=$k?>"><?=$v?></option>
										<?php
									endforeach;
									?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label"><?=GROUPS?> :</label>
								<div class="controls">
									<?php
									foreach (config::GetGroups() as $k => $v):
										$groups  = explode('|', $private->groups);
										$checked = in_array($k, $groups) ? 'checked="checked"' : '';
										?>
										<input class="form-control" id="label_groups_<?=$k?>" <?=$checked?> type="checkbox" name="groups[]" value="<?=$k?>"><span for="label_groups_<?=$k?>"><?=$v?></span><br>
										<?php
									endforeach;
									?>
								</div>
							</div>
						</div>
						<div class="card-footer">
							<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-dot-circle-o"></i> <?=EDIT?></button>
						</div>
					</div>
				</div>

				<div id="profil" class="tab-pane">
					<div class="card">
						<div class="card-header">
							<strong><?=constant('PROFIL')?></strong>
						</div>
						<div class="card-body">
							<div class="form-group">
								<label class="control-label" for="label_mail_public"><?=MAIL?> :</label>
								<div class="controls">
									<input class="form-control" name="public_mail" type="email" class="span6" id="label_mail_public" placeholder="<?=MAIL.' '.constant('PUBLIC')?>" value="<?=$profil->public_mail?>">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label" for="label_web"><?=WEBSITE?> :</label>
								<div class="controls">
									<input class="form-control" name="websites" type="url" class="span6" id="label_web" placeholder="http://" value="<?=$profil->websites?>">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label" for="label_birthday"><?=BIRTHDAY?> :</label>
								<div class="controls">
									<input id="label_birthday" class="datepicker form-control" name="birthday" type="text" value="<?=$profil->birthday?>">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label" for="label_country"><?=COUNTRY?> :</label>
								<div class="controls">
									<select class="form-control" name="country" class="span6">
										<?php
										foreach (Common::contryList() as $k => $v):
											$selected = $profil->country == $v ? 'selected="selected"' : '';
											echo '<option '.$selected.' value="'.$v.'">'.$v.'</option>';
										endforeach;
										?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label" for="label_main_groups"><?=GENDER?> :</label>
								<div class="controls">
								<?php
									$profil->gender = mb_strtoupper($profil->gender);
									$genderM = strtoupper($profil->gender) == strtoupper(constant('MALE')) ? 'selected' : '';
									$genderF = strtoupper($profil->gender) == strtoupper(constant('FEMALE')) ? 'selected' : '';
									$genderU = strtoupper($profil->gender) == strtoupper(constant('UNISEXUAL')) ? 'selected' : '';
								?>
									<select class="form-control"  name="gender">
										<option <?=$genderM?> value="male"><?=constant('MALE')?></option>
										<option <?=$genderF?> value="female"><?=constant('FEMALE')?></option>
										<option <?=$genderU?> value="unisexual"><?=constant('UNKNOWN')?></option>
									</select>
								</div>
							</div>
							<label class="control-label" for="label_main_groups"><?=DESCRIPTION?> :</label>
							<div class="controls">
								<textarea class="bel_cms_textarea_simple" name="info_text" placeholder="Votre description..."><?=$profil->info_text; ?></textarea>
							</div>
						</div>
						<div class="card-footer">
							<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-dot-circle-o"></i> <?=EDIT?></button>
						</div>
					</div>
				</div>

				<div id="social" class="tab-pane">
					<div class="card">
						<div class="card-header">
							<strong><?=constant('SOCIAL')?></strong>
						</div>
						<div class="card-body">
							<?php
							foreach ($listSocial as $name):
								$label = defined(strtoupper($name)) ? constant(strtoupper($name)) : ucfirst($name);
							?>
							<div class="form-group">
								<label class="control-label" for="label_<?=$name;?>"><?=$label?> :</label>
								<div class="controls">
									<input class="form-control" name="<?=$name?>" type="text" class="span6" id="label_<?=$label;?>" value="<?=$social->$name?>">
								</div>
							</div>
							<?php
							endforeach;
							?>
						</div>
						<div class="card-footer">
							<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-dot-circle-o"></i> <?=EDIT?></button>
						</div>
					</div>
				</div>

			</div>
		</form>

	</div>
</div>
<?php
	endif;
endif;
