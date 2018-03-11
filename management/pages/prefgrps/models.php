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

class ModelsGrpsAccess
{
	public function getFormWidgets ()
	{
		$sql = New BDD();
		$sql->table('TABLE_WIDGETS');
		$sql->orderby(array(array('name' => 'name', 'type' => 'DESC')));
		$sql->queryAll();
		foreach ($sql->data as $k => $v) {
			$v->name = defined(strtoupper($v->name)) ? constant(strtoupper($v->name)) : ucfirst($v->name);
			if ($v->activate == 1) {
				$v->activate = ACTIVATE;
			} else {
				$v->activate = DISABLE;
			}
			$formWidgets[$k] = $v;
		} unset($sql);
		return $formWidgets;
	}

	public function getParameters ($id = false)
	{
		$return = array();

		if ($id) {
			$id = (int) $id;
			$sql = New BDD();
			$sql->table('TABLE_WIDGETS');
			$sql->where(array('name' => 'id', 'value' => $id));
			$sql->queryOne();
			$return = $sql->data;
		}

		return $return;
	}

	function sendParameters ($send = false)
	{
		if (isset($send['activate'])) {
			$data['activate'] = (int) $send['activate'];
		} else {
			$data['activate'] = 0;
		}
		if (isset($send['groups_access'])) {
			if (in_array(0, $send['groups_access'])) {
				$data['groups_access'] = 0;
			} else {
				$data['groups_access'] = implode('|', $send['groups_access']);
			}
		} else {
			$data['groups_access'] = 0;
		}
		$data['title'] = Common::VarSecure($send['title'], '');
		if (isset($send['groups_admin'])) {
			$data['groups_admin'] = implode('|', $send['groups_admin']);
		} else {
			$data['groups_admin'] = 1;
		}
		$data['pos']      = $send['pos'];
		$data['orderby']  = (int) $send['orderby'];
		if (isset($send['pages'])) {
			$data['pages'] = implode('|', $send['pages']);
		} else {
			$data['pages'] = '';
		}
		$sql = New BDD();
		$sql->table('TABLE_WIDGETS');
		$sql->where(array('name'=>'name', 'value' => $send['id']));
		$sql->sqlData($data);
		$sql->update();

		if ($sql->rowCount == 1) {
			$return = array(
				'type' => 'success',
				'text' => NEW_PARAMETER_SUCCESS
			);
			Config::getConfigPages(true);
		} else {
			$return = array(
				'type' => 'alert',
				'text' => NEW_PARAMETER_ERROR
			);
		}

		return $return;
	}
}
