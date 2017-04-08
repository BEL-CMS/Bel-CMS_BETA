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
?>
<div class="row">
	<div class="col-sm-6">

		<div class="panel panel-default">
			<div class="panel-heading"><h4>Bienvenue</h4></div>
			<div class="panel-body">
				<p>Bienvenue sur l'installation de BEL-CMS 0.0.1</p>
				<p>Nous vous remercions d'avoir choisis notre CMS et nous espérons qu'il va vous plaire</p>
				<hr>
				<hr>
			</div>
		</div>

	</div>

	<div class="col-sm-6">
		<div class="panel panel-default">
			<div class="panel-heading"><h4>Compatibilité avec votre hébergement</h4></div>
			<div class="panel-body">
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Composant</th>
							<th>Check</th>
						</tr>
					</thead>
					<tbody>
						<?php $php_class = checkPhp() === false ? 'class="danger"' : ''; ?>
						<?php $php_ico   = checkPhp() === false ? 'glyphicon glyphicon-remove' : 'glyphicon glyphicon-ok'; ?>
						<tr <?=$php_class?>>
							<td>PHP version ≥ 5.4.17</td>
							<td><span class="<?=$php_ico?>"></span></td>
						</tr>
						<?php $sqli_class = checkMysqli() === false ? 'class="danger"' : ''; ?>
						<?php $sqli_ico   = checkMysqli() === false ? 'glyphicon glyphicon-remove' : 'glyphicon glyphicon-ok'; ?>
						<tr <?=$sqli_class?>>
							<td>Extension MySQL</td>
							<td><span class="<?=$sqli_ico?>"></span></td>
						</tr>
						<?php $rewrite_class = checkRewrite() === false ? 'class="danger"' : ''; ?>
						<?php $rewrite_ico   = checkRewrite() === false ? 'glyphicon glyphicon-remove' : 'glyphicon glyphicon-ok'; ?>
						<tr <?=$rewrite_class?>>
							<td>Mod Rewrite</td>
							<td><span class="<?=$rewrite_ico?>"></span></td>
						</tr>
						<?php $pdo_class = checkPDO() === false ? 'class="danger"' : ''; ?>
						<?php $pdo_ico   = checkPDO() === false ? 'glyphicon glyphicon-remove' : 'glyphicon glyphicon-ok'; ?>
						<tr <?=$pdo_class?>>
							<td>PDO Driver</td>
							<td><span class="<?=$pdo_ico?>"></span></td>
						</tr>
						<?php $pdo_class = checkWriteConfig() === false ? 'class="danger"' : ''; ?>
						<?php $pdo_ico   = checkWriteConfig() === false ? 'glyphicon glyphicon-remove' : 'glyphicon glyphicon-ok'; ?>
						<tr <?=$pdo_class?>>
							<td>Write config/config.inc.php</td>
							<td><span class="<?=$pdo_ico?>"></span></td>
						</tr>
					</tbody>
				</table>
			<hr>
			</div>
		</div>
	</div>

<div class="row">
	<div class="col-sm-12">
		<?php
		if (checkPhp() && checkPhp() && checkRewrite() && checkPDO()) {
			echo '<p><a class="btn btn-primary btn-lg" href="?page=sql" role="button">Installer</a></p>';
		} else {
			echo '<div class="alert alert-danger" role="alert">Votre Hébérgeur n\'est pas compatible avec le C.M.S</div>';
		}
		?>
	</div>
</div>

	<div class="clearfix"></div>
</div>