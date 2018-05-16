<?php
/**
 * Bel-CMS [Content management system]
 * @version 0.3.0
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

class ModelsShoutbox
{
	public function getMsg()
	{
		$return = null;

		if (isset($_SESSION['pages']->shoutbox->config['MAX_MSG'])) {
			$nbpp = (int) $_SESSION['pages']->shoutbox->config['MAX_MSG'];
		} else {
			$nbpp = (int) 10;
		}

		$this->sql = New BDD();
		$this->sql->table('TABLE_SHOUTBOX');
		$this->sql->orderby(array(array('name' => 'id', 'type' => 'DESC')));
		$this->sql->limit($nbpp);
		$this->sql->queryAll();
		if (!empty($this->sql->data)) {
			$return = $this->sql->data;
		} else {
			$return = array();
		}
		return $return;
	}
}
