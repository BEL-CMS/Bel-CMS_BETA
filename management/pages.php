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
							<h3><?=PAGES?></h3>
						</div>
						<!-- fin Titre full -->
						<!-- debut des boutton action -->
						<div class="form-actions">
							<button class="btn active"><i class="icon-file"></i> <?=PAGE?></button>
							<button class="btn" onclick="window.location.href='Access/main_groups?management'"><i class="icon-key"></i> <?=GROUPS?></button>
						</div>
					</div>
				</div>
				<!-- fin des boutton action -->
				<!-- debut du contenue -->
				<div class="span6">
					<div class="widget widget-table action-table">
						<div class="widget-header">
							<h3><?=PAGES?></h3>
						</div>
						<div class="widget-content">
							<table class="table table-striped table-bordered">
								<thead class="thead-inverse">
									<tr>
										<th><?=NAME?></th>
										<th class="td-actions"><?=ACTIVE?></th>
									</tr>
								</thead>
								<tbody>
								<?php
								foreach ($formPages as $k => $v):
									?>
									<tr>
										<td><a href="Access/page_access/<?=$v->id?>?management"><?=$v->name?></a></td>
										<td><?=$v->active?></td>
									</tr>
								<?php
								endforeach
								?>
								</tbody>
							</table>
						</div>
					</div>
				</div>

				<div class="span6">
					<div class="widget widget-table action-table">
						<div class="widget-header">
							<h3><?=WIDGETS?></h3>
						</div>
						<div class="widget-content">
							<table class="table table-striped table-bordered">
								<thead class="thead-inverse">
									<tr>
										<th><?=NAME?></th>
										<th class="td-actions"><?=ACTIVE?></th>
									</tr>
								</thead>
								<tbody>
								<?php
								foreach ($formWidgets as $k => $v):
									?>
									<tr>
										<td><a href="Access/widgets_access/<?=$v->id?>?management"><?=$v->name?></a></td>
										<td><?=$v->activate?></td>
									</tr>
								<?php
								endforeach
								?>
								</tbody>
							</table>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>
<?php
endif;