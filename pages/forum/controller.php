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

class ControllerPagesForum extends ModelPagesForum
{
	public 	$data,
			$view,
			$access = false;
	#####################################
	# Start Class
	#####################################
	public function __construct()
	{
		User::getAccessPage();
	}
	public function index ()
	{
		$this->data = parent::GetForum();
		if (empty($this->data)) {
			$this->view = array(ERROR, 'Aucun Forum de disponible', 'blue');
		} else {
			foreach ($this->data as $k => $v) {
				$tmp_threads[] = $v->id;
			}
			$threads = parent::GetThreads($tmp_threads);

			foreach ($this->data as $k => $v) {
				foreach ($threads as $k_t => $v_t) {
					if ($v_t->id_forum == $v->id) {
						$this->data[$k]->threads[] = $v_t;
					}
				}
			}
		}
	}
	public function threads ()
	{
		$data = parent::GetPost(GET_ID, GET_ID_SUPP);
		if (NO_ACCESS_GROUP_PAGE == $data) {
			$this->view = array(ERROR, NO_ACCESS_GROUP_PAGE, 'red');
		} else {
			$this->data = $data;
		}
	}
	public function post ()
	{
		$_SESSION['REPLYPOST']   = GET_ID_SUPP;
		$_SESSION['FORUM']       = uniqid('forum_');
		$_SESSION['FORUM_CHECK'] = $_SESSION['FORUM'];
		parent::addView(GET_ID_SUPP);
		$this->data = parent::GetPosts(GET_ID, GET_ID_SUPP);
	}
	public function NewThread ()
	{
		$_SESSION['NEWTHREADS'] = GET_ID;
	}
	public function send ()
	{
		if ($_REQUEST['send'] == 'SubmitReply') {
			self::SubmitReply($_POST);
		} else if ($_REQUEST['send'] == 'NewThread') {
			self::NewPostThread($_POST);
		}
	}
	private function NewPostThread ($data)
	{
		$insert = parent::SubmitThread($data);
		$this->view = array('Forum : Nouveau Post', $insert['msg'], $insert['type']);
		Common::redirect('Forum', 2);
	}
	private function SubmitReply ($data = false)
	{
		$insert = parent::SubmitPost($data);
		$this->view = array('Forum : Réponse', $insert['msg'], $insert['type']);
		Common::redirect('Forum', 2);
	}
}
