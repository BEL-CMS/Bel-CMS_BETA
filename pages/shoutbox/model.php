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

class ModelPagesShoutbox
{
	protected function getMsg($id = false)
	{
		$return = null;
		$sql = New BDD();
		$sql->table('TABLE_SHOUTBOX');
		if (is_numeric($id)) {
			$sql->where(array('name' => 'id', 'value' => $id));
			$sql->queryOne();
		} else {
			$sql->orderby(array(array('name' => 'id', 'type' => 'DESC')));
			$sql->queryAll();
		}
		if (!empty($sql->data)) {
			$return = $sql->data;
		}
		return $return;
	}
	protected function getLast ()
	{
		$id = (int) $_GET['id'];
		$return = null;
		$sql = New BDD();
		$sql->table('TABLE_SHOUTBOX');
		$sql->orderby(array(array('name' => 'id', 'type' => 'DESC')));
		$where = array('name' => 'id', 'value' => $id, 'op' => '>');
		$sql->where($where);
		$sql->queryAll();
		if (!empty($sql->data)) {
			$return = $sql->data;
		} else {
			$return = array();
		}
		return $return;
	}
	protected function insertMsg()
	{
		if (strlen($_SESSION['user']->hash_key) != 32) {
			$return['text'] = 'Erreur HashKey';
			$return['type'] = 'danger';
			return $return;
		} else {
			$data['hash_key'] = $_SESSION['user']->hash_key;
		}

		if (empty($_SESSION['user']->avatar) OR !is_file($_SESSION['user']->avatar)) {
			$data['avatar'] = DEFAULT_AVATAR;
		} else {
			$data['avatar'] = $_SESSION['user']->avatar;
		}

		if (empty($_POST['text'])) {
			$return['text'] = 'Erreur Message Vide';
			$return['type'] = 'danger';
			return $return;
		} else {
			$data['msg'] = Common::VarSecure($_REQUEST['text'], '<a><b><p><strong>');
		}

		$this->sql = New BDD();
		$this->sql->table('TABLE_SHOUTBOX');
		$this->sql->sqldata($data);
		$this->sql->insert();

		$return['text']	= 'Message envoyer avec succès';
		$return['type']	= 'success';

		return $return;

	}
}
