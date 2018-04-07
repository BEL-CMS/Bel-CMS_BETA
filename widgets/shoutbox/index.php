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
<div id="bel_cms_widgets_shoutbox" class="card bel_cms_widgets">
	<div class="card-header"><?=$this->title ?></div>
	<div class="card-body">
		<div id="bel_cms_widgets_shoutbox" class="widget">
			<div class="widget-content">
				<ul id="bel_cms_widgets_shoutbox_msg">
					<?php
					$i = 1;
					foreach ($shoutbox as $k => $v):
						if (count($v->msg) != 0):
						$i++;
						if ($i & 1) {
							$left_right =  'by_myself right';
						} else {
							$left_right =  'from_user left';
						}
						$username = AutoUser::getNameAvatar($v->hash_key);
						$msg = ' ' . $v->msg;
						$msg = preg_replace("#([\t\r\n ])(www|ftp)\.(([\w\-]+\.)*[\w]+(:[0-9]+)?(/[^ \"\n\r\t<]*)?)#i", '\1<a href="http://\2.\3" onclick="window.open(this.href); return false;">\2.\3</a>', $msg);
						$msg = preg_replace("#([\n ])([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>", $msg);
						$msg = preg_replace_callback('`((https?|ftp)://\S+)`', 'cesure_href',$msg);
						?>
						<li class="<?=$left_right?>" id="id_<?=$v->id?>">
							<a data-toggle="tooltip" title="<?=$username->username?>" href="Members/View/<?=$username->username?>" class="avatar">
								<img src="<?=$username->avatar?>" style="max-width: 50px; max-height: 50px;">
							</a>
							<div class="message_wrap"> <span class="arrow"></span>
								<div class="info"> <a data-toggle="tooltip" title="<?=$username->username?>" href="Members/View/<?=$username->username?>" class="name"><?=$username->username?></a> <span class="time"><?=$v->date_msg?></span>
								</div>
								<div class="text"><?=$msg?></div>
							</div>
						</li>
						<?php
						endif;
					endforeach;
					?>
				</ul>
			</div>
		</div>
	</div>
	<?php
	if (isset($_SESSION['user']->hash_key) && strlen($_SESSION['user']->hash_key) == 32):
	?>
	<div class="card-footer text-muted">
		<form id="bel_cms_widgets_shoutbox_form" action="shoutbox/send&ajax" method="post">
			<div class="form-group">
				<input type="text" class="form-control" name="text" placeholder="Votre Message...">
			</div>
			<div class="form-group">
				<button class="btn btn-primary" type="submit"><?=SEND?></button>
			</div>
		</form>
	</div>
	<?php
	endif;
	?>
</div>
