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
							<h3><?=BLOG?></h3>
						</div>
						<!-- fin Titre full -->
						<!-- debut du contenue -->
						<div class="widget-content">
							<!-- debut des boutton action -->
							<div class="form-actions">
								<button class="btn"><i class="icon-home"></i> <?=HOME?></button>
								<button class="btn"><i class="icon-plus"></i> <?=ADD?></button>
								<button class="btn"><i class="icon-wrench"></i> <?=PARAMETER?></button>
							</div>
							<!-- fin des boutton action -->
							<table class="table table-striped table-bordered">
								<thead class="thead-inverse">
									<tr>
										<th>#</th>
										<th><?=NAME?></th>
										<th><?=DATE?></th>
										<th><?=USERNAME?></th>
										<th class="td-actions"> </th>
									</tr>
								</thead>
								<tbody>
									<?php
									foreach ($this->data as $k => $v):
										?>
										<tr>
											<td><?=$v->id?></td>
											<td><?=$v->name?></td>
											<td><?=$v->date_create?></td>
											<td><?=$v->author['username']?></td>
											<td class="td-actions">
												<a href="javascript:;" class="btn btn-small btn-success">
													<i class="btn-icon-only icon-ok"> </i>
												</a>
												<a href="javascript:;" class="btn btn-danger btn-small">
													<i class="btn-icon-only icon-remove"> </i>
												</a>
											</td>
										</tr>
										<?php
									endforeach;
									?>
								</tbody>
							</table>
						</div>
						<!-- fin Titre full -->
					</div>
				</div>
		  	</div>

		</div>
	</div>
</div>
<?php
endif;
