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
$comment = new Comment('view', 'blog','readmore', $blog->id);
?>
<section id="bel_cms_blog_readmore">
	<div class="blog-post">
		<h2 class="blog-post-title"><?=$blog->name?></h2>
		<p class="blog-post-meta"><?=Common::transformDate($blog->date_create, 'FULL', 'SHORT')?> by <a href="#"><?=$blog->author->username?></a></p>
		<?=$blog->content?>
	</div>
</section>
<?=$comment->html?>
