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
	#########################################
	# Variable declaration
	#########################################
	private $url;
	#########################################
	# Start Class
	#########################################
	function __construct ()
	{
		$GLOBALS['DISPATCHER'] = true;
		$this->url = isset($_REQUEST['param']) && !empty($_REQUEST['param']) ? explode('/', strtolower(rtrim($_REQUEST['param'], '/'))) : false;
		unset($_REQUEST['param']);

		if (!isset($_REQUEST) OR empty($_REQUEST)) {
			$_REQUEST = array(0);
		}

		$array = (array(
			'GET_PAGE'       => self::NamePage(),
			'GET_ACTION'     => self::NameAction(),
			'GET_ID'         => self::RequestId(),
			'GET_ID_SUPP'    => self::RequestIdSupp(),
			'GET_PAGES'      => self::RequestPages(),
			)
		);

		foreach ($array as $k => $v) {
			define($k, $v);
		}

		if (defined('GET_PAGE')) {
			$var = GET_PAGE;
			if (!defined('HOME_PAGE')) {
				if (!empty($var)) define('HOME_PAGE', true); unset($var);
			}
		}

	}
	#########################################
	# Checks if it is a page in ajax
	#########################################
	protected function RequestAjax ()
	{
		$return = false;

		$jquery = array(
			'jquery',
			'Jquery',
			'ajax',
			'Ajax'
		);

		foreach ($jquery as $k) {
			if (array_key_exists($k, $_REQUEST)) {
				$return = (bool) true;
				break;
			}
		}

		return $return;

	}
	#########################################
	# Checks if it is a page in echo only
	#########################################
	protected function RequestEcho ()
	{
		$return = false;

		$echo = array(
			'Echo',
			'echo'
		);

		foreach ($echo as $k) {
			if (array_key_exists($k, $_REQUEST)) {
				$return = (bool) true;
				break;
			}
		}

		return $return;

	}
	#########################################
	# Set name module
	#########################################
	private function NamePage ()
	{
		if (isset($this->url[0]) && !empty($this->url[0])) {
			$unauthorized = strtolower($this->url[0]);
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
					$return = $this->url[0];
				break;
			}
		} else {
			$return = 'blog';
		}

		return $return;
	}
	#########################################
	# Set name action
	#########################################
	private function NameAction ()
	{
		return isset($this->url[1]) && !empty($this->url[1]) ? $this->url[1] : 'index';
	}
	#########################################
	# Set id
	#########################################
	private function RequestId ()
	{
		return isset($this->url[2]) && !empty($this->url[2]) ? $this->url[2] : false;
	}
	#########################################
	# Set page
	#########################################
	private function RequestPages ()
	{
		if (isset($_REQUEST['page']) AND is_numeric($_REQUEST['page']) ) {
			$return = (int) $_REQUEST['page'];
		} else {
			$return = 0;
		}
		return $return;
	}
	#########################################
	# Set page
	#########################################
	private function RequestIdSupp ()
	{
		$return = '';
		if (isset($_REQUEST['id'])) {
			if (!is_numeric($_REQUEST['id'])) {
				$GLOBALS['ERROR'] = array('ID', 'ID must be a number !');
			} else {
				$return = (int) $_REQUEST['id'];
			}
			$return = (int) $_REQUEST['id'];
		}
		return $return;
	}
}

