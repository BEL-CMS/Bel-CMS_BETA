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

?>
<section id="bel_cms_forum_newthread" class="padding-bottom-60">
	<div class="">
		<div class="headline">
			<h4 class="no-padding-top"><?php echo NEW_THREAD; ?></h4>
		</div>

		<form action="Forum/Send" method="post" enctype="multipart/form-data">
			<div class="panel panel-default margin-bottom-30">
				<div class="panel-body">
					<div class="form-group row">
						<label for="thread" class="col-md-2"><?php echo TITLE_POST; ?></label>
						<div class="col-md-10">
							<input type="text" name="title" class="form-control" id="thread" placeholder="<?php echo ADD_A_TITLE; ?>">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-md-2"><?php echo MESSAGE; ?></label>
						<div class="col-md-10">
							<div class="forum-post no-margin no-shadow">
								<textarea class="bel_cms_textarea_simple" name="info_text"></textarea>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="text-center">
				<input type="hidden" name="send" value="NewThread">
				<input type="hidden" name="id" value="<?php echo $_SESSION['NEWTHREADS'] ?>">
				<input type="submit" class="btn btn-primary btn-lg btn-rounded btn-shadow" value="<?php echo SUBMIT_THREAD; ?>">
			</div>

		</form>

	</div>
</section>
