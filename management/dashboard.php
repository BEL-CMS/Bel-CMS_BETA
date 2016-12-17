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
		<div class="span6">
		  <div class="widget widget-nopad">
			<div class="widget-header"> <i class="icon-list-alt"></i>
			  <h3>Stats</h3>
			</div>

			<div class="widget-content">
			  <div class="widget big-stats-container">
				<div class="widget-content">
				  <div id="big_stats" class="cf">
					<div class="stat"> <i class="icon-user"></i> <span class="value">0</span> </div>
					<div class="stat"> <i class="icon-thumbs-up-alt"></i> <span class="value">0</span> </div>
					<div class="stat"> <i class="icon-twitter-sign"></i> <span class="value">0</span> </div>
					<div class="stat"> <i class="icon-bullhorn"></i> <span class="value">0</span> </div>
				  </div>
				</div>
			  </div>
			</div>
		  </div>

		  <div class="widget widget-table action-table">
			<div class="widget-header"> <i class="icon-th-list"></i>
			  <h3>Managing your Pages CMS</h3>
			</div>

			<div class="widget-content">
			  <table class="table table-striped table-bordered">
				<thead>
				  <tr>
					<th> Page </th>
					<th class="td-actions"> </th>
				  </tr>
				</thead>
				<tbody>
				<?php
				if (count(get_object_vars($this->ExistsPages())) == 0):
				?>
				<tr>
					<td colspan="2">Aucune page d'administration disponible.</td>
				</tr>
				<?php
				else:
				foreach ($this->ExistsPages() as $name => $access):
					$traduct = defined(strtoupper($name)) ? constant(strtoupper($name)) : $name;
					if ($access === true) {
						$p = '<a href="'.$name.'?Management" class="btn btn-small btn-success"><i class="btn-icon-only icon-ok"> </i></a>';
					} else {
						$p = '<a href="javascript:;" class="btn btn-danger btn-small"><i class="btn-icon-only icon-remove"> </i></a>';
					}
					if ($name != 'dashboard'):
					?>
					<tr>
						<td> <?=$traduct?> </td>
						<td class="td-actions"> <?=$p?></td>
					</tr>
					<?php
					endif;
				endforeach;
				endif;
				?>
				</tbody>
			  </table>
			</div>
		  </div>

		  <div class="widget widget-table action-table">
			<div class="widget-header"> <i class="icon-th-list"></i>
			  <h3>Managing your Widgets CMS</h3>
			</div>

			<div class="widget-content">
			  <table class="table table-striped table-bordered">
				<thead>
				  <tr>
					<th> Widgets </th>
					<th class="td-actions"> </th>
				  </tr>
				</thead>
				<tbody>
				<?php
				if (count(get_object_vars($this->ExistsWidgets())) == 0):
				?>
				<tr>
					<td colspan="2">Aucun widgets d'administration disponible.</td>
				</tr>
				<?php
				else:
				foreach ($this->ExistsWidgets() as $name => $access):
					$traduct = defined(strtoupper($name)) ? constant(strtoupper($name)) : $name;
					if ($access === true) {
						$p = '<a href="'.$name.'?Management" class="btn btn-small btn-success"><i class="btn-icon-only icon-ok"> </i></a>';
					} else {
						$p = '<a href="javascript:;" class="btn btn-danger btn-small"><i class="btn-icon-only icon-remove"> </i></a>';
					}
					if ($name != 'dashboard'):
					?>
					<tr>
						<td> <?=$traduct?> </td>
						<td class="td-actions"> <?=$p?></td>
					</tr>
					<?php
					endif;
				endforeach;
				endif;
				?>
				</tbody>
			  </table>
			</div>
		  </div>

		</div>

		<div class="span6">
		  <div class="widget">
			<div class="widget-header"> <i class="icon-bookmark"></i>
			  <h3>Speed Links</h3>
			</div>

			<div class="widget-content">
				<div class="shortcuts">
					<a href="Parameter?management" class="shortcut"><i class="shortcut-icon icon-cogs"></i><span class="shortcut-label">Parameter</span></a>
					<a href="javascript:;" class="shortcut"><i class="shortcut-icon icon-list-alt"></i><span class="shortcut-label">Widgets</span></a>
					<a href="javascript:;" class="shortcut"><i class="shortcut-icon  icon-filter"></i><span class="shortcut-label">Reports</span></a>
					<a href="javascript:;" class="shortcut"> <i class="shortcut-icon icon-comment"></i><span class="shortcut-label">Comments</span></a>
					<a href="javascript:;" class="shortcut"><i class="shortcut-icon icon-user"></i><span class="shortcut-label">Users</span></a>
					<a href="javascript:;" class="shortcut"><i class="shortcut-icon icon-download-alt"></i><span class="shortcut-label">Downloads</span></a>
					<a href="javascript:;" class="shortcut"><i class="shortcut-icon icon-picture"></i> <span class="shortcut-label">Photos</span></a>
					<a href="javascript:;" class="shortcut"> <i class="shortcut-icon icon-globe"></i><span class="shortcut-label">Links</span></a>
				</div>

			</div>

		  </div>

		  <div class="widget widget-nopad">
			<div class="widget-header"> <i class="icon-list-alt"></i>
			  <h3>News en direct de BEL-CMS</h3>
			</div>
			<div class="widget-content">
			  <ul class="news-items">
				<li>
				  <div class="news-item-date"> <span class="news-item-day">25</span> <span class="news-item-month">Sept</span> </div>
				  <div class="news-item-detail">
				  	<a href="#" class="news-item-title" target="_blank">
				  		Version Alpha 0.0.2
				  	</a>
					<p class="news-item-preview">Managing - News</p>
					<p>Mise en place du managing (Administration des pages) en cours</p>
					<p>Version Bêta en retard suite a mon hospitalisation, le plus gros du C.M.S est fait, reste juste à faire les pages et widgets ce qui est le plus rapide une fois le cœur du C.M.S est fait.</p>
				  </div>
				</li>
				<li>
				  <div class="news-item-date"> <span class="news-item-day">21</span> <span class="news-item-month">Juin</span> </div>
				  <div class="news-item-detail">
				  	<a href="#" class="news-item-title" target="_blank">
				  		Version Alpha 0.0.1
				  	</a>
					<p class="news-item-preview">Remplacement chargement</p>
					<p>Retrait du chargement des pages a chaque modification et remplacer par du jQuery.</p>
				  </div>
				</li>
				<li>
				  <div class="news-item-date"> <span class="news-item-day">17</span> <span class="news-item-month">Juin</span> </div>
				  <div class="news-item-detail">
				  	<a href="#" class="news-item-title" target="_blank">
				  		Version Alpha 0.0.1
				  	</a>
					<p class="news-item-preview">La version alpha du C.M.S est fini</p>
					<p>Test des differents bogues sur la page utilisateur en cours : inscription, changements avatar, teste double e-mail, etc...</p>
				  </div>
				</li>
			  </ul>
			</div>

		  </div>

		</div>

	  </div>

	</div>

  </div>

</div>

<?php
endif;
