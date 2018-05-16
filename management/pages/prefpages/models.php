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

class ModelsPrefPages
{
	public function getFormPages ()
	{
		$sql = New BDD();
		$sql->table('TABLE_PAGES_CONFIG');
		$sql->orderby(array(array('name' => 'name', 'type' => 'DESC')));
		$sql->queryAll();
		foreach ($sql->data as $k => $v) {
			$v->name = defined(strtoupper($v->name)) ? constant(strtoupper($v->name)) : ucfirst($v->name);
			if ($v->active == 1) {
				$v->active = ACTIVATE;
			} else {
				$v->active = DISABLE;
			}
			$formPages[$k] = $v;
		} unset($sql);
		return $formPages;
	}

	public function getConfig ($id = false)
	{
		$sql = New BDD();
		$sql->table('TABLE_PAGES_CONFIG');
		$sql->where(array('name' => 'id', 'value' => (int) $id));
		$sql->queryOne();

		$sql->data->access_groups = explode('|', $sql->data->access_groups);
		$sql->data->access_admin  = explode('|', $sql->data->access_admin);

		$getGroups                  = Config::GetGroups();
		$getGroups[0]               = VISITORS;
		$return['getGroups']        = $getGroups;
		$return['checkAccessPage']  = $sql->data->access_groups;
		$return['checkAccessAdmin'] = $sql->data->access_admin;
		$return['checkActive']      = (bool) $sql->data->active;
		$return['pageId']           = $sql->data->id;
		$return['traduct']          = defined(strtoupper($sql->data->name)) ? constant(strtoupper($sql->data->name)) : ucfirst($sql->data->name);

		if (empty($sql->data->config)) {
			$return['config'] = false;
		} else {
			$return['config'] = Common::transformOpt($sql->data->config);
		}

		return $return;
	}

	public function sendPage($data = false)
	{
		if ($data) {
			if (isset($data['access_groups'])) {
				if (in_array(0, $data['access_groups'])) {
					unset($data['access_groups']); $data['access_groups'] = array(0 => 0);
				}
			}
			if (isset($data['access_admin'])) {
				if (!in_array(1, $data['access_admin'])) {
					array_push($data['access_admin'], 1);
				}
			}
			/* secure data */
			$data['access_groups']   = isset($data['access_groups']) ? $data['access_groups'] : array(0 => 0);
			$data['access_admin']    = isset($data['access_admin']) ? $data['access_admin'] : array(0 => 1);
			$secure['active']        = (int) $data['active'];
			$secure['access_groups'] = implode('|', $data['access_groups']);
			$secure['access_admin']  = implode('|', $data['access_admin']);
			$secure['config']        = Common::transformOpt($data['config'], true);
			/* update sql */
			$sql = New BDD();
			$sql->table('TABLE_PAGES_CONFIG');
			$sql->where(array('name'=>'id','value'=> (int) $data['id']));
			$sql->sqlData($secure);
			$sql->update();
			/* return msg */
			$save = array(
				'type' => 'success',
				'text' => SAVE_BDD_SUCCESS
			);
		} else {
			$save = array(
				'type' => 'error',
				'text' => SAVE_BDD_ERROR
			);
		}
		return $save;
	}
}
