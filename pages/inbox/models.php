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

class ModelsInbox
{
	# TABLE_INBOX
	# TABLE_INBOX_MSG

	#####################################
	# Get Users min letter >= 2
	#####################################
	public function getUsers($user = null)
	{
		$return = array();
		# check if user >= 2 letter
		if (strlen($user) >= 2) {
			$sql = New BDD();
			$sql->table('TABLE_USERS');
			$sql->fields(array('username'));

			$where = array(
				'name'  => 'username',
				'value' => $user
			);

			$sql->whereLike($where);
			$sql->queryAll();
			$result = $sql->data;

			foreach ($result as $k => $v) {
				if ($v->username != $_SESSION['user']->username) {
					$return[] = $v->username;
				}
			}

		}

		return $return;
	}
	#####################################
	# Get User if exist
	#####################################
	private function isExistUser ($username) {

		$return = false;
		# check if username not empty
		if ($username !== null) {
			# check if username exist and return hash_key
			$sql = New BDD();
			$sql->table('TABLE_USERS');
			$sql->fields(array('hash_key'));
			$where = array(
				'name' => 'username',
				'value' => $username
			);
			$sql->where($where);
			$sql->queryOne();
			$count = $sql->rowCount;

			if ($count == 1) {
				$return = $sql->data;
				$return = $return->hash_key;
			}

		}

		return $return;
	}
	#####################################
	# Send new message
	#####################################
	public function sendNewMessage ($username = null, $message = null)
	{

		$return = false;
		# check if user exist
		$isExistUser = self::isExistUser($username);
		# check if hash_key 32 letter
		if ($isExistUser && strlen($isExistUser) == 32) {
			# check user != yourself
			if ($isExistUser == $_SESSION['user']->hash_key) {
				$return['text']	= ERROR_BE_SAME;
				$return['type']	= 'danger';
				return $return;
			}
			# insert main BDD
			# data
			$dataInbox = array(
				'username' => $_SESSION['user']->hash_key,
				'usersend' => $isExistUser,
			);
			$sqlInbox = New BDD();
			$sqlInbox->table('TABLE_INBOX');
			$sqlInbox->sqldata($dataInbox);
			$sqlInbox->insert();
			# get id insert
			$idInsert = $sqlInbox->lastId;
			# insert msg BDD
			# data
			$dataMsg = array(
				'id_msg' => (int) $idInsert,
				'username' => $_SESSION['user']->hash_key,
				'message' => Common::VarSecure($message, 'html'),
				'status' => (int) 0
			);
			$sqlMsg = New BDD();
			$sqlMsg->table('TABLE_INBOX_MSG');
			$sqlMsg->sqldata($dataMsg);
			$sqlMsg->insert();
			# check insert BDD INBOX and INBOX MSG
			if ($sqlInbox->rowCount == 1 && $sqlMsg->rowCount == 1) {
				$return['text']	= MESSAGE_SUCCESS;
				$return['type']	= 'success';
			} else {
				$return['text']	= ERROR_INSERT_BDD;
				$return['type']	= 'danger';
			}

		} else {
			$return['text']	= ERROR_NO_USER;
			$return['type']	= 'danger';
		}
		# return
		return $return;
	}
	#####################################
	# get message yourself
	#####################################
	public function getMessages()
	{
		$return = array();

		$sql = New BDD();
		$sql->table('TABLE_INBOX');
		if (strlen($_SESSION['user']->hash_key) != 32) {
			return array();
		}
		$where = 'WHERE username = "'.$_SESSION['user']->hash_key.'" OR usersend = "'.$_SESSION['user']->hash_key.'"';
		$sql->where($where);
		$sql->queryAll();

		foreach ($sql->data as $k => $v) {
			$v->username = AutoUser::getNameAvatar($v->username)->username;
			$v->usersend = AutoUser::getNameAvatar($v->usersend)->username;
			$sqlLastMessage = New BDD();
			$sqlLastMessage->table('TABLE_INBOX_MSG');
			$sqlLastMessage->where(
				array(
					'name'  => 'id_msg',
					'value' => (int) $v->id
				)
			);
			$sqlLastMessage->orderby(
				array(
					array(
						'name' => 'date_msg',
						'type' => 'DESC'
					)
				)
			);
			$sqlLastMessage->limit(1);
			$sqlLastMessage->queryOne();
			$return[$k] = $v;
			$sqlLastMessage->data->message = Common::VarSecure($sqlLastMessage->data->message, null);
			$sqlLastMessage->data->username = AutoUser::getNameAvatar($sqlLastMessage->data->username)->username;
			$return[$k]->lastmessage = $sqlLastMessage->data;
		}

		return $return;
	}
	#####################################
	# get all message for id
	#####################################
	public function showMessage($id = false)
	{
		$return = array();
		$id     = Common::SecureRequest($id);
		# check hash_key valid
		if (strlen($_SESSION['user']->hash_key) != 32) {
			return array();
		}
		# get id user and usersend
		$get = New BDD();
		$get->table('TABLE_INBOX');
		$get->fields(array('username', 'usersend'));
		$get->where(
			array(
				'name'  => 'id',
				'value' => $id
			)
		);
		$get->queryOne();
		$getId = array();
		$getId[] = $get->data->username;
		$getId[] = $get->data->usersend;
		# check user in username and usersend
		if (!in_array($_SESSION['user']->hash_key, $getId)) {
			$return['text']	= ERROR_HASH_KEY_MSG;
			$return['type']	= 'danger';
			return $return;
			# @todo include BDD : intrusion unauthorized
		}
		# get message for id
		$sql = New BDD();
		$sql->table('TABLE_INBOX_MSG');
		$sql->where(
			array(
				'name'  => 'id_msg',
				'value' => $id
			)
		);
		$sql->orderby(
			array(
				array(
					'name' => 'date_msg',
					'type' => 'ASC'
				)
			)
		);
		$sql->queryAll();
		foreach ($sql->data as $k => $v) {
			if ($k <= 0) {
				$user = AutoUser::getNameAvatar($get->data->usersend);
				$sql->data[$k]->origin = $user;
			}
		}
		self::changeStatus($id);
		$return = $sql->data;

		return $return;
	}
	#####################################
	# send reponse message
	#####################################
	public function sendReponse($id = null, $message = null)
	{
		$return = array();
		$id     = Common::SecureRequest($id);
		# check message empty
		if (empty($message)) {
			$return['text']	= ERROR_EMPTY_MSG;
			$return['type']	= 'danger';
			return $return;
		}
		# insert msg BDD
		# data
		$data = array(
			'id_msg' => (int) $id,
			'username' => $_SESSION['user']->hash_key,
			'message' => Common::VarSecure($message, 'html'),
			'status' => (int) 0
		);
		$sql = New BDD();
		$sql->table('TABLE_INBOX_MSG');
		$sql->sqldata($data);
		$sql->insert();
		# check insert INBOX MSG
		if ($sql->rowCount == 1) {
			$return['text']	= MESSAGE_SUCCESS;
			$return['type']	= 'success';
		} else {
			$return['text']	= ERROR_INSERT_BDD;
			$return['type']	= 'danger';
		}

		$return['id'] = $id;

		return $return;
	}
	#####################################
	# change status for message
	#####################################
	private function changeStatus($id)
	{
		$id = Common::SecureRequest($id);
		$sql = New BDD();
		$sql->table('TABLE_INBOX_MSG');
		$where[] = array(
			'name'  => 'id_msg',
			'value' => $id
		);
		$where[] = array(
			'name'  => 'username',
			'value' => $_SESSION['user']->hash_key
		);
		$sql->where($where);
		$sql->sqlData(array('status' => 1));
		$sql->update();
	}
	#####################################
	# Get count message
	#####################################
	public function countUnreadMessage ()
	{
		$return = (int) 0;

		$sql = New BDD();
		$sql->table('TABLE_INBOX_MSG');
		$where[] = array(
			'name'  => 'username',
			'value' => $_SESSION['user']->hash_key
		);
		$where[] = array(
			'name'  => 'status',
			'value' => 0
		);
		$sql->where($where);
		$sql->count();

		$return = (int) $sql->data;

		return $return;
	}

}
