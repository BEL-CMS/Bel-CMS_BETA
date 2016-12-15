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
<section id="bel_cms_links_main">
	<table class="table table-bordered">
		<thead>
			<tr>
				<th>#</th>
				<th><?=LINK?></th>
				<th><?=NB_CLICK?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			$i = 0;
			foreach ($this->data as $k => $v):
				?>
				<tr>
					<td><?=$i++?></td>
					<td><a class="alertAjaxLink" href="Links/linkOut/<?=$v->id?>?ajax"><?=$v->link?></a></td>
					<td><?=$v->countck?></td>
				</tr>
				<?php
			endforeach;
			?>
		</tbody>
	</table>
</section>