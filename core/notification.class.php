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

final class Notification
{
	public 	$title,
			$msg,
			$type;

	function __construct($title = null, $msg = null, $type = 'error', $debug = null)
	{
		$this->title   = self::title($title);
		$this->message = self::message($msg);
		$this->type    = self::type($type);
		$this->bug     = self::bug($debug);
		echo self::render();
	}

	function render ()
	{
		$render  = '<div class="alert-message '.$this->type.'">';
		$render .= '<div class="box-icon"><span></span></div>';
		$render .= '<div class="box-msg">';
		$render .= $this->title == null ? null : '<p><strong>'.$this->title.'</strong></p>'; 
		$render .= '<p>'.$this->message.'</p>';
		$render .= '</div></div>';

		if ($this->bug !== false) {
			$render .= '<div class="alert-message error">';
			$render .= $this->bug;
			$render .= '</div>';
		}
		return $render;
	}

	function title ($title)
	{
		return Common::VarSecure($title, '');
	}

	function type ($type) 
	{
		switch ($type) {
			case 'success':
				$return = 'success';
				break;

			case 'info':
				$return = 'info';
				break;

			case 'warning':
				$return = 'warning';
				break;

			case 'error':
				$return = 'error';
				break;
			
			default:
				$return = 'error';
				break;
		}
		return $return;
	}

	function message ($msg)
	{
		return Common::VarSecure($msg, 'html');
	}

	function bug ($debug)
	{
		if ($debug !== null) {
			ob_start();
			$return  = '<pre><code>';
			$return .=  print_r($debug);
			$return .= '</code></pre>';
			$buffer = ob_get_contents();
			ob_end_clean();
			return $buffer; //DANGEREAOUS 0 SECURITY
		} else {
			return false;
		}
	}
}