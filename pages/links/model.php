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

class ModelPagesLinks
{
	protected function GetLink($id = false)
	{
		$return = '';

		$sql = New BDD();
		$sql->table('TABLE_LINK_OUT');

		if (is_int($id)) {
			$where = array(
				'name'  => 'id',
				'value' => (int) $id
			);
			$sql->where($where);
			$sql->queryOne();
		} elseif ($id === false) {
			$sql->queryAll();
		}
		if (!empty($sql->data)) {
			$return = $sql->data;
		}
		return $return;
	}
	protected function addClick ($id = false) {
		if ($id !== false && is_int($id)) {
			$get = New BDD();
			$get->table('TABLE_LINK_OUT');
			$where = array(
				'name'  => 'id',
				'value' => (int) $id
			);
			$get->where($where);
			$get->queryOne();
			if (!empty($get->data)) {
				$data = $get->data;
				$plus = (int) $data->countck + 1;
				$update = New BDD();
				$update->table('TABLE_LINK_OUT');
				$update->where($where);
				$update->sqlData(array('countck' => $plus));
				$update->update();
			}
		}
	}
}
