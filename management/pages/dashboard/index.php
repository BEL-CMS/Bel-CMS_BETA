<?php
use GeoIp2\Database\Reader;
$nombre_de_lignes = 1;
$chartDateMonth = '';
while ($nombre_de_lignes <= date('t')) {

	$chartDateMonth.= '"'.$nombre_de_lignes++.'",';
}
$chartDateMonth = substr($chartDateMonth, 0, -1);
$chartNbDay = implode(',', $month);
?>
<div class="row">
	<div class="col-sm-6 col-lg-12">
		<div class="card" style="width: 100%">
			<div class="card-body">
				<div class="row">
					<div class="col-sm-5">
						<h4 class="card-title mb-0">Traffic</h4>
						<div class="small text-muted"><?= date('F').' '. date('Y')?></div>
					</div>
				</div>
				<div class="row">
					<div class="chart-wrapper" style="height:300px;margin-top:40px; width: 100%">
						<canvas id="myChart" class="chart" height="300"></canvas>
					</div>
					<script src="assets/plugins/chart/chart.min.js"></script>
					 <script type="text/javascript">
						var ctx = document.getElementById('myChart');
						var chart = new Chart(ctx, {
							// The type of chart we want to create
							type: 'line',

							// The data for our dataset
							data: {
								labels: [<?=$chartDateMonth?>],
								datasets: [{
									label: "Visite",
									backgroundColor: 'rgb(166,204,58)',
									borderColor: 'rgb(41,54,61)',
									data: [<?=$chartNbDay?>],
								}]
							},
							options: {
								responsive: true,
								label: false
							}
						});
					</script>
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-6 col-lg-12">
		<table class="table table-responsive-sm table-bordered">
			<thead class="thead-dark">
				<tr>
					<th>User | hash key</th>
					<th>Flag</th>
					<th class="text-center">Country</th>
					<th class="text-center">Browser</th>
					<th>Activity</th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ($conneted as $k => $v):
					$reader = new Reader('config/GeoLite2-City.mmdb');
					$v->visitor_ip = $v->visitor_ip == '127.0.0.1' ? '128.101.101.101' : $v->visitor_ip;
					$record = $reader->city("$v->visitor_ip");
				?>
				<tr>
					<td>
						<div><?=$v->visitor_user?></div>
					</td>
					<td>
						<img src="assets/imagery/flags/<?=$record->country->isoCode?>.png" alt="" style="height:18px;">
					</td>
					<td class="text-center">
						<?=$record->country->names['fr']?>
					</td>
					<td class="text-center">
						<div><?=$v->visitor_browser?></div>
					</td>
					<td>
						<div><?=$v->visitor_page?></div>
					</td>
				</tr>
				<?php
				endforeach;
				?>
			</tbody>
		</table>
	</div>
</div>
