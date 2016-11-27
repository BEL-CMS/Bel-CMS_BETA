<?php
/**
 * Bel-CMS [Content management system]
 * @version 0.0.1
 * @link http://www.bel-cms.be
 * @link http://www.stive.eu
 * @license http://opensource.org/licenses/GPL-3.0 copyleft
 * @copyright 2014 Bel-CMS
 * @author Stive - mail@stive.eu
 */
if (!defined('CHECK_INDEX')) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 403 Direct access forbidden');
    exit(ERROR_INDEX);
}

if ($this->data):
?>
<section id="bel_cms_user_main">
	<div class="row">
		<div id="bel_cms_user_main_left" class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
			<div id="bel_cms_user_avatar">
				<img width="100" height="100" src="<?php echo $this->data->avatar; ?>" alt="avatar_<?php echo $this->data->username; ?>">
			</div>
			<span class="bel_cms_user_main_left_title"><i class="ion-ios-body-outline"></i>A propos</span>
			<div id="bel_cms_user_main_left_about">
				<?php echo $this->data->info_text; ?>
			</div>
			<ul id="bel_cms_user_main_left_menu">
				<li class="active"><a href="#profil_infos"><i class="ion-ios-world"></i>Profile information</a></li>
				<li><a href="#edit_profil_infos"><i class="ion-edit"></i>Change profile info</a></li>
				<li><a href="#edit_profil_social"><i class="ion-heart"></i>Change profile social</a></li>
				<li><a href="#edit_mail_pass"><i class="ion-key"></i>Change Email & Password</a></li>
				<li><a href="#edit_avatar"><i class="ion-images"></i>Manage avatar</a></li>
			</ul>

			<a class="logout" href="/User/Logout">Log-out</a>
		</div>

		<div id="bel_cms_user_main_right" class="col-lg-9 col-md-8 col-sm-8 col-xs-12">
			<?php
			profilInfos ($this->data);
			editProfilInfos ($this->data);
			editProfilSocial ($this->data);
			editMailPass ($this->data);
			editAvatar ($this->data);
			?>
		</div>

</section>
<?php
endif;

