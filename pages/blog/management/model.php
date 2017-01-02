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

include ROOT_PAGES.'blog'.DS.'model.php';

class ModelManagementBlog extends ModelPagesBlog
{
	protected function SendNew ($data = false)
	{
		if ($data !== false) {
			// SECURE DATA
			$insert['rewrite_name'] = Common::MakeConstant($data['name']);
			$insert['name']         = Common::VarSecure($data['name'], ''); // autorise que du texte
			$insert['content']      = Common::VarSecure($data['content'], null); // autorise que les balises HTML
			$user                   = User::ReturnUser();
			$insert['author']       = $user->hash_key;
			$insert['tags']         = Common::VarSecure($data['tags'], ''); // autorise que du texte
			$insert['cat']          = ''; // à implanter
			// SQL INSERT
			$sql = New BDD();
			$sql->table('TABLE_PAGES_BLOG');
			$sql->sqlData($insert);
			$sql->insert();
			// SQL RETURN NB INSERT 
			if ($sql->rowCount == 1) {
				$return = array(
					'type' => 'success',
					'text' => NEW_BLOG_SUCCESS
				);
			} else {
				$return = array(
					'type' => 'alert',
					'text' => NEW_BLOG_ERROR
				);
			}
		} else {
			$return = array(
				'type' => 'alert',
				'text' => ERROR_NO_DATA
			);
		}

		return $return;
	}
	protected function SendEdit ($data = false)
	{
		if ($data !== false) {
			// SECURE DATA
			$edit['rewrite_name'] = Common::MakeConstant($data['name']);
			$edit['name']         = Common::VarSecure($data['name'], ''); // autorise que du texte
			$edit['content']      = Common::VarSecure($data['content'], null); // autorise que les balises HTML
			$edit['author']       = strlen($data['author']) == 32 ? $data['author'] : null;
			$edit['tags']         = Common::VarSecure($data['tags'], ''); // autorise que du texte
			$edit['cat']          = ''; // à implanter
			// SQL UPDATE
			$sql = New BDD();
			$sql->table('TABLE_PAGES_BLOG');
			$id = Common::SecureRequest($data['id']);
			$sql->where(array('name' => 'id', 'value' => $id));
			$sql->sqlData($edit);
			$sql->update();
			// SQL RETURN NB UPDATE 
			if ($sql->rowCount == 1) {
				$return = array(
					'type' => 'success',
					'text' => EDIT_BLOG_SUCCESS
				);
			} else {
				$return = array(
					'type' => 'alert',
					'text' => EDIT_BLOG_ERROR
				);
			}
		} else {
			$return = array(
				'type' => 'alert',
				'text' => ERROR_NO_DATA
			);
		}

		return $return;
	}
	protected function UpdateParameter ($data = false)
	{
		if ($data !== false) {
			// SECURE DATA
			unset($data['send']);
			$update['config'] = Common::transformOpt($data, true);
			// SQL UPDATE
			$sql = New BDD();
			$sql->table('TABLE_PAGES_CONFIG');
			$sql->where(array('name'=>'name','value' => 'blog'));
			$sql->sqlData($update);
			$sql->update();
			// SQL RETURN NB UPDATE 
			if ($sql->rowCount == 1) {
				$return = array(
					'type' => 'success',
					'text' => NEW_PARAMETER_SUCCESS
				);
			} else {
				$return = array(
					'type' => 'alert',
					'text' => NEW_PARAMETER_ERROR
				);
			}
		} else {
			$return = array(
				'type' => 'alert',
				'text' => ERROR_NO_DATA
			);
		}
		return $return;
	}
	protected function DelNew ($data = false)
	{
		if ($data !== false) {
			// SECURE DATA
			$delete = (int) $data;
			// SQL DELETE
			$sql = New BDD();
			$sql->table('TABLE_PAGES_BLOG');
			$sql->where(array('name'=>'id','value' => $delete));
			$sql->delete();
			// SQL RETURN NB DELETE 
			if ($sql->rowCount == 1) {
				$return = array(
					'type' => 'success',
					'text' => DEL_BLOG_SUCCESS
				);
			} else {
				$return = array(
					'type' => 'alert',
					'text' => DEL_BLOG_ERROR
				);
			}
		} else {
			$return = array(
				'type' => 'alert',
				'text' => ERROR_NO_DATA
			);
		}
		return $return;
	}
}
