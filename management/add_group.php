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
						<div class="widget-header">
							<i class="icon-user"></i>
							<h3><?=ADD.' '.GROUPS?></h3>
						</div>

						<div class="widget-content">
							<form action="Access/send_add_group?management" method="post" class="form-horizontal">
								<div class="control-group">
									<label class="control-label" for="label_name"><?=NAME;?></label>
									<div class="controls">
										<fieldset>
											<label class="checkbox inline">
												<input type="text" name="name" value="" placeholder="Nom du groupe">
											</label>
										</fieldset>
									</div>
								</div>
								<div class="form-actions">
									<button type="submit" class="btn btn-primary"><?=ADD?></button> 
								</div>
							</form>
						</div>

					</div>
				</div>

			</div>
		</div>
	</div>
</div>
<?php
endif;