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
					<div class="widget widget-table action-table">
						<div class="widget-header">
							<i class="icon-user"></i>
							<h3><?=SHOUTBOX?></h3>
						</div>
						<div class="widget-content">
							<table class="table table-striped table-bordered">
								<thead>
									<tr>
										<td style="text-align: center;">#</td>
										<td><?=NAME?></td>
										<td><?=DATE?></td>
										<td class="td-actions"><?=OPTIONS?></td>
									</tr>
								</thead>
								<tbody>
									<?php
									foreach ($this->data as $k => $v):
										$username = User::getNameAvatar($v->hash_key);
										?>
										<tr>
											<td style="text-align: center;"><?=$v->id?></td>
											<td><?=$username->username?></td>
											<td><?=$v->date_msg?></td>
											<td style="text-align: center;">
												<a href="shoutbox/edit/<?=$v->id?>?management" class="btn btn-small btn-info"><i class="icon-large icon-edit"></i></a>
												<a href="shoutbox/remove/<?=$v->id?>?management" class="btn btn-small btn-danger"><i class="icon-large icon-remove-sign"></i></a>
											</td>
										</tr>
										<?php
									endforeach;
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