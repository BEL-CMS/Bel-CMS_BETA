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

if (isset($_SESSION['LOGIN_MANAGEMENT']) && $_SESSION['LOGIN_MANAGEMENT'] === true):
?>
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title"><?=WIDGETS?></h3>
	</div>
	<div class="box-body">
		<table class="table table-striped table-bordered">
			<tbody>
			<?php
				foreach ($formWidgets as $k => $v):
					?>
					<tr>
						<td><a href="prefgrps/parameter/<?=$v->id?>?management"><?=$v->name?></a></td>
						<td><?=$v->activate?></td>
					</tr>
				<?php
				endforeach
			?>
			</tbody>
		</table>

	</div>
</div>
<?php
endif;
