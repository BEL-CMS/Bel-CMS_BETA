<?php
/**
 * Bel-CMS [Content management system]
 * @version 0.0.3
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

class Forum extends Pages
{
	var $models = array('ModelsForum');
	private $_error = false;

	#####################################
	# Start Class
	#####################################
	function __construct()
	{
		parent::__construct();
		if (parent::accessPage(strtolower(get_class($this))) === false) {
			$this->error(ERROR, 'Désolé, vous n’avez pas accès à cette page', 'danger');
			$this->_error = true;
		}
		if ($_SESSION['pages']->forum->active == 0) {
			$this->error(INFO, 'Forum désactiver', 'info');
			$this->_error = true;
		}
	}

	function index ()
	{
		if ($this->_error === false) {
			$name['forum'] = $this->ModelsForum->GetForum();
			if (empty($name['forum'])) {
				$this->error(ERROR, 'Aucun Forum de disponible', 'info');
				return;
			} else {
				foreach ($name['forum'] as $k => $v) {
					$tmp_threads[] = $v->id;
				}
				$name['threads'] = $this->ModelsForum->GetThreads($tmp_threads);

				foreach ($name['forum'] as $k => $v) {
					foreach ($name['threads'] as $k_t => $v_t) {
						if ($v_t->id_forum == $v->id) {
							$name['forum'][$k]->threads[] = $v_t;
						}
					}
				}
				$this->set($name);
			}
			$this->render('index');
		}
	}

	public function threads ($name, $id = null)
	{
		if ($this->_error === false) {
			$d = array();
			$id = (int) $id;
			$d['id'] = $id;
			$d['threads'] = $this->ModelsForum->GetPost($name, $id);
			if (NO_ACCESS_GROUP_PAGE == $name) {
				$this->error(ERROR, NO_ACCESS_GROUP_PAGE, 'danger');
			} else {
				$this->set($d);
				$this->render('threads');
			}
		}
	}

	public function post ($name, $id)
	{
		if ($this->_error === false) {

			if (empty($name)) {
				$this->error('Forum', 'Page manquante...');
				$this->redirect('Forum', 3);
				return;
			}
			$d = array();
			$id = (int) $id;
			$_SESSION['REPLYPOST']   = $id;
			$_SESSION['FORUM']       = uniqid('forum_');
			$_SESSION['FORUM_CHECK'] = $_SESSION['FORUM'];
			$this->ModelsForum->addView($id);
			$d['post'] = $this->ModelsForum->GetPosts($name, $id);
			if (count($d['post']) == 0) {
				$this->error('Forum', 'Page manquante...', 'danger');
				return;
			} else {
				$this->set($d);
				$this->render('post');
			}
		}

	}

	private function accessLock ()
	{
		$groupUser = $_SESSION['user']->groups;

		if (in_array('1', $groupUser)) {
			return true;
		}

		$access    = false;
		$groupsAccess = explode('|', $_SESSION['pages']->forum->admin);
		foreach ($groupsAccess as $k => $v) {
			if (in_array($v, $groupUser)) {
				$access = true;
				break;
			}
		}
		return $access;
	}

	public function lockpost ($id)
	{
		if ($this->_error === false) {
			if (self::accessLock()) {
				$return = $this->ModelsForum->lock($id);
				$this->error ('Forum', $return['msg'], $return['type']);
			} else {
				$this->error ('Forum', NO_CLOSE_POST, 'danger');
			}
			$this->redirect(true, 2);
		}
	}

	public function unlockpost ($id)
	{
		if ($this->_error === false) {
			if (self::accessLock()) {
				$return = $this->ModelsForum->unlock($id);
				$this->error ('Forum', $return['msg'], $return['type']);
			} else {
				$this->error ('Forum', NO_ACCESS_POST, 'danger');
			}
			$this->redirect(true, 2);
		}
	}

	public function delpost ($id)
	{
		if ($this->_error === false) {
			if (self::accessLock()) {
				$return = $this->ModelsForum->delpost($id);
				$this->error ('Forum', $return['msg'], $return['type']);
			} else {
				$this->error ('Forum', NO_ACCESS_POST, 'danger');
			}
			$this->redirect('Forum', 2);
		}
	}

	public function NewThread ($name)
	{
		if ($this->_error === false) {
			$_SESSION['NEWTHREADS'] = $name;
			$this->render('newthread');
		}
	}

	public function send ($name,$id)
	{
		if ($this->_error === false) {
			if ($_REQUEST['send'] == 'SubmitReply') {
				self::SubmitReply($this->data);
			} else if ($_REQUEST['send'] == 'NewThread') {
				self::NewPostThread($this->data);
			}
		}
	}

	private function NewPostThread ($data)
	{
		if ($this->_error === false) {
			$insert = $this->ModelsForum->SubmitThread($data['id'], $data);
			$this->error ('Forum', $insert['msg'], $insert['type']);
			$this->redirect(true, 2);
		}
	}

	private function SubmitReply ($data)
	{
		if ($this->_error === false) {

			$referer = (!empty($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : 'Forum';
			$insert  = $this->ModelsForum->SubmitPost($data);
			$this->error ('Forum : Réponse', $insert['msg'], $insert['type']);
			$this->redirect(true, 2);
		}

	}
}
