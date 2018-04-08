<?php
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

</div>
