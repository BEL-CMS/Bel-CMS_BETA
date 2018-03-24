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
if ($last !== null):
?>
<section id="bel_cms_widgets_lastconnected">
	<div class="panel-group">
		<div class="panel-heading"><?=$this->title?></div>
		<div class="panel">
			<div class="panel-body">
				<ul>
					<?php
					foreach ($last as $k => $v):
						?>
						<li>
							<img data-toggle="tooltip" title="<?=$v->username?>" src="<?=$v->avatar?>" alt="avatar_<?=$v->username?>">
							<span>
								<p><?=$v->username?></p>
								<p><?=Common::transformDate($v->last_visit, true, 'd M Y # H:i') ?></p>
							</span>
						</li>
					<?php
					endforeach;
					?>
				</ul>
			</div>
		</div>
	</div>
</section>
<?php
endif;
