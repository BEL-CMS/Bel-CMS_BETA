<?php
if (!defined('CHECK_INDEX')) {
	header($_SERVER['SERVER_PROTOCOL'] . ' 403 Direct access forbidden');
	exit(ERROR_INDEX);
}
$name = (empty($this->data)) ? UNKNOWN : $this->data[0]->thread_name;
$new  = (User::ReturnUser() !== false) ? '<a href="Forum/NewThread/'.GET_ID_SUPP.'" class="btn btn-primary btn-icon-left"><i class="fa fa-comments"></i>'.NEW_THREAD.'</a>' : '<a href="User/login" class="btn btn-primary btn-icon-left"><i class="fa fa-user"></i>'.LOGIN_REQUIRE.'</a>';
?>
<section id="bel_cms_forum_thread" class="padding-bottom-60">
	<div>
		<div class="headline">
			<h4 class="no-padding-top"><?=$name?></h4>
			<div class="pull-right">
				<?=$new?>
			</div>
		</div>
		<?php
		if (!empty($this->data)):
		?>
		<div class="forum forum-thread">
		<?php
		foreach ($this->data as $k => $v):
			$labelWarning = $v->options['pin'] == true ? '<span class="label label-warning icon"><i class="fa fa-thumb-tack"></i></span>' : '';
			$lock = $v->options['lock'] ? 'lock' : '';
			$link = $v->options['lock'] ? Common::CurrentPage() : 'Forum/Post/'.$v->title.'?id='.$v->id;
			?>
			<div class="forum-group <?=$lock?>">
				<div class="forum-icon hidden-xs"><i class="fa fa-comments"></i><?=$labelWarning?></div>
				<div class="forum-title">
					<h4><a href="<?php echo $link; ?>"><?php echo $v->title; ?></a></h4>
					<p><?=BY?> <a data-toggle="popuser" title="<?php echo $v->author; ?>"><?=$v->author?></a> <?php echo ON; ?> <?=Common::transformDate($v->date_post, true, 'd M Y # H:i'); ?></p>
				</div>
				<div class="forum-activity hidden-xs">
					<a href="User/Profil/<?=$v->lastpost->author?>" data-toggle="tooltip" title="<?php echo $v->lastpost->author; ?>">
						<img src="<?php echo $v->lastpost->avatar; ?>" alt="">
					</a>
					<div>
						<h4><a href="<?php echo $link; ?>"><?=LAST_POST?> <?=BY?> <?=$v->lastpost->author?></a></h4>
						<span><a href="#"><?=ON?></a> <?=$v->lastpost->date_post?></span>
					</div>
				</div>
				<div class="forum-meta visible-lg"><?=$v->options['post']?> posts</div>
				<div class="forum-meta visible-lg"><?=$v->options['view']?> views</div>
			</div>
			<?php
		endforeach;
		?>
		</div>
		<?php
		else:
			New Notification ('Forum', ERROR_NO_POST, 'blue');
		endif;
		?>
	</div>
</section>
