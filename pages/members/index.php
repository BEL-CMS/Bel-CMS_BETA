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
<section id="bel_cms_members_index" class="padding-bottom-60">
	<h4><?=MEMBERS?></h4>

	<table id="bel_cms_members_table" class="table table-striped table-bordered">
		<thead>
			<tr>
				<th><?=USERNAME;?></th>
				<th><?=WEBSITE?></th>
				<th><?=LOCATION?></th>
				<th><?=LAST_VISIT?></th>
			</tr>
		</thead>
	</table>

</section>