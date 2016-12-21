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
foreach ($this->data as $k => $v):
?>
<article class="padding-bottom-30">
	<h3><?=$v->name?></h3>
	<div class="infos_meta">
		<span class="bel_cms_blog_main_tags"></span>
		<span class="bel_cms_blog_main_date"><?=$v->date_create?></span>
		<span class="bel_cms_blog_main_comment">0</span>
		<span class="bel_cms_blog_main_view">0</span>
	</div>
<?=$v->content?>
</article>
<?php
endforeach;
?>
<?=$this->pagination?>
</section>