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

class ControllerPagesUser extends ModelPagesUser
{
	public 	$data,
			$jquery,
			$view;
	private $id,
			$user;
	#####################################
	# Start Class
	#####################################
	public function __construct()
	{
		User::ResetSession();
		$this->user = User::ReturnUser();
	}

	public function index ()
	{
		if ($this->user !== false) {
			$this->data = $this->user;
		} else {
			Common::redirect('/user/login');
		}
	}
	public function login ()
	{
		if ($this->user === false) {
			$this->data = (bool) true;
		} else {
			$this->data = (bool) false;
			Common::redirect('User');
		}

	}
	public function register ()
	{
		if ($this->user === false) {
			$_SESSION['TMP_QUERY_REGISTER'] = array();
			$_SESSION['TMP_QUERY_REGISTER']['number_1'] = rand(1, 9);
			$_SESSION['TMP_QUERY_REGISTER']['number_2'] = rand(1, 9);
			$_SESSION['TMP_QUERY_REGISTER']['overall']  = $_SESSION['TMP_QUERY_REGISTER']['number_1'] + $_SESSION['TMP_QUERY_REGISTER']['number_2'];
			$_SESSION['TMP_QUERY_REGISTER'] = Common::arrayChangeCaseUpper($_SESSION['TMP_QUERY_REGISTER']);

			$this->data = (bool) true;
		} else {
			Common::redirect('User');
		}
	}
	public function logout ()
	{
		$return = User::logout();
		$this->view = array('Logout', $return['msg'], $return['type']);
		Common::redirect('User/Login', 2);
	}
	public function lostpassword ()
	{
		if ($this->user === false) {
			$this->data = (bool) true;
		}
	}
	private function sendLostPassword ($data)
	{
		unset($data['send']);
		$return = parent::checkToken($data);
		if (!isset($return['pass'])) {
			$this->view = array('Password', $return['msg'], $return['type']);
			Common::redirect('User/LostPassword', 3);
		} else {
			$this->view = array('Password', $return['msg'], $return['type']);
		}
	}
	public function send ()
	{
		if ($_REQUEST['send'] == 'register') {
			self::sendRegister($_REQUEST);
		} else if ($_REQUEST['send'] == 'login') {
			self::sendLogin($_REQUEST);
		} else if ($_REQUEST['send'] == 'mailpassword') {
			self::mailpassword($_REQUEST);
		} else if ($_REQUEST['send'] == 'editsocial') {
			self::editsocial($_REQUEST);
		} else if ($_REQUEST['send'] == 'editprofile') {
			self::editprofil($_REQUEST);
		} else if ($_REQUEST['send'] == 'lostpassword') {
			self::sendLostPassword($_REQUEST);
		} else if ($_REQUEST['send'] == 'sendavatar') {
			self::sendAvatar();
		} else if ($_REQUEST['send'] == 'changeavatar') {
			self::changeAvatar();
		} else if ($_REQUEST['send'] == 'deleteavatar') {
			self::deleteAvatar();
		} else {
			Common::redirect('User', 0);
		}
	}
	private function sendAvatar ()
	{
		$return = parent::sendAvatarUpload();
		if ($return['type'] == 'green') {
			$this->view = array('Avatar Upload', $return['msg'], $return['type']);
			Common::redirect('User', 2);
		} else {
			$this->view = array('Avatar Upload', $return['msg'], $return['type']);
			Common::redirect('User', 2);
		}
	}
	private function changeAvatar ()
	{
		unset($_REQUEST['send']);
		$return = parent::sendChangeAvatar($_REQUEST['value']);
		$_SESSION['JQUERY'] = array('type' => $return['type'], 'text' => $return['msg']);
		$this->view = array('Avatar Upload', $return['msg'], $return['type']);
	}

	private function deleteAvatar ()
	{
		unset($_REQUEST['send']);
		$return = parent::sendDeleteAvatar($_REQUEST['value']);
		$this->view = array('Delete Avatar', $return['msg'], $return['type']);
	}

	private function sendRegister ($data = false)
	{
		if (empty($data)) {
			$this->view = array(ERROR, 'Field empty', 'red');
		} else {
			$return = parent::sendRegistration($data);
			$this->view = true;
			if ($return['type'] != 'danger') {
				$_SESSION['JQUERY'] ='user';
			}
			$_SESSION['JQUERY'] = $return;
		}
	}
	private function sendLogin ($data = false)
	{
		if (empty($data)) {
			$this->view = array(ERROR, 'Field Empty', 'red');
			//Common::redirect('user/login', 3);
		} else {
			$return = User::login($_REQUEST['username'], $_REQUEST['password']);
			$this->view = array('Login', $return['msg'], $return['type']);
			Common::redirect('user/login', 2);
		}
	}
	private function editprofil ($data = false)
	{
		if (empty($data)) {
			$this->view = array(ERROR, 'Field Empty', 'red');
			Common::redirect('user/login', 3);
		} else {
			unset($data['send']);
			$return = parent::sendEditProfil($data);
			$this->view = array('Edition Profile Information', $return['msg'], $return['type']);
			Common::redirect('User', 2);
		}
	}
	private function editsocial ($data = false)
	{
		if (empty($data)) {
			$this->view = array(ERROR, 'Field Empty', 'red');
			Common::redirect('user/login', 3);
		} else {
			unset($data['send']);
			$return = parent::sendEditSocial($data);
			$this->view = array('Edit social media', $return['msg'], $return['type']);
			Common::redirect('User', 2);
		}
	}
	private function mailpassword ($data = false)
	{
		if (empty($data)) {
			$this->view = array(ERROR, 'Field Empty', 'red');
			Common::redirect('user/login', 3);
		} else {
			unset($data['send']);
			$return = parent::sendEditPassword($data);
			$this->view = array('Change email and password', $return['msg'], $return['type']);
			Common::redirect('User', 2);
		}
	}
}
