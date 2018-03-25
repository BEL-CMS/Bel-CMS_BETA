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
	var $models = array('ModelsBlog');
	private $_error = false;

	function __construct()
	{
		parent::__construct();
		if (parent::accessPage(strtolower(get_class($this))) === false) {
			$this->error(INFO, 'Désolé, vous n’avez pas accès à cette page', 'info');
			$this->_error = true;
		}
		if ($_SESSION['pages']->blog->active == 0) {
			$this->error(INFO, 'Les blogs sont désactiver', 'info');
			$this->_error = true;
		}
	}

	function index ()
	{
		if ($this->_error === false) {
			$set['pagination'] = $this->pagination($_SESSION['pages']->blog->config['MAX_BLOG'], 'blog', TABLE_PAGES_BLOG);
			$set['blog'] = $this->ModelsBlog->GetBlog();
			$this->set($set);
			$this->render('index');
		}
	}

	function readmore ($name = false, $id = false)
	{
		if ($this->_error === false) {
			$set = array();
			$set['blog'] = $this->ModelsBlog->GetBlog($id);
			if (count($set['blog']) == 0) {
				$this->error(BLOG, NAME_OF_THE_UNKNOW, 'danger');
				return;
			} else {
				$this->ModelsBlog->NewView($id);
			}
			$this->set($set);
			$this->render('readmore');
		}
	}
}
