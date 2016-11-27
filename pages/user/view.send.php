<?php
/**
 * Bel-CMS [Content management system]
 * @version 0.0.1
 * @link http://www.bel-cms.be
 * @link http://www.stive.eu
 * @license http://opensource.org/licenses/GPL-3.0 copyleft
 * @copyright 2014 Bel-CMS
 * @author Stive - mail@stive.eu
 */
if (!defined('CHECK_INDEX')) {
	header($_SERVER['SERVER_PROTOCOL'] . ' 403 Direct access forbidden');
	exit(ERROR_INDEX);
}
?>
<section class="bel_cms_box_msg <?php echo $this->belCmsBoxMsgType; ?>">
	<div class="bel_cms_box_msg_title"><?php echo $this->belCmsBoxMsgTitle; ?></div>
	<div class="bel_cms_box_msg_content"><?php echo $this->belCmsBoxMsgContent; ?></div>
</section>
