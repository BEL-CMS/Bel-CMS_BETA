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

	function __construct()
	{
		parent::__construct();
	}

	function index ()
	{
		$name['blog'] = $this->ModelsBlog->GetBlog();
		$this->set($name);
		$this->pagination($GLOBALS['CONFIG_PAGES']['blog']['config']['MAX_BLOG'], 'blog', TABLE_PAGES_BLOG);
		$this->render('index');
	}

	function readmore ($name = false, $id = false)
	{
		$name = array();
		$name['blog'] = $this->ModelsBlog->GetBlog($id);
		if (count($name['blog']) == 0) {
			$this->error('Forum', 'Page inconnu...', 'danger');
			return;
		}
		$this->set($name);
		$this->render('readmore');
	}
}
