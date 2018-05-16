<?php
/**
 * Bel-CMS [Content management system]
 * @version 0.3.0
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
<div class="box box-info">
	<div class="box-header with-border">
	  <h3 class="box-title"><?=WIDGETS?></h3>
	</div>

	<div class="box-body">
		<div class="table-responsive">
			<table class="table no-margin">
				<tbody>
				<?php
					foreach ($formWidgets as $k => $v):
						$c = ($v->activate == ACTIVATE) ? 'label bg-green' : 'label bg-red';
						?>
						<tr>
							<td><a href="PrefWidgets/parameter/<?=$v->id?>?management"><?=$v->name?></a></td>
							<td><span class="<?=$c?>"><?=$v->activate?></span></td>
						</tr>
					<?php
					endforeach
				?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php
endif;
