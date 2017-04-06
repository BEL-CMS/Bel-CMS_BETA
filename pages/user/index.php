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

if (AutoUser::isLogged() === true):
?>
<section id="bel_cms_user_main">
	<div class="row">
		<div id="bel_cms_user_main_left" class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
			<div id="bel_cms_user_avatar">
				<img width="100" height="100" src="<?=$user->avatar?>" alt="avatar_<?= $user->username?>">
			</div>
			<span class="bel_cms_user_main_left_title"><i class="ion-ios-body-outline"></i>A propos</span>
			<div id="bel_cms_user_main_left_about">
				<?=$user->info_text?>
			</div>
			<ul id="bel_cms_user_main_left_menu">
				<li class="active"><a href="#profil_infos"><i class="ion-ios-world"></i><?=constant('INFO_PERSO')?></a></li>
				<li><a href="#edit_profil_infos"><i class="ion-edit"></i><?=constant('EDIT_PROFIL')?></a></li>
				<li><a href="#edit_profil_social"><i class="ion-heart"></i><?=constant('EDIT_PROFIL_SOCIAL')?></a></li>
				<li><a href="#edit_mail_pass"><i class="ion-key"></i><?=constant('EDIT_MAIL_PASS')?></a></li>
				<li><a href="#edit_avatar"><i class="ion-images"></i><?=constant('MANAGE_AVATAR')?></a></li>
			</ul>

			<a class="logout" href="User/Logout"><?=constant('SIGN_OUT')?></a>
		</div>

		<div id="bel_cms_user_main_right" class="col-lg-9 col-md-8 col-sm-8 col-xs-12">
			<?php
			profilInfos ($user);
			editProfilInfos ($user);
			editProfilSocial ($user);
			editMailPass ($user);
			editAvatar ($user);
			?>
		</div>
	</div>
</section>
<?php
endif;

