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
?>
<section id="bel_cms_blog_main">
<?php
foreach ($blog as $k => $v):
$count_comment = new Comment('count', 'blog','readmore', $v->id);
$count_comment = $count_comment->count;
?>
<div class="row">
	<div class="col-md-12">
		<h2><?=$v->name?></h2>
	</div>
	<div class="col-md-12">
		<div class="bel_cms_blog_blog_text">
			<?=$v->content?>
		</div>
	</div>
	<ul class="col-md-12" id="bel_cms_blog_meta">
		<li class="meta-author">
			<span class="fa fa-user"></span>
			<a href="Members/<?=$v->author->username?>" title="Posts by <?=$v->author->username?>"><?=$v->author->username?></a>
		</li>
		<li class="meta-date"><span class="fa fa-clock-o"></span><?=Common::transformDate($v->date_create, true, 'd-M-Y # H:i') ?></li>
		<li class="meta-comments">
			<span class="fa fa-comment-o"></span>
			<a href="<?=$v->link?>" class="comments-link" title="Comments: <?=$v->name?>"><?=$count_comment?> Comments</a>
		</li>
		<li class="meta-button">
			<a href="<?php echo $v->link; ?>" target="_self">
				<?=READ_MORE?> <i class="fa fa-arrow-right"></i>
			</a>
		</li>
	</ul>
</div>
<?php
endforeach;
?>
<?=$pagination?>
</section>
