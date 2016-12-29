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

class ModelPagesForum
{
	protected function GetForum($title = null)
	{
		$this->sql = New BDD();
		$this->sql->table('TABLE_FORUM');

		$tmp_where[] = array(
			'name'  => 'activate',
			'value' => 1
		);

		if (!empty($title)) {
			if (ctype_digit($title)) {
				$tmp_where[] = array(
					'name'  => 'id',
					'value' => $title
				);
			} else {
				$tmp_where[] = array(
					'name'  => 'title',
					'value' => $title
				);
			}

			$this->sql->where($tmp_where);
			$this->sql->queryOne();
		} else {
			$this->sql->where($tmp_where);
			$this->sql->orderby(array(array('name' => 'orderby', 'type' => 'ASC')));
			$this->sql->queryAll();
		}
		$return = $this->sql->data;
		foreach ($return as $k => $v) {
			$access = false;
			$groups = explode('|', $v->groups);
			foreach ($groups as $v_access) {
				if ($v_access == 0) {
					$access = true;
					break;
				} else {
					if (User::ReturnUser() !== false) {
						if (in_array($v_access, $_SESSION['user']->groups)) {
							$access = true;
							break;
						}
					}
				}
			}

			if ($access === false) {
				unset($return[$k]);
			}
		}

		unset($this->sql);
		return $return;
	}
	protected function GetThreads($id = null)
	{
		$this->sql = New BDD();
		$this->sql->table('TABLE_FORUM_THREADS');
		if (ctype_digit($id)) {
			$tmp_where[] = array(
				'name'  => 'id_forum',
				'value' => $title
			);
			$this->sql->where($tmp_where);
			$this->sql->orderby(array(array('name' => 'orderby', 'type' => 'ASC')));
			$this->sql->queryAll();
			if (!empty($this->sql->data)) {
				foreach ($this->sql->data as $k => $v) {
					$options = explode('|', $v->options);
					$this->sql->data[$k]->options = $options;
				}
			}
			$return = $this->sql->data;
		} else if (is_array($id)) {
			foreach ($id as $k) {
				if (ctype_digit($k)) {
					$tmp_where[] = array(
						'name'  => 'id_forum',
						'value' => $k
					);
				}
			}
			$this->sql->where($tmp_where);
			$this->sql->orderby(array(array('name' => 'orderby', 'type' => 'ASC')));
			$this->sql->queryAll();
			if (!empty($this->sql->data)) {
				foreach ($this->sql->data as $k => $v) {
					if (!empty($v->options)) {
						$options = explode('|', $v->options);
						foreach ($options as $k_opt => $v_opt) {
							$tmp_opt = explode('=', $v_opt);
							$options[$tmp_opt[0]] = $tmp_opt[1] == 1 ? true : false;
							unset($options[$k_opt], $tmp_opt);
						}
						$this->sql->data[$k]->options = $options;
					}
					$this->sql->data[$k]->count_post = (int) self::CountForum('TABLE_FORUM_POST', array('name'=>'id_threads','value' => $v->id));
					$this->sql->data[$k]->lastThreads = self::LastThreads($v->id);
				}
			}
			$return = $this->sql->data;
		} else {
			$return = null;
		}
		unset($this->sql);
		return $return;
	}
	protected function GetPost($id = false, $id_supp = null)
	{
		$return = false;
		$this->sql = New BDD();
		$this->sql->table('TABLE_FORUM_POST');

		if (!empty($id)) {
			if (ctype_digit($id)) {
				$id = intval($id);
				$thread_name = $id;
			} else {
				$id = Common::SecureRequest($id);
				$this->getId = New BDD();
				$this->getId->table('TABLE_FORUM_THREADS');
				$this->getId->where(array('name' => 'id', 'value' => $id_supp));
				$this->getId->queryOne();
				if (!empty($this->getId->data)) {
					$id = $this->getId->data;
					$id = $id->id;
					$thread_name = $this->getId->data->title;
				}
			}

			$accessTest = New BDD();
			$accessTest->table('TABLE_FORUM');
			$accessTest->where(array('name' => 'id', 'value' => $this->getId->data->id_forum));
			$accessTest->queryOne();

				$access = false;
				$groups = explode('|', $accessTest->data->groups);
				foreach ($groups as $v_access) {
					if ($v_access == 0) {
						$access = true;
						break;
					} else {
						if (in_array($v_access, $_SESSION['USER']['groups'])) {
							$access = true;
							break;
						}
					}
				}

			if ($access === true) {

				$this->sql->where(array('name' => 'id_threads', 'value' => $id));
				$this->sql->orderby(array(array('name' => 'date_post', 'type' => 'DESC')));
				$this->sql->queryAll();
				$tmp_where = array();
				if (!empty($this->sql->data)) {
					//foreach ($this->sql->data as $k => $v) {
						//$users[] = $v->author;
					//}
					//User::getNameUsers($users);
					foreach ($this->sql->data as $k => $v) {
						$authorId = $v->author;
						$this->sql->data[$k]->author      = User::getNameAvatar($authorId)->username;
						$this->sql->data[$k]->thread_name = $thread_name;
						$this->sql->data[$k]->lastpost    = self::LastPost($v->id);
						$this->sql->data[$k]->options     = Common::transformOpt($v->options);
					}
				}
				$return = $this->sql->data;
			} else {
				$return = NO_ACCESS_GROUP_PAGE;
			}
		}

		return $return;
	}
	protected function GetPosts($id = false, $id_supp = null)
	{

		$return = false;
		if ($id && $id_supp) {
			$id_supp = (int) $id_supp;
			// Récupère le 1er message du post //
			$this->sql = New BDD();
			$this->sql->table('TABLE_FORUM_POST');
			$this->sql->where(array('name' => 'id', 'value' => $id_supp));
			$this->sql->limit(1);
			$this->sql->queryAll();
			$firstPost = $this->sql->data;
			unset($this->sql);
			// Récupère les reponses du post //
			$this->sql = New BDD();
			$this->sql->table('TABLE_FORUM_POSTS');
			$this->sql->where(array('name' => 'id_post', 'value' => $id_supp));
			$this->sql->orderby(array(array('name' => 'date_post', 'type' => 'ASC')));
			$this->sql->queryAll();
			$posts = $this->sql->data;
			// Assemble les deux tableaux
			$return = array_merge($firstPost, $posts);
			foreach ($return as $k => $v) {
				$authorId = $v->author;
				$author = User::getNameAvatar($authorId);
				// Fait corrépondre leurs ID avec leur username
				$return[$k]->author = $author->username;
				// Fait corrépondre leurs ID avec leur avatar
				$return[$k]->avatar = $author->avatar;
				// Fait corrépondre leurs ID avec leur date d'inscription
				$return[$k]->registration = Common::TransformDate($author->date_registration);
				// Récupère les options et les transformer en Booleen
				// Les like sont transoformer en (int)
				$options = explode('|', $v->options);
				foreach ($options as $k_opt => $v_opt) {
					$tmp_opt = explode('=', $v_opt);
					$options[$tmp_opt[0]] = $tmp_opt[1] == 1 ? true : false;
					if (isset($options['like'])) {
						$options['like'] = $options['like'] == false ? (int) 0 : $options['like'];
					}
					unset($options[$k_opt], $tmp_opt);
				}
				$return[$k]->options = $options;
			}
		}
		return $return;
	}
	public function CountForum ($table, $where)
	{
		$this->count = New BDD();
		$this->count->table($table);
		$this->count->where($where);
		$this->count->count();
		$return = $this->count->data;
		return $return;
	}
	protected function SubmitPost($data)
	{
		if (User::ReturnUser() === false) {
			$return['msg']  = ERROR_LOGIN;
			$return['type'] = 'blue';
			return $return;
		} else {
			$user = User::ReturnUser();
		}

		if (!isset($_SESSION['REPLYPOST'])) {
			$return['msg']  = ERROR_ID;
			$return['type'] = 'red';
			return $return;
		}

		if ($_SESSION['REPLYPOST'] != $data['id']) {
			$return['msg']  = ERROR_ID;
			$return['type'] = 'red';
			return $return;
		} else {
			unset($_SESSION['REPLYPOST']);
		}

		$upload = Common::Upload('file', 'forum');
		if ($upload == UPLOAD_FILE_SUCCESS) {
			$insert['attachment'] = 'uploads/forum/'.Common::FormatName($_FILES['file']['name']);
			$upload = '<br>'.$upload;
		} else if ($upload == UPLOAD_NONE) {
			$insert['attachment'] = '';
			$upload = '';
		} else {
			$insert['attachment'] = '';
			$upload = '';
		}

		$insert['content'] = Common::VarSecure($data['info_text']);
		$insert['id_post'] = (int) $data['id'];
		$insert['author']  = $user->hash_key;
		$insert['options'] = 'like=0|report=0';

		$BDD = New BDD();
		$BDD->table('TABLE_FORUM_POSTS');
		$BDD->sqlData($insert);
		$BDD->insert();

		if ($BDD->rowCount == 1) {
			self::addPlusPost($BDD->sqlData['id_post']);
			$return['msg']  = 'Enregistrement de la réponse en cours...'.$upload;
			$return['type'] = 'green';
		} else {
			$return['msg']  = ERROR_BDD;
			$return['type'] = 'red';
		}

		return $return;
	}
	protected function SubmitThread($data)
	{
		if (User::ReturnUser() === false) {
			$return['msg']  = ERROR_LOGIN;
			$return['type'] = 'blue';
			return $return;
		} else {
			$user = User::ReturnUser();
		}

		if ($_SESSION['NEWTHREADS'] != $data['id']) {
			$return['msg']  = ERROR_ID;
			$return['type'] = 'red';
			return $return;
		} else {
			unset($_SESSION['NEWTHREADS']);
		}

		$insert['content']    = Common::VarSecure($data['info_text']);
		$insert['author']     = $user->hash_key;
		$insert['options']    = 'lock=0|like=0|report=0|pin=0|view=0|post=0';
		$insert['id_threads'] = (int) $data['id'];
		$insert['title']      = strip_tags($data['title']);

		$BDD = New BDD();
		$BDD->table('TABLE_FORUM_POST');
		$BDD->sqlData($insert);
		$BDD->insert();

		if ($BDD->rowCount == 1) {
			$return['msg']  = 'Enregistrement du nouveau post en cours...';
			$return['type'] = 'green';
		} else {
			$return['msg']  = ERROR_BDD;
			$return['type'] = 'red';
		}

		return $return;
	}
	protected function LastThreads($id = false)
	{
		$return = array();

		$get = New BDD();
		$get->table('TABLE_FORUM_POST');
		$where = array(
			'name'  => 'id_threads',
			'value' => (int) $id
		);
		$get->where($where);
		$get->orderby(array(array('name' => 'date_post', 'type' => 'DESC')));
		$get->queryOne();
		$return = $get->data;

		if (empty($return)) {
			$return = (object) array(
				'title'     => NO_POST,
				'author'    => UNKNOWN,
				'date_post' => UNKNOWN,
				'avatar'    => 'assets/imagery/default_avatar.jpg'
			);
		} else {
			$authorId = $return->author;
			$return->date_post = Common::transformDate($return->date_post, true, 'd M Y');
			$author = User::getNameAvatar($authorId);
			$return->author    = $author->username;
			$return->avatar    = $author->avatar;
		}

		return $return;

	}
	protected function LastPost($id = false)
	{
		$return = array();

		$get = New BDD();
		$get->table('TABLE_FORUM_POSTS');
		$where = array(
			'name'  => 'id_post',
			'value' => (int) $id
		);
		$get->where($where);
		$get->orderby(array(array('name' => 'date_post', 'type' => 'DESC')));
		$get->queryOne();
		$return = $get->data;

		if (empty($return)) {
			$return = (object) array(
				'title'     => 'Aucun Post',
				'author'    => '-',
				'date_post' => UNKNOWN,
				'avatar'    => 'assets/imagery/default_avatar.jpg'
			);
		} else {
			$authorId = $return->author;
			$return->date_post = Common::transformDate($return->date_post, true, 'd M Y');
			$author = User::getNameAvatar($authorId);
			// Fait corrépondre leurs ID avec leur username
			$return->author = $author->username;
			// Fait corrépondre leurs ID avec leur avatar
			$return->avatar = $author->avatar;
		}

		return $return;

	}

	protected function addView ($id = false) {
		if ($id && is_int($id)) {
			$get = New BDD();
			$get->table('TABLE_FORUM_POST');
			$where = array(
				'name'  => 'id',
				'value' => (int) $id
			);
			$get->where($where);
			$get->queryOne();
			$data = $get->data;

			$options = Common::transformOpt($data->options);
			$options['view'] = (int) $options['view'] + 1;
			$options = Common::transformOpt($options, true);

			$update = New BDD();
			$update->table('TABLE_FORUM_POST');
			$update->where($where);
			$update->sqlData(array('options' => $options));
			$update->update();

		}
	}
	protected function addPlusPost ($id = false) {
		if ($id && is_int($id)) {
			$get = New BDD();
			$get->table('TABLE_FORUM_POST');
			$where = array(
				'name'  => 'id',
				'value' => (int) $id
			);
			$get->where($where);
			$get->queryOne();
			$data = $get->data;

			$options = Common::transformOpt($data->options);
			$options['post'] = (int) $options['post'] + 1;
			$options = Common::transformOpt($options, true);

			$update = New BDD();
			$update->table('TABLE_FORUM_POST');
			$update->where($where);
			$update->sqlData(array('options' => $options));
			$update->update();

		}
	}
}
