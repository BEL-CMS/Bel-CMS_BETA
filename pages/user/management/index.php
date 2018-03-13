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
<div class="card">
	<div class="card-header"><i class="fa fa-align-justify"></i> Pages</div>
	<div class="card-body">
		<div class="card-body">
		<button class="btn btn-secondary active"><i class="icon-home"></i> Accueil</button>
		<button class="btn btn-outline-secondary" onclick="window.location.href='User/NewUser?management'"><i class="icon-user-follow"></i> Ajouter</button>
		<button class="btn btn-outline-secondary" onclick="window.location.href='User/Parameter?management'"><i class="icon-wrench"></i> Paramètres</button>
		</div>
	</div>
</div>
<div class="card">
	<div class="card-header"><i class="fa fa-align-justify"></i> <?=USERS?></div>
	<div class="card-body">
		<table class="table table-responsive-sm table-bordered">
			<thead class="thead-dark">
				<tr>
					<th>#</th>
					<th><?=NAME?></th>
					<th><?=MAIL?></th>
					<th><?=LAST_VISIT?></th>
					<th class="td-actions"> </th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ($data as $k => $v):
					?>
					<tr>
						<td><?=$v->id?></td>
						<td><?=$v->username?></td>
						<td><?=$v->email?></td>
						<td><?=$v->last_visit?></td>
						<td class="td-actions">
							<a href="User/Edit/<?=$v->hash_key?>?management" class="btn btn-success btn-sm">
								<i class="icon-pencil"> </i>
							</a>
							<a href="User/Del/<?=$v->hash_key?>?management" class="btn btn-danger btn-sm">
								<i class="icon-trash "> </i>
							</a>
						</td>
					</tr>
					<?php
				endforeach;
				?>
			</tbody>
		</table>
		<?=$pagination?>
	</div>
</div>
<?php
endif;
