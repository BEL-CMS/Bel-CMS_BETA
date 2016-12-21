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

class ControllerPagesBlog extends ModelPagesBlog
{
	public 	$data,
			$view;
	private $id;
	#####################################
	# Start Class
	#####################################
	public function __construct($id = null)
	{
		if ($id === null) {
			$this->id = Common::SecureRequest(constant('GET_ID'));
		} else {
			$this->id = Common::SecureRequest($id);
		}
		self::GetConfig();
		User::getAccessPage();
	}
	protected function GetConfig ()
	{
		$config = Access::GetConfigPages('blog');
		foreach ($config as $k => $v) {
			$this->$k = $v;
		}
	}
	public function Index ()
	{
		if (isset($_SESSION['pages']->blog->config['MAX_BLOG'])) {
			$nbpp = (int) $_SESSION['pages']->blog->config['MAX_BLOG'];
		} else {
			$nbpp = (int) 3;
		}
		$this->pagination = Common::Pagination($nbpp, GET_PAGE, TABLE_PAGES_BLOG);
		$this->data = self::GetBlog($this->id);
		if (!is_array($this->data)) {
			$return[] = $this->data;
			$this->data = $return;
		}
	}
	public function ReadMore ()
	{
		$this->data = self::GetBlog($this->id);
		if (empty($this->data)) {
			$this->view = array(ERROR, 'Aucun Blog portant ce nom', 'red');
		}
	}
}
