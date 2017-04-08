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
?>
<div class="row">
	<div class="col-md-5 col-lg-6">
		<div href="<?=$v->link?>" class="bel_cms_blog_main_text">
			<h2><?=$v->name?></h2>
			<a href="<?=$v->link?>" class="bel_cms_blog_main_count">0</a>
			<div class="both"></div>
			<div><?=Common::transformDate($v->date_create, true, 'd-M-Y # H:i') ?> by <a href="Members/<?=$v->author->username?>"><?=$v->author->username?></a></div>
			<a href="<?php echo $v->link; ?>" class="nk-btn nk-btn-rounded nk-btn-color-dark-3 nk-btn-hover-color-main-1">Read More</a>
		</div>
	</div>
	<div class="col-md-7 col-lg-6">
		<div class="bel_cms_blog_blog_text">
			<?=$v->content?>
		</div>
	</div>
</div>
<?php
endforeach;
?>
<?=$this->pagination?>
</section>