function profilInfos($data) {
	?>
		<div id="profil_infos" class="active">
			<div class="bel_cms_user_main_right_half">
				<span class="bel_cms_user_main_right_title"><span><?=constant('INFO_PERSO')?></span></span>
				<ul id="bel_cms_user_profile_infos">
					<li>
						<span><?=constant('NAME')?></span>
						<span><?=$data->username?></span>
					</li>
					<li>
						<span><?=constant('GENDER')?></span>
						<span><?=$data->gender?></span>
					</li>
					<li>
						<span><?=constant('BIRTHDAY')?></span>
						<span><?=$data->birthday?></span>
					</li>
					<li>
						<span><?=constant('LOCATION')?></span>
						<span><?=$data->country?></span>
					</li>
					<li>
						<span><?=constant('DATE_INSCRIPTION')?></span>
						<span><?=$data->date_registration?></span>
					</li>
				</ul>
			</div>
			<div class="bel_cms_user_main_right_half">
				<span class="bel_cms_user_main_right_title"><span><?=constant('FRIENDS')?></span></span>
				<ul id="bel_cms_user_friends">
					<?php
					foreach ($data->friends as $k => $v):
						?>
						<li>
							<a href="Members/View/<?=$v['name']?>">
								<img width="100" height="100" src="<?=$v['avatar']?>" alt="<?=$v['name']?>">
							</a>
						</li>
						<?php
					endforeach;
					?>
				</ul>
			</div>
			<div class="bel_cms_user_main_right_full">
				<span class="bel_cms_user_main_right_title"><span>Mes avatars</span></span>
				<ul id="bel_cms_user_ul_avatar">
					<?php
					foreach ($data->list_avatar as $value):
						$alt = str_replace('uploads/users/'.$data->hash_key.'/', '', $value);
					?>
					<li>
						<a href="<?=$value?>?ajax">
							<img width="100" height="100" src="<?=$value?>" alt="<?=$alt?>">
							<span>Selectionner</span>
						</a>
					</li>
					<?php
					endforeach;
					?>
				</ul>
			</div>
		</div>

	<?php
}
function editProfilInfos ($data) {
	$data->gender = mb_strtoupper($data->gender);
	$genderM = strtoupper($data->gender) == strtoupper(constant('MALE')) ? 'checked="checked"' : '';
	$genderF = strtoupper($data->gender) == strtoupper(constant('FEMALE')) ? 'checked="checked"' : '';
	$genderU = strtoupper($data->gender) == strtoupper(constant('UNISEXUAL')) ? 'checked="checked"' : '';
	$data->info_text = $data->info_text == UNKNOWN ? '' : $data->info_text;
	?>
		<div id="edit_profil_infos">
			<span class="bel_cms_user_main_right_title"><span><?=constant('EDIT_PROFIL')?></span></span>
			<form action="User/Send" method="post" enctype="multipart/form-data">

				<div class="form-group input-icon-left">
					<i class="fa fa-user"></i>
					<input class="form-control" name="username" type="text" placeholder="<?=constant('ENTER_NAME_PSEUDO')?>" required="required" value="<?=$data->username?>" pattern="^[a-zA-Z][a-zA-Z0-9-_\.]{1,20}$">
				</div>

				<div class="form-group input-icon-left">
					<i class="fa fa-birthday-cake"></i>
					<input id="datepicker" class="form-control" name="birthday" type="text" value="<?=$data->birthday?>" pattern="(0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}">
				</div>


				<div class="form-group input-icon-left">
					<i class="fa fa-link"></i>
					<input class="form-control" name="websites" type="text" placeholder="Your website url." value="<?=$data->websites?>" pattern="https?://.+">
				</div>

				<div class="form-group margin-bottom-20">
					<label>Sex :</label>
					<div class="radio radio-inline">
						<input type="radio" <?=$genderM?> id="radio1" name="gender" value="male">
						<label for="radio1"><i class="fa fa-mars"></i></label>
					</div>
					<div class="radio radio-inline">
						<input type="radio" <?=$genderF?> id="radio2" name="gender" value="female">
						<label for="radio2"><i class="fa fa-mercury"></i></label>
					</div>
					<div class="radio radio-inline">
						<input type="radio" <?=$genderU?> value="unisexual" id="radio3" name="gender">
						<label for="radio3"><i class="fa fa-transgender"></i></label>
					</div>
				</div>

				<div class="form-group input-icon-left">
					<i class="fa fa-map-signs"></i>
					<select name="country" class="form-control">
						<?php
						foreach (Common::contryList() as $k => $v):
							$selected = $data->country == $v ? 'selected="selected"' : '';
							echo '<option '.$selected.' value="'.$v.'">'.$v.'</option>';
						endforeach;
						?>
					</select>
				</div>

				<div class="form-group">
					<label>Description :</label>
					<textarea class="bel_cms_textarea_simple" name="info_text" placeholder="Votre description..."><?php echo $data->info_text; ?></textarea>
				</div>

				<div class="form-group input-icon-left">
					<input type="hidden" name="send" value="editprofile">
					<input class="btn btn-primary" type="submit" value="<?=constant('UPDATE_NOW')?>">
				</div>
			</form>
		</div>
	<?php
}
function editProfilSocial ($data) {
	foreach ($data as $k => $v) {
		$data->$k = $v == UNKNOWN ? '' : $v;
	}
	?>
		<div id="edit_profil_social">
			<span class="bel_cms_user_main_right_title"><span><?=constant('EDIT_PROFIL_SOCIAL')?></span></span>
			<form action="/User/Send" method="post" class="bel_cms">

				<div class="form-group input-icon-left">
					<i class="fa fa-facebook"></i>
					<input class="form-control" name="facebook" type="text" placeholder="<?=constant('ENTER_YOUR');?> facebook <?=constant('LINK')?>" value="<?=$data->facebook?>" pattern="^[a-z\d\.]{5,}$">
				</div>

				<div class="form-group input-icon-left">
					<i class="fa fa-twitter"></i>
					<input class="form-control" name="twitter" type="text" placeholder="<?=constant('ENTER_YOUR');?> twitter <?=constant('LINK')?>" value="<?=$data->twitter?>" pattern="^[A-Za-z0-9_]{1,15}$">
				</div>

				<div class="form-group input-icon-left">
					<i class="fa fa-google-plus"></i>
					<input class="form-control" name="googleplus" type="text" placeholder="<?=constant('ENTER_YOUR');?> gplus <?=constant('LINK')?>" value="<?=$data->googleplus?>">
				</div>

				<div class="form-group input-icon-left">
					<i class="fa fa-pinterest-p"></i>
					<input class="form-control" name="pinterest" type="text" placeholder="<?=constant('ENTER_YOUR');?> pinterest <?=constant('LINK')?>" value="<?=$data->pinterest?>">
				</div>

				<div class="form-group input-icon-left">
					<i class="fa fa-linkedin"></i>
					<input class="form-control" name="linkedin" type="text" placeholder="<?=constant('ENTER_YOUR');?> linkedin <?=constant('LINK')?>" value="<?=$data->linkedin?>">
				</div>

				<div class="form-group">
					<input type="hidden" name="send" value="editsocial">
					<input class="btn btn-primary" type="submit" value="<?=constant('UPDATE_NOW')?>">
				</div>
			</form>
		</div>
<?php
}
function editMailPass ($data)
{
	$data->public_mail = $data->public_mail == UNKNOWN ? '' : $data->public_mail;
	?>
		<div id="edit_mail_pass">
			<span class="bel_cms_user_main_right_title"><span><?=constant('EDIT_MAIL_PASS')?></span></span>
			<form action="/User/Send" method="post">

				<div class="form-group input-icon-left">
					<i class="fa fa-envelope"></i>
					<input class="form-control" name="email" placeholder="<?=constant('ENTER_YOUR');?> <?=constant('PRIVATE')?> <?=constant('MAIL')?>" type="email" value="<?=$data->email?>" required="required">
				</div>

				<div class="form-group input-icon-left">
					<i class="fa fa-envelope-o"></i>
					<input class="form-control" name="public_mail" placeholder="<?=constant('ENTER_YOUR');?> <?=constant('PUBLIC')?> <?=constant('MAIL')?>" type="email" value="<?=$data->public_mail?>">
				</div>

				<div class="form-group input-icon-left">
					<i class="fa fa-lock"></i>
					<input class="form-control" name="newpassword" type="password" placeholder="<?=constant('ENTER_YOUR');?> <?=constant('NEW_PASSWORD')?>" value="" pattern="[a-zA-ZÀ-ÿ#'/*-&@$%]{6,16}" autocomplete="off">
				</div>

				<div class="form-group input-icon-left">
					<i class="fa fa-unlock"></i>
					<input class="form-control" name="password" type="password" placeholder="<?=constant('ENTER_YOUR');?> <?=constant('ACTUAL_PASSWORD')?>" value="" pattern="[a-zA-ZÀ-ÿ#'/*-&@$%]{6,16}" autocomplete="off">
				</div>

				<div class="form-group">
					<input type="hidden" name="send" value="mailpassword">
					<input class="btn btn-primary" type="submit" value="<?=constant('UPDATE_NOW')?>">
				</div>

			</form>
		</div>
	<?php
}
function editAvatar ($data) {
	?>
		<div id="edit_avatar">
			<div class="panel panel-default margin-bottom-20">
				<div class="panel-body">
					<table class="table table-striped">
						<thead>
							<tr>
								<th>Actions</th>
								<th>Avatar Link</th>
								<th>Image Preview</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i=null;
							foreach ($data->list_avatar as $value):
								$i++;
								$alt = str_replace('uploads/users/'.$data->hash_key.'/', '', $value);
							?>
							<tr id="user_tr_<?=$i?>">
								<td>
									<a class="bel_cms_jquery_avatar_sel" href="#<?=$value?>"><i class="fa fa-check-square"></i></a>
									<a data-id="#user_tr_<?=$i?>" class="bel_cms_jquery_avatar_del" href="#<?=$value?>"><i class="fa fa-eraser"></i></a>
								</td>
								<td><?=$value?></td>
								<td><img width="100" height="100" src="<?=$value?>" alt="<?=$alt?>"></td>
							</tr>
							<?php
							endforeach;
							if ($i == null):
							?>
							<tr>
								<td colspan="3"><?=constant('EMPTY')?></td>
							</tr>
							<?php
							endif;
							?>
						</tbody>
					</table>
				</div>
			</div>
			<form action="User/Send" method="post" enctype="multipart/form-data">
				<div class="form-group input-icon-left">
					<div class="input-group">
						<label class="input-group-btn">
							<div class="btn btn-primary"><?=constant('IMPORT_IMG')?><input name="avatar" type="file" style="display: none;"></div>
						</label>
					</div>
				</div>
				<div class="form-group">
					<input type="hidden" name="send" value="sendavatar">
					<input class="btn btn-primary" type="submit" value="<?=constant('ADD_YOUR_AVATAR')?>">
				</div>
			</form>
		</div>
	<?php
}
?>
