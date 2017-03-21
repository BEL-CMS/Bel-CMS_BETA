<?php

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
		$this->set($name);
		$this->render('readmore');
	}
}
