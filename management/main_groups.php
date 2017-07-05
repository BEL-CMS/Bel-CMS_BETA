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
<div class="main">
	<div class="main-inner">
		<div class="container">
			<div class="row">

				<div class="span12">
					<div class="widget">
						<!-- debut Titre full -->
						<div class="widget-header">
							<i class="icon-th-large"></i>
							<h3><?=GROUPS?></h3>
						</div>
						<!-- fin Titre full -->
						<!-- debut des boutton action -->
						<div class="form-actions">
							<button class="btn" onclick="window.location.href='Access?management'"><i class="icon-file"></i> <?=PAGE?></button>
							<button class="btn active"><i class="icon-key"></i> <?=GROUPS?></button>
						</div>
					</div>
				</div>
				<!-- fin des boutton action -->
				<!-- debut du contenue -->
				<div class="span12">

					<div class="widget widget-table action-table">
						<div class="widget-header">
							<h3><?=GROUPS?></h3>
						</div>
						<div class="widget-content">
							<table class="table table-striped table-bordered">
								<thead class="thead-inverse">
									<tr>
										<th class="td-actions"><?=ID_GROUP?></th>
										<th><?=NAME?></th>
										<th class="td-actions"><?=OPTIONS?></th>
									</tr>
								</thead>
								<tbody>
								<?php
								foreach ($table as $k => $v):
									?>
									<tr>
										<td><?=$v->id_group?></td>
										<td><?=$v->name?></td>
										<td class="td-actions">
											<a href="Access/edit_groups/<?=$v->id?>?management" class="btn btn-small btn-success">
												<i class="btn-icon-only icon-edit"> </i>
											</a>
											<a href="#modal_<?=$v->id?>" role="button" data-toggle="modal" class="btn btn-danger btn-small">
												<i class="btn-icon-only icon-remove"> </i>
											</a>
											<div id="modal_<?=$v->id?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
													<h3 id="myModalLabel">Suppression du groups</h3>
												</div>
												<div class="modal-body">
													<p>Etes vous certain de supprimer le group : <?=$v->name?></p>
												</div>
												<div class="modal-footer">
													<button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
													<a href="Access/del_groups/<?=$v->id?>?management" class="btn btn-primary">Supprimer</a>
												</div>
											</div>
										</td>
									</tr>
								<?php
								endforeach
								?>
								</tbody>
							</table>
							<div class="form-actions">
								<a href="Access/add_group?management" class="btn btn-primary"><?=constant('ADD')?></a> 
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>
<?php
endif;