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

class ModelPagesTeam
{
	protected function GetGroups ()
	{
		$return = array();

		$sql = New BDD();
		$sql->table('TABLE_GROUPS');
		$sql->orderby(array(array('name' => 'name', 'type' => 'ASC')));
		$sql->queryAll();
		$return = $sql->data;
		unset($sql);

		return $return;
	}

	protected function GetUsers ($where)
	{
		$return = array();

		$sql = New BDD();
		$sql->table('TABLE_USERS');
		$where = "WHERE `groups` LIKE '%".$where."%'";
		$sql->where($where);
		$sql->orderby(array(array('name' => 'username', 'type' => 'ASC')));
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
}
