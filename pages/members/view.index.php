<?php
if (!defined('CHECK_INDEX')) {
	header($_SERVER['SERVER_PROTOCOL'] . ' 403 Direct access forbidden');
	exit(ERROR_INDEX);
}
?>
<section id="bel_cms_members_index" class="padding-bottom-60">
	<h4><?=MEMBERS?></h4>
	<div class="bel_cms_members_index_table">
		<table class="table table-striped">
			<thead>
				<tr>
					<th class="hidden-xs">#</th>
					<th><?=USERNAME?></th>
					<th class="hidden-xs"><?=BIRTHDAY?></th>
					<th class="hidden-xs"><?=LOCATION?></th>
					<th class="hidden-xs"><?=GENDER?></th>
					<th><?=WEBSITE?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				if (empty($this->data)) {
					?>
					<tr>
						<td colspan="6">Aucun utilisateur</td>
					</tr>
					<?php
				} else {
					foreach ($this->data as $k => $v):
						if (!empty($v->profils)) {
							if ($v->profils->gender == 'male') {
								$gender = MALE;
							} else if ($v->profils->gender == 'female') {
								$gender = FEMALE;
							} else {
								$gender = UNISEXUAL;
							}	
							$birthday = Common::TransformDate($v->profils->birthday);
							$country  = $v->profils->country;
							$websites = $v->profils->websites;
						} else {
							$gender   = '.';
							$birthday = '.';
							$country  = '.';
							$websites = '.';
						}
						?>
						<tr>
							<td class="hidden-xs"></td>
							<td><a href="Members/View/<?=$v->username?>"><?=$v->username?></td>
							<td class="hidden-xs"><?=$birthday?></td>
							<td class="hidden-xs"><?=$country?></td>
							<td class="hidden-xs"><?=$gender?></td>
							<td><?=$websites?></td>
						</tr>
					<?php
					endforeach;
				}
				?>
			</tbody>
		</table>
	</div>
	<?=$this->pagination?>
</section>