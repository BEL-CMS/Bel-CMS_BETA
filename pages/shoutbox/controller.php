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

class ControllerPagesShoutbox extends ModelPagesShoutbox
{
	public 	$data,
			$view;
	private $id;
	#####################################
	# Start Class
	#####################################
	public function __construct($id = null)
	{
	}
	public function index ()
	{
		$this->data = '';
	}
	public function send ()
	{
		$return = parent::insertMsg();
		$_SESSION['JQUERY'] = array('type' => $return['type'], 'text' => $return['text'] );
	}
	public function get()
	{
		$return = '';
		$get = parent::getLast();

		$i = 1;
		foreach ($get as $k => $v):
			$i++;
			if ($i & 0) {
				$left_right =  'by_myself right';
			} else {
				$left_right =  'from_user left';
			}
			$username = User::getNameAvatar($v->hash_key);
			$msg = ' ' . $v->msg;
			$msg = preg_replace("#([\t\r\n ])(www|ftp)\.(([\w\-]+\.)*[\w]+(:[0-9]+)?(/[^ \"\n\r\t<]*)?)#i", '\1<a href="http://\2.\3" onclick="window.open(this.href); return false;">\2.\3</a>', $msg);
			$msg = preg_replace("#([\n ])([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>", $msg);
			$msg = preg_replace_callback('`((https?|ftp)://\S+)`', 'cesure_href',$msg);

			$return .= '<li class="'.$left_right.'" id="id_'.$v->id.'">
				<a data-toggle="tooltip" title="'.$username->username.'" href="Members/View/'.$username->username.'" class="avatar">
					<img src="'.$username->avatar.'">
				</a>
				<div class="message_wrap"> <span class="arrow"></span>
					<div class="info"> <a data-toggle="tooltip" title="'.$username->username.'" href="Members/View/'.$username->username.'" class="name">'.$username->username.'</a> <span class="time">'.$v->date_msg.'</span>
					</div>
					<div class="text">'.$msg.'</div>
				</div>
			</li>';
		endforeach;

		$_SESSION['ECHO'] = $return;
	}
}
