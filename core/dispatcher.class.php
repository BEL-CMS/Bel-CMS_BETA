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

class Dispatcher
{
	var $controller;
	var $view;
	var $links;

	function __construct ()
	{
		$this->links      = isset($_GET['params']) && !empty($_GET['params']) ? explode('/', strtolower(rtrim($_GET['params'], '/'))) : array();
		$this->controller = self::controller();
		$this->view       = isset($this->links[1]) && !empty($this->links[1]) ? $this->links[1] : 'index';
		$this->IsJquery   = self::IsJquery();
		$this->IsEcho     = self::IsEcho();
	}

	private function controller ()
	{
		if (isset($this->links[0]) && !empty($this->links[0])) {
			$unauthorized = strtolower($this->links[0]);
			switch ($unauthorized) {
				case 'config':
					return false;
				break;

				case 'asset':
					return false;
				break;

				case 'class':
					return false;
				break;

				case 'pages':
					return false;
				break;

				case 'templates':
					return false;
				break;

				case 'uploads':
					return false;
				break;

				case 'widgets':
					return false;
				break;

				case 'home':
					$return = 'blog';
				break;

				case 'index.html':
					$return = 'blog';
				break;

				case 'forum.html':
					$return = 'forum';
				break;

				case 'downloads.html':
					$return = 'downloads';
				break;

				case 'telechargement.html':
					$return = 'downloads';
				break;

				case 'user.html':
					$return = 'user';
				break;

				case 'utilisateur.html':
					$return = 'user';
				break;

				default:
					$return = $this->links[0];
				break;
			}
		} else {
			$return = 'blog';
		}

		return $return;
	}

	#########################################
	# Set page
	#########################################
	public static function RequestPages ()
	{
		if (isset($_GET['page']) AND is_numeric($_GET['page']) ) {
			$return = intval($_GET['page']);
		} else {
			$return = 1;
		}
		return $return;
	}

	function IsJquery ()
	{
		$return = false;
		if (isset($_GET['jquery'])) {
			$return = true;
		} else if (isset($_GET['ajax'])) {
			$return = true;
		}
		return $return;
	}

	function IsEcho ()
	{
		$return = false;
		if (isset($_GET['echo'])) {
			$return = true;
		}
		return $return;
	}
}