<div class="panel panel-default">
	<div class="panel-heading"><?=CONNECTED?></div>
	<div class="panel-body">
		<div id="bel_cms_widgets_connected" class="widget">
			<ul>
				<li>
					<span class="col-md-7">Hier</span>
					<span class="col-md-5"><strong><?=Visitors::getVisitorYesterday()->count?></strong></span>
				</li>
				<li>
					<span class="col-md-7">Aujourd'hui</span>
					<span class="col-md-5"><strong><?=Visitors::getVisitorDay()->count?></strong></span>
				</li>
				<li>
					<span class="col-md-7">Maintennant</span>
					<span class="col-md-5"><strong><?=Visitors::getVisitorConnected()->count?></strong></span>
				</li>
				<li>
					<ul id="getVisitorConnected">
						<?php
						$i = 0;
						foreach (Visitors::getVisitorConnected()->data as $k => $v):
							$test = autoUser::getNameAvatar($v->visitor_user);
							$visitor = $test === false ? VISITOR : $test->username;
							?>
							<li>
								<span class="col-md-7"><?=Common::truncate($visitor, 20)?></span>
								<span class="col-md-5" style="text-align: right;"><?=$v->visitor_page?></span>
							</li>
							<?php
							if ($i++ == 5) {
								break;
							}
						endforeach;
						?>
					</ul>
				</li>
			</ul>

		</div>
	</div>
</div>