function profilInfos($data) {
	?>
		<div id="profil_infos" class="active">
			<div class="bel_cms_user_main_right_half">
				<span class="bel_cms_user_main_right_title"><span>Profile Information</span></span>
				<ul id="bel_cms_user_profile_infos">
					<li>
						<span>Name</span>
						<span><?php echo $data->username; ?></span>
					</li>
					<li>
						<span>Gender</span>
						<span><?php echo $data->gender; ?></span>
					</li>
					<li>
						<span>Birthday</span>
						<span><?php echo $data->birthday; ?></span>
					</li>
					<li>
						<span>Location</span>
						<span><?php echo $data->country; ?></span>
					</li>
					<li>
						<span>Signed up</span>
						<span><?php echo $data->date_registration; ?></span>
					</li>
				</ul>
			</div>
			<div class="bel_cms_user_main_right_half">
				<span class="bel_cms_user_main_right_title"><span>Friends</span></span>
				<ul id="bel_cms_user_friends">
					<?php
					foreach ($data->friends as $k => $v):
						?>
						<li>
							<a href="/Members/View/<?php echo $v['name']; ?>">
								<img width="100" height="100" src="<?php echo $v['avatar']; ?>" alt="<?php echo $v['name']; ?>">
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
						<a href="<?php echo $value; ?>">
							<img width="100" height="100" src="<?php echo $value; ?>" alt="<?php echo $alt; ?>">
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
	$genderM = $data->gender == 'MALE' ? 'checked="checked"' : '';
	$genderF = $data->gender == 'FEMALE' ? 'checked="checked"' : '';
	$genderU = $data->gender == 'UNISEXUAL' ? 'checked="checked"' : '';
	$data->info_text = $data->info_text == UNKNOWN ? '' : $data->info_text;
	?>
		<div id="edit_profil_infos">
			<span class="bel_cms_user_main_right_title"><span>Edition Profile Information</span></span>
			<form action="/User/Send" method="post" enctype="multipart/form-data">

				<div class="form-group input-icon-left">
					<i class="fa fa-user"></i>
					<input class="form-control" name="username" type="text" placeholder="Enter your name / pseudo." required="required" value="<?php echo $data->username; ?>" pattern="^[a-zA-Z][a-zA-Z0-9-_\.]{1,20}$">
				</div>

				<div class="form-group input-icon-left">
					<i class="fa fa-birthday-cake"></i>
					<input id="datepicker" class="form-control" name="birthday" type="text" value="<?php echo $data->birthday; ?>" pattern="(0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}">
				</div>


				<div class="form-group input-icon-left">
					<i class="fa fa-link"></i>
					<input class="form-control" name="websites" type="text" placeholder="Your website url." value="<?php echo $data->websites; ?>" pattern="https?://.+">
				</div>

				<div class="form-group margin-bottom-20">
					<label>Sex :</label>
					<div class="radio radio-inline">
						<input type="radio" <?php echo $genderM; ?> id="radio1" name="gender" value="male">
						<label for="radio1"><i class="fa fa-mars"></i></label>
					</div>
					<div class="radio radio-inline">
						<input type="radio" <?php echo $genderF; ?> id="radio2" name="gender" value="female">
						<label for="radio2"><i class="fa fa-mercury"></i></label>
					</div>
					<div class="radio radio-inline">
						<input type="radio" <?php echo $genderU; ?> value="unisexual" id="radio3" name="gender">
						<label for="radio3"><i class="fa fa-transgender"></i></label>
					</div>
				</div>

				<div class="form-group input-icon-left">
					<i class="fa fa-map-signs"></i>
					<input value="<?php echo $data->country; ?>" class="form-control" name="country" type="text" list="country">
					<datalist id="country">
						<?php
						foreach (Common::contryList() as $country):
							?>
							<option value="<?php echo $country; ?>"><?php echo $country; ?></option>
							<?php
						endforeach;
						?>
					</datalist>
				</div>

				<div class="form-group">
					<label>Description :</label>
					<textarea class="info_text" name="info_text"><?php echo $data->info_text; ?></textarea>
				</div>

				<div class="form-group input-icon-left">
					<input type="hidden" name="send" value="editprofile">
					<input class="btn btn-primary" type="submit" value="Update your profile">
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
			<span class="bel_cms_user_main_right_title"><span>Edition Profile Social</span></span>
			<form action="/User/Send" method="post" class="bel_cms">

				<div class="form-group input-icon-left">
					<i class="fa fa-facebook"></i>
					<input class="form-control" name="facebook" type="text" placeholder="Enter your facebook link" value="<?php echo $data->facebook; ?>" pattern="^[a-z\d\.]{5,}$">
				</div>

				<div class="form-group input-icon-left">
					<i class="fa fa-twitter"></i>
					<input class="form-control" name="twitter" type="text" placeholder="Enter your twitter link" value="<?php echo $data->twitter; ?>" pattern="^[A-Za-z0-9_]{1,15}$">
				</div>

				<div class="form-group input-icon-left">
					<i class="fa fa-google-plus"></i>
					<input class="form-control" name="googleplus" type="text" placeholder="Enter your gplus link" value="<?php echo $data->googleplus; ?>">
				</div>

				<div class="form-group input-icon-left">
					<i class="fa fa-pinterest-p"></i>
					<input class="form-control" name="pinterest" type="text" placeholder="Enter your pinterest link" value="<?php echo $data->pinterest; ?>">
				</div>

				<div class="form-group input-icon-left">
					<i class="fa fa-linkedin"></i>
					<input class="form-control" name="linkedin" type="text" placeholder="Enter your linkedin link" value="<?php echo $data->linkedin; ?>">
				</div>

				<div class="form-group">
					<input type="hidden" name="send" value="editsocial">
					<input class="btn btn-primary" type="submit" value="Update social media">
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
			<span class="bel_cms_user_main_right_title"><span>Change email and password</span></span>
			<form action="/User/Send" method="post">

				<div class="form-group input-icon-left">
					<i class="fa fa-envelope"></i>
					<input class="form-control" name="email" placeholder="Enter your private email" type="email" value="<?php echo $data->email; ?>" required="required">
				</div>

				<div class="form-group input-icon-left">
					<i class="fa fa-envelope-o"></i>
					<input class="form-control" name="public_mail" placeholder="Enter your public email" type="email" value="<?php echo $data->public_mail; ?>">
				</div>

				<div class="form-group input-icon-left">
					<i class="fa fa-lock"></i>
					<input class="form-control" name="newpassword" type="password" placeholder="Enter your new password" value="" pattern="[a-zA-ZÀ-ÿ#'/*-&@$%]{6,16}" autocomplete="off">
				</div>

				<div class="form-group input-icon-left">
					<i class="fa fa-unlock"></i>
					<input class="form-control" name="password" type="password" placeholder="Enter your password active (old)" value="" pattern="[a-zA-ZÀ-ÿ#'/*-&@$%]{6,16}" autocomplete="off">
				</div>

				<div class="form-group">
					<input type="hidden" name="send" value="mailpassword">
					<input class="btn btn-primary" type="submit" value="Update now">
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
							<tr id="user_tr_<?php echo $i; ?>">
								<td>
									<a class="bel_cms_jquery_avatar_sel" href="#<?php echo $value; ?>"><i class="fa fa-check-square"></i></a>
									<a data-id="#user_tr_<?php echo $i; ?>" class="bel_cms_jquery_avatar_del" href="#<?php echo $value; ?>"><i class="fa fa-eraser"></i></a>
								</td>
								<td><?php echo $value; ?></td>
								<td><img width="100" height="100" src="<?php echo $value; ?>" alt="<?php echo $alt; ?>"></td>
							</tr>
							<?php
							endforeach;
							if ($i == null):
							?>
							<tr>
								<td colspan="3">Empty</td>
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
					<input class="" name="avatar" type="file" placeholder="Enter your new password" value="Ajouter votre avatar">
				</div>
				<div class="form-group">
					<input type="hidden" name="send" value="sendavatar">
					<input class="btn btn-primary" type="submit" value="Add your avatar">
				</div>
			</form>
		</div>
	<?php
}
?>
