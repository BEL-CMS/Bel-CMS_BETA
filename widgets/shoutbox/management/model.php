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

include ROOT_PAGES.'shoutbox'.DS.'model.php';

class ModelManagementShoutobx extends ModelPagesShoutbox
{
	protected function sendEditMsg ($id, $msg)
	{
		$where = array(
			'name'  => 'id',
			'value' => (int) $id
		);

		$msg = Common::VarSecure($msg);

		$sql = New BDD();
		$sql->table('TABLE_SHOUTBOX');
		$sql->sqldata(array('msg' => $msg));
		$sql->where($where);
		$sql->update();

		$return['text']	= 'Message éditer avec succès';
		$return['type']	= 'success';

		return $return;
	}
}