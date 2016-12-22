<?php
/**
 * Bel-CMS [Content management system]
 * @version 0.0.1
 * @link http://www.bel-cms.be
 * @link http://www.stive.eu
 * @license http://opensource.org/licenses/GPL-3.0 copyleft
 * @copyright 2014 Bel-CMS
 * @author Stive - mail@stive.eu
 */

class ModelPagesMembers
{
	protected function GetUsers ($where)
	{
		if (isset($_SESSION['pages']->blog->config['MAX_MEMBERS'])) {
			$nbpp = (int) $_SESSION['pages']->blog->config['MAX_MEMBERS'];
		} else {
			$nbpp = (int) 10;
		}
		$page = (GET_PAGES * $nbpp) - $nbpp;

		$return = array();

		$sql = New BDD();
		$sql->table('TABLE_USERS');
		$where = "WHERE `groups` LIKE '%".$where."%'";
		$sql->where($where);
		$sql->orderby(array(array('name' => 'username', 'type' => 'ASC')));
		$sql->limit(array(0 => $page, 1 => $nbpp), true);
		$sql->queryAll();
		$return = $sql->data;
		unset($sql);

		foreach ($return as $k => $v) {
			$sql = New BDD();
			$sql->table('TABLE_USERS_PROFILS');
			$where = 	array(
							'name'  => 'hash_key',
							'value' => $v->hash_key
						);
			$sql->where($where);
			$sql->queryOne();
			$return[$k]->profils = $sql->data;
			unset($sql);
		}

		return $return;
	}
	protected function GetLastPost ($hash_key)
	{
		$return = array();

		$sql = New BDD();
		$sql->table('TABLE_FORUM_POST');
		$sql->where(array('name' => 'author', 'value' => $hash_key));
		$sql->orderby(array(array('name' => 'id', 'type' => 'DESC')));
		$sql->limit(3);
		$sql->queryAll();
		$return = $sql->data;
		unset($sql);

		return $return;

	}
}
