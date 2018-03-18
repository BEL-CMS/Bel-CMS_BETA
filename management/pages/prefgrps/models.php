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

class ModelsPrefGrps
{
	public function GetGroups ($id = false)
	{
		$return = array();

		$sql = New BDD();
		$sql->table('TABLE_GROUPS');
		$sql->fields(array('id', 'name', 'id_group'));

		if ($id) {
			if (!is_numeric($id)) {
				return array(
					'type' => 'alert',
					'text' => ERROR_NO_ID_VALID
				);
			} else {
				$sql->where(array('name' => 'id', 'value' => $id));
				$sql->queryOne();
			}
		} else {
			$sql->queryAll();
		}

		$return = $sql->data;

		return $return;
	}

	public function GetCountGrps ()
	{
		$return = array();
		$nb     = array();

		foreach (self::GetGroups() as $k => $v) {
			$nb[$v->id_group] = (int) 0;
		}

		$sql = New BDD();
		$sql->table('TABLE_USERS');
		$sql->fields(array('groups'));
		$sql->queryAll();

		foreach ($sql->data as $k => $v) {
			$return[$k] = explode('|', $v->groups);
		}

		foreach ($return as $a => $b) {
			foreach ($b as $v => $d) {
				if (isset($nb[$d])) {
					$nb[$d]++;
				}
			}
		}

		return $nb;
	}

	public  function DelGroup ($id = false)
	{
		if ($id) {
			if (!is_numeric($id)) {
				return array(
					'type' => 'alert',
					'text' => ERROR_NO_ID_VALID
				);
			}
			// SECURE DATA
			$id = common::SecureRequest($id);
			if ($id == 1 or $id == 2) {
				return array(
					'type' => 'alert',
					'text' => ERROR_NO_ID_DEL
				);
			}
			// SQL DELETE
			$sql = New BDD();
			$sql->table('TABLE_GROUPS');
			$sql->where(array('name' => 'id', 'value' => $id));
			$sql->delete();
			// SQL RETURN NB DELETE
			if ($sql->rowCount == 1) {
				config::GetGroups();
				$return = array(
					'type' => 'success',
					'text' => DEL_GROUP_SUCCESS
				);
			} else {
				$return = array(
					'type' => 'alert',
					'text' => DEL_GROUP_ERROR
				);
			}
		} else {
			$return = array(
				'type' => 'alert',
				'text' => ERROR_NO_ID
			);
		}
		return $return;
	}

}
