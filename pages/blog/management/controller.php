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

class Blog extends Pages
{
	var $models = array('ModelsManagementBlog');

	public 	$data,
			$view,
			$pagination,
			$error = null;

	function __construct($id = null)
	{
		if (isset($_SESSION['pages']->blog->config['MAX_BLOG_ADMIN'])) {
			$this->nbpp = (int) $_SESSION['pages']->blog->config['MAX_BLOG_ADMIN'];
		} else {
			$this->nbpp = (int) 25;
		}
	}

	public function index ()
	{
		//$this->pagination = Common::Pagination($this->nbpp, GET_PAGE, TABLE_PAGES_BLOG);
		$set['d'] = $this->ModelsManagementBlog->GetBlog();
		$this->set($set);
		$this->render('index');
	}

	public function send ()
	{
		if ($_POST['send'] == 'blog') {
			$return = parent::SendNew($_POST);
		} else if ($_POST['send'] == 'edit') {
			$return = parent::SendEdit($_POST);
		} else if ($_POST['send'] == 'parameter') {
			$return = parent::UpdateParameter($_POST);
		}
		$this->data = $return;
		Common::Redirect('Blog?management', 2);
	}
	public function del ()
	{
		$return = parent::DelNew(GET_ID);
		$this->data = $return;
		Common::Redirect('Blog?management', 2);
	}
	public function edit ()
	{
		$return = parent::GetBlog(GET_ID);
		$this->data = $return;
	}
}
