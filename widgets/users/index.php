<?php
if (AutoUser::ReturnUser() === false):
?>
<div class="panel panel-default">
	<div class="panel-heading"><?=$users['title'] ?></div>
	<div class="panel-body">
		<div id="bel_cms_widgets_user" class="widget">
			<span id="have_back"><?=GREAT_HAVE_BACK?></span>
			<form action="User/Send" method="post">
				<div class="form-group">
					<label for="username">Email address</label>
					<input type="text" class="form-control" id="username" placeholder="username or email" name="username" required="required">
				</div>
				<div class="form-group">
					<label for="password">Password</label>
					<input type="password" class="form-control" id="password" name="password" required="required" placeholder="Password">
				</div>
				<button type="submit" class="btn btn-success btn-block"><?=SIGN_IN?></button>
				<input name="send" value="login" type="hidden">
				<input name="remember" value="true" type="hidden">
			</form>
			<p id="new_here"><?=NEW_HERE?> <a href="User/Register"><?=CREATE_USER_TO_WEB?></a></p>
		</div>
	</div>
</div>
<?php
else:
	$user = AutoUser::ReturnUser();
?>
<div class="panel panel-default">
	<div class="panel-heading"><?=$users['title'] ?></div>
	<div class="panel-body">
		<div id="bel_cms_widgets_user" class="widget">
			<img id="bel_cms_widgets_user_img" src="<?=$user->avatar?>" alt="avatar_<?=$user->username?>" class="img-circle">
			<span id="bel_cms_widgets_user_username"><?=$user->username?></span>
			<ul id="bel_cms_widgets_user_ul">
				<li>
					<a href="https://www.facebook.com/<?=$user->facebook?>">
						<i class="fa fa-facebook" aria-hidden="true"></i>
					</a>
				</li>
				<li>
					<a href="https://twitter.com/<?=$user->twitter?>">
						<i class="fa fa-twitter" aria-hidden="true"></i>
					</a>
				</li>
				<li>
					<a href="<?=$user->googleplus?>">
						<i class="fa fa-google-plus" aria-hidden="true"></i>
					</a>
				</li>
			</ul>
			<?php
			if (!empty($user->info_text)):
			?>
			<span id="bel_cms_widgets_user_desc">
				<?=$user->info_text?>
			</span>
			<?php endif; ?>
			<div id="bel_cms_widgets_use_log">
				<a href="User/Logout" title="Log-out">
					<i class="fa fa-sign-out" aria-hidden="true"></i>
					<?=SIGN_OUT?>
				</a>
				<a href="User" title="userbox">
					<i class="fa fa-user" aria-hidden="true"></i>
					<?=PROFIL?>
				</a>
			</div>
			<div class="clear"></div>
		</div>

	</div>
</div>
<?php
endif;