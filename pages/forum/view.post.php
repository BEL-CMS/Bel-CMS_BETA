<?php
if (!defined('CHECK_INDEX')) {
	header($_SERVER['SERVER_PROTOCOL'] . ' 403 Direct access forbidden');
	exit(ERROR_INDEX);
}
?>
		<section class="padding-bottom-60">
			<div class="">
				<?php
				foreach ($this->data as $k => $v):
					if ($k == 0):
				?>
					<div class="headline">
						<h4><i class="fa fa-comments"></i> <?php echo $v->title; ?></h4>
						<div class="pull-right">
							<a href="#" class="btn btn-default btn-icon-left"><i class="fa fa-lock"></i> LOCK_THREAD</a>
						</div>
					</div>
					<?php
					endif;
					?>

					<div class="forum-post">
						<div class="forum-panel">
							<div class="forum-user">
								<div>
									<a href="profile.html"><?php echo $v->author; ?></a>
								</div>
							</div>
							<div class="forum-body">
								<?php echo $v->content; ?>
							</div>
						</div>
						<div class="forum-footer">
							<ul class="post-action">
								<li><a href="#"><i class="fa fa-heart"></i> LIKE (<?php echo $v->options['like']; ?>)</a></li>
								<li><a href="#"><i class="fa fa-flag"></i> REPORT_POST</a></li>
							</ul>
							<ul class="post-meta">
								<li><i class="fa fa-calendar-o"></i> <?php echo Common::transformDate($v->date_post, true, 'd M Y - H:i'); ?></li>
								<li>#<?php echo $k + 1; ?></li>
							</ul>
						</div>
					</div>
				<?php
				endforeach;
				?>

					<div class="headline">
						<h4><i class="fa fa-comment"></i> <?php echo WRITE_A_REPLY; ?></h4>
					</div>
					<form action="Forum/Send" method="post" enctype="multipart/form-data">
						<div class="forum-post">
							<textarea class="text-forum" name="info_text"></textarea>
						</div>
						<input type="hidden" name="check" value="<?php echo $_SESSION['FORUM']; ?>">
						<input type="hidden" name="id" value="<?php echo $this->data[0]->id; ?>">
						<input type="hidden" name="send" value="SubmitReply">
						<input type="submit" value="<?php echo SUBMIT_POST; ?>" class="btn btn-primary btn-rounded btn-lg btn-shadow pull-right">
					</form>
			</div>
		</section>
