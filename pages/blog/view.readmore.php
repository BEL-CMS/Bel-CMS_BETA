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
<div class="blog-post">
	<h2 class="blog-post-title"><?php echo $this->data->name; ?></h2>
	<p class="blog-post-meta"><?php echo Common::transformDate($this->data->date_create, true, 'd-M-Y # H:i'); ?> by <a href="#"><?php echo $this->data->author; ?></a></p>
	<?php echo $this->data->content; ?>
</div>
