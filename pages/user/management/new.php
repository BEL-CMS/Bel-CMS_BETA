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
							<h3><?=USER.' : '.ADD?></h3>
						</div>
						<!-- fin Titre full -->
						<!-- debut du contenue -->
						<div class="widget-content">
							<!-- debut des boutton action -->
							<div class="form-actions">
								<button class="btn" onclick="window.location.href='/User?management'"><i class="icon-home"></i> <?=HOME?></button>
								<button class="btn active"><i class="icon-plus"></i> <?=ADD?></button>
								<button class="btn" onclick="window.location.href='User/parameter?management'"><i class="icon-wrench"></i> <?=PARAMETER?></button>
							</div>
							<form action="User/send?management" method="post" class="form-horizontal">
								<fieldset>
									<div class="control-group">
										<label class="control-label" for="label_name"><?=USERNAME?> :</label>
										<div class="controls">
											<input name="username" type="text" class="span6" id="label_name" placeholder="Nom d'utilisateur" required="required">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="label_pass"><?=PASSWORD?> :</label>
										<div class="controls">
											<input name="password" type="password" class="span6" placeholder="******" id="label_pass" required="required">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="label_mail"><?=MAIL?> :</label>
										<div class="controls">
											<input name="email" type="email" class="span6" id="label_mail" placeholder="<?=MAIL.' '.constant('PRIVATE')?>" required="required">
										</div>
									</div>
								<fieldset>
								<div class="control-group" style="margin-top: 15px;">
									<input type="hidden" name="send" value="new">
									<input class="btn btn-primary" type="submit" value="<?=ADD?>">
								</div>
							</form>
						</div>
						<!-- fin du contenue -->
					</div>
				</div>
		  	</div>

		</div>
	</div>
</div>
<?php
endif;