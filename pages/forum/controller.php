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

class Forum extends Pages
{
	var $models = array('ModelsForum');

	#####################################
	# Start Class
	#####################################
	function __construct()
	{
		parent::__construct();
	}

	function index ()
	{
		$name['forum'] = $this->ModelsForum->GetForum();
		if (empty($name['forum'])) {
			$this->view = array(ERROR, 'Aucun Forum de disponible', 'blue');
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

	public function threads ($name, $id = null)
	{
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

	public function post ()
	{
		$d = array();
		$id = (int) $data['id'];
		$_SESSION['REPLYPOST']   = $id;
		$_SESSION['FORUM']       = uniqid('forum_');
		$_SESSION['FORUM_CHECK'] = $_SESSION['FORUM'];
		$this->ModelsForum->addView($id);
		$d['post'] = $this->ModelsForum->GetPosts($data);
		$this->set($d);
		$this->render('post');
	}
	public function NewThread ($name)
	{
		$_SESSION['NEWTHREADS'] = $name;
		$this->render('newthread');
	}
	public function send ()
	{
		if ($_REQUEST['send'] == 'SubmitReply') {
			self::SubmitReply($this->data);
		} else if ($_REQUEST['send'] == 'NewThread') {
			self::NewPostThread($this->data);
		}
	}
	private function NewPostThread ($data)
	{
		$insert = $this->ModelsForum->SubmitThread($data['id'], $data);
		$this->error ('Forum', $insert['msg'], $insert['type']);
		$this->redirect(true, 2);
	}
	private function SubmitReply ($data)
	{
		$referer = (!empty($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : 'Forum';
		$insert  = $this->ModelsForum->SubmitPost($data);
		$this->error ('Forum : Réponse', $insert['msg'], $insert['type']);
		$this->redirect(true, 2);
	}
}
