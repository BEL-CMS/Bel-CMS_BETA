<?php
if (!defined('CHECK_INDEX')) {
	header($_SERVER['SERVER_PROTOCOL'] . ' 403 Direct access forbidden');
	exit(ERROR_INDEX);
}
?>
		<section id="bel_cms_forum_post" class="padding-bottom-60">
			<div class="">
				<?php
				foreach ($this->data as $k => $v):
					if ($k == 0):
				?>
					<div class="headline">
						<h4><i class="fa fa-comments"></i> <?=$v->title?></h4>
						<div class="pull-right">
							<a href="#" class="btn btn-default btn-icon-left"><i class="fa fa-lock"></i> <?=LOCK_THREAD?></a>
						</div>
					</div>
					<?php
					endif;
					?>

					<div class="forum-post">
						<div class="forum-panel">
							<div class="forum-user">
								<a href="Members/View/<?=$v->author?>" class="avatar">
									<img src="<?=$v->avatar?>" alt="avatar_<?=$v->author?>">
									<!-- futur connect <span class="label label-success"></span></a>-->
								<div>
									<a href="Members/View/<?=$v->author?>"><?=$v->author?></a>
									<span>Member Since</span>
									<span class="date"><?=$v->registration?></span>
								</div>
							</div>
							<div class="forum-body">
								<?=$v->content?>
								<?php
								if (!empty($v->attachment)):
								?>
									<div class="attachment">
										<h4>Attachment</h4>
										<a href="<?=$v->attachment?>"><i class="fa fa-unlink"></i> <?=FILE?></a>
										<span>(<?=Common::SizeFile(ROOT.$v->attachment)?>) Size</span>
									</div>
								<?php
								endif;
								?>
							</div>
						</div>
						<div class="forum-footer hidden-xs">
							<ul class="post-action">
								<li><a href="#"><i class="fa fa-heart"></i> <?=LIKE;?> (<?=$v->options['like']?>)</a></li>
								<li><a href="#"><i class="fa fa-flag"></i> <?=REPORT_POST;?></a></li>
							</ul>
							<ul class="post-meta">
								<li><i class="fa fa-calendar-o"></i> <?=Common::transformDate($v->date_post, true, 'd M Y - H:i')?></li>
								<li>#<?=$k + 1?></li>
							</ul>
						</div>
					</div>
				<?php
				endforeach;
				?>
				<div class="headline">
					<h4><i class="fa fa-comment"></i> <?=WRITE_A_REPLY?></h4>
				</div>
				<form action="Forum/Send" method="post" enctype="multipart/form-data">
					<div class="forum-post">
						<textarea class="text-forum" name="info_text"></textarea>
					</div>
					<div class="form-group">
					<label class="btn btn-default btn-file">File attachment
						<input type="file" name="file" style="display: none;">
					</label>
					</div>
					<input type="hidden" name="check" value="<?=$_SESSION['FORUM']?>">
					<input type="hidden" name="id" value="<?=$this->data[0]->id?>">
					<input type="hidden" name="send" value="SubmitReply">
					<input type="submit" value="<?=SUBMIT_POST?>" class="btn btn-primary btn-rounded btn-lg btn-shadow pull-right">
				</form>
			</div>
		</section>
