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

foreach ($this->data as $k => $v):
?>
<div class="blog-post">
	<h2 class="blog-post-title"><?php echo $v->name; ?></h2>
	<p class="blog-post-meta"><?php echo Common::transformDate($v->date_create, true, 'd-M-Y # H:i'); ?> by <a href="#"><?php echo $v->author['username']; ?></a></p>
	<?php echo $v->content; ?>
</div>
<?php
endforeach;
?>
