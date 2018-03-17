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
		<h3 class="box-title"><?=PAGES?></h3>
	</div>
	<div class="box-body">
		<table class="table table-striped table-bordered">
			<tbody>
			<?php
			foreach ($formPages as $k => $v):
				$c = ($v->active == ACTIVATE) ? 'label bg-green' : 'label bg-red';
				?>
				<tr>
					<td><a href="prefaccess/pageaccess/<?=$v->id?>?management"><?=$v->name?></a></td>
					<td><span class="<?=$c?>"><?=$v->active?></span></td>
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
