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
							<h3><?=BLOG.' : '.ADD?></h3>
						</div>
						<!-- fin Titre full -->
						<!-- debut du contenue -->
						<div class="widget-content">
							<!-- debut des boutton action -->
							<div class="form-actions">
								<button class="btn" onclick="window.location.href='/Blog?management'"><i class="icon-home"></i> <?=HOME?></button>
								<button class="btn active"><i class="icon-plus"></i> <?=ADD?></button>
								<button class="btn" onclick="window.location.href='Blog/parameter?management'"><i class="icon-wrench"></i> <?=PARAMETER?></button>
							</div>
							<form action="Blog/send?management" method="post" class="form-horizontal">
								<fieldset>
									<div class="control-group">
										<label class="control-label" for="label_name"><?=NAME?> :</label>
										<div class="controls">
											<input name="name" type="text" class="span6" id="label_name" placeholder="Nom de la page" required="required">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="label_tags"><?=TAGS?> :</label>
										<div class="controls">
											<input name="tags" type="text" class="span6" id="label_tags" placeholder="Tags: séparer par des ,">
										</div>
									</div>
									<textarea class="bel_cms_textarea_simple" name="content" placeholder="Texte..."></textarea>
								<fieldset>
								<div class="control-group" style="margin-top: 15px;">
									<input type="hidden" name="send" value="blog">
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
// rewrite_name 	name 	date_create 	author 	content 	tags 	cat 