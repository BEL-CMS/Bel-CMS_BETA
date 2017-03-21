<div id="widgets_infos_users">
	<span class="widgets_infos_users_title"><?=USERS?></span>
	<ul>
		<?php
		foreach ($this->data['users'] as $k => $v):
		?>
		<li>
			<span class="col-md-7"><?=$k?></span>
			<span class="col-md-5"><strong><?=$v?></strong></span>
		</li>
		<?php
		endforeach;
		?>
	</ul>
	<span class=widgets_infos_users_title><?=CONNECTED?></span>
	<ul>
		<li>
			<span class="col-md-7">Hier</span>
			<span class="col-md-5"><strong><?=Visitors::GetVisitorsLasterday()?></strong></span>
		</li>
		<li>
			<span class="col-md-7">Aujourd'hui</span>
			<span class="col-md-5"><strong><?=Visitors::GetVisitorsDay()?></strong></span>
		</li>
		<li>
			<span class="col-md-7">Maintennant</span>
			<span class="col-md-5"><strong><?=$this->data['current']?></strong></span>
		</li>
	</ul>
</div>

