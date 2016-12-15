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

class ControllerPagesLinks extends ModelPagesLinks
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
		$return = parent::GetLink();
		$this->data = $return;
	}
	public function linkOut ()
	{
		$return = parent::GetLink(Common::SecureRequest(GET_ID));
		parent::addClick(Common::SecureRequest(GET_ID));
		$_SESSION['JQUERY'] = array('type' => 'green', 'text' => 'Redirection en cours vers : '.$return->link, 'redirect' => $return->link);
	}
}
