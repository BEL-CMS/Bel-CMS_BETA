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

if (!empty($post)):
?>
<section id="bel_cms_forum_post" class="padding-bottom-60">
	<div class="">
		<?php
		$k = 0;
		foreach ($post as $k => $v):
			if ($k == 0):
				if ($post[0]->options['lock'] == 1):
				?>
					<div class="headline">
						<h4><i class="fa fa-comments"></i> <?=defixUrl($v->title)?></h4>
						<div class="pull-right">
							<a href="forum/unlockPost/<?=$post[0]->id?>" class="btn btn-default btn-icon-left"><i class="fa fa-unlock"></i> <?=UNLOCK_THREAD?></a>
						</div>
					</div>
				<?php
				else:
				?>
					<div class="headline">
						<h4><i class="fa fa-comments"></i> <?=defixUrl($v->title)?></h4>
						<div class="pull-right">
							<a href="forum/lockPost/<?=$post[0]->id?>" class="btn btn-default btn-icon-left"><i class="fa fa-lock"></i> <?=LOCK_THREAD?></a>
						</div>
					</div>
				<?php
				endif;
			endif;
			?>

			<div class="forum-post">
				<div class="forum-header">
					<a href="Members/View/<?=$v->author?>" class="avatar">
						<img src="<?=$v->avatar?>" alt="avatar_<?=$v->author?>">
					</a>
					<div>
						<a href="Members/View/<?=$v->author?>"><?=$v->author?></a>
					</div>
				</div>
				<div class="forum-panel">
					<div class="forum-body">
						<?=$v->content?>
						<?php
						if (!empty($v->attachment)):
						?>
							<div class="attachment">
								<a href="<?=$v->attachment?>" target="_blank"><i class="fa fa-unlink"></i> <?=FILE?></a>
								<span>(<?=Common::SizeFile(ROOT.$v->attachment)?>) Size</span>
							</div>
						<?php
						endif;
						?>
					</div>
				</div>
				<div class="forum-footer hidden-xs">
					<ul class="post-action">
						<li><a href="forum/lockPost/<?=$v->id?>"><i class="fa fa-flag"></i> <?=REPORT_POST?></a></li>
					</ul>
					<ul class="post-meta">
						<li><i class="fa fa-calendar-o"></i> <?=Common::transformDate($v->date_post, 'FULL', 'NONE')?></li>
						<li>#<?=$k + 1?></li>
					</ul>
				</div>
			</div>
		<?php
		endforeach;
		if ($post[0]->options['lock'] == 0):
		?>
			<div class="headline">
				<h4><i class="fa fa-comment"></i> <?=WRITE_A_REPLY?></h4>
			</div>
			<form action="Forum/Send" method="post" enctype="multipart/form-data">
				<div class="forum-post">
					<textarea class="bel_cms_textarea_simple" name="info_text"></textarea>
				</div>
				<div class="form-group">
					<label class="btn btn-default btn-file">File attachment
						<input type="file" name="file" style="display: none;">
					</label>
				</div>
				<input type="hidden" name="id" value="<?=$post[0]->id?>">
				<input type="hidden" name="send" value="SubmitReply">
				<input type="submit" value="<?=SUBMIT_POST?>" class="btn btn-primary btn-rounded btn-lg btn-shadow pull-right">
			</form>
		<?php
		endif;
		?>
	</div>
</section>
<?php
endif;
