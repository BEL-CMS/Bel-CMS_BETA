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
							<h3><?=PARAMETERS?></h3>
						</div>
						<div class="widget-content">
							<!-- debut des boutton action -->
							<div class="form-actions">
								<button class="btn" onclick="window.location.href='/Blog?management'"><i class="icon-home"></i> <?=HOME?></button>
								<button class="btn" onclick="window.location.href='Blog/New?management'"><i class="icon-plus"></i> <?=ADD?></button>
								<button class="btn active"><i class="icon-wrench"></i> <?=PARAMETER?></button>
							</div>
							<form action="Blog/send?management" method="post" class="form-horizontal">
								<fieldset>
									<div class="control-group">
										<label class="control-label" for="label_nb"><?=NB_BLOG?></label>
										<div class="controls">
											<input name="MAX_BLOG" type="number" class="span6" id="label_nb" value="<?=$_SESSION['pages']->blog['config']['MAX_BLOG']?>" min="1" max="16">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="label_nb_admin"><?=NB_BLOG_ADMIN?></label>
										<div class="controls">
											<input name="MAX_BLOG_ADMIN" type="number" class="span6" id="label_nb_admin" value="<?=$_SESSION['pages']->blog['config']['MAX_BLOG_ADMIN']?>" min="1" max="25">
										</div>
									</div>
								</fieldset>
								<div class="form-actions">
									<input type="hidden" name="send" value="parameter">
									<button type="submit" class="btn btn-primary"><?=SAVE?></button> 
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