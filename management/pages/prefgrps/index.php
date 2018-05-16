<?php
/**
 * Bel-CMS [Content management system]
 * @version 0.0.3
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
<div class="box-body">
	<a class="btn btn-app" href="Prefgrps?management">
		<i class="fa fa-key"></i><?=GROUPS?>
	</a>
	<a class="btn btn-app" href="Prefgrps/add?management">
		<i class="fa fa-plus"></i><?=ADD?>
	</a>
</div>

<div class="box">
	<div class="box-body">
		<table class="table table-bordered table-hover datatable">
			<thead>
				<tr>
					<th>ID</th>
					<th><?=NAME?></th>
					<th><?=NB_GROUPS?></th>
					<th class="td-actions"> </th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ($groups as $k => $v):
					?>
					<tr>
						<td><?=$v->id_group?></td>
						<td><?=$v->name?></td>
						<td><?=$v->count?></td>
						<td class="td-actions">
							<a href="Prefgrps/edit/<?=$v->id?>?management" class="btn btn-success btntable">
								<i class="icon-pencil"></i>
							</a>
							<a href="Prefgrps/del/<?=$v->id?>?management" class="btn btn-danger btntable">
								<i class="icon-trash"></i>
							</a>
						</td>
					</tr>
					<?php
				endforeach;
				?>
			</tbody>
		</table>
	</div>
</div>
<?php
endif;
