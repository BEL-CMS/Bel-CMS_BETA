<?php
if (!defined('CHECK_INDEX')) {
	header($_SERVER['SERVER_PROTOCOL'] . ' 403 Direct access forbidden');
	exit(ERROR_INDEX);
}
?>
<section class="padding-bottom-60">
	<div class="">
		<div class="headline">
			<h4 class="no-padding-top"><?php echo NEWTHREAD; ?></h4>
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
								<textarea class="text-forum" name="info_text"></textarea>
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
