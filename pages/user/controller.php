<?php
/**
 * Bel-CMS [Content management system]
 * @version 0.3.0
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

class User extends Pages
{
	var $models = array('ModelsUser');
	private $_error = false;
	#####################################
	# Start Class
	#####################################
	public function __construct()
	{
		parent::__construct();
		if ($_SESSION['pages']->user->active == 0) {
			$this->error(INFO, 'La page utilisateur est désactiver', 'info');
			$this->_error = true;
		}
	}

	public function index ()
	{
		if ($this->_error === false) {
			if (AutoUser::isLogged() === true) {
				$d = array();
				AutoUser::ResetSession();
				$d['user'] = AutoUser::ReturnUser();
				$this->set($d);
				$this->render('index');
				} else {
					$this->redirect('User/login', 1);
					$this->error('LOGIN', LOGIN_REQUIRE, 'info');
			}
		}
	}
	public function login ()
	{
		if ($this->_error === false) {
			if (AutoUser::isLogged() === false) {
				$this->render('login');
			} else {
				$d = array();
				$d['user'] = AutoUser::ReturnUser();
				$this->set($d);
				$this->render('index');
			}
		}
	}
	public function register ()
	{
		if ($this->_error === false) {
			if (AutoUser::isLogged() === false) {
				$_SESSION['TMP_QUERY_REGISTER'] = array();
				$_SESSION['TMP_QUERY_REGISTER']['number_1'] = rand(1, 9);
				$_SESSION['TMP_QUERY_REGISTER']['number_2'] = rand(1, 9);
				$_SESSION['TMP_QUERY_REGISTER']['overall']  = $_SESSION['TMP_QUERY_REGISTER']['number_1'] + $_SESSION['TMP_QUERY_REGISTER']['number_2'];
				$_SESSION['TMP_QUERY_REGISTER'] = Common::arrayChangeCaseUpper($_SESSION['TMP_QUERY_REGISTER']);
				$this->data = (bool) true;
				$this->render('register');
			} else {
				$this->redirect('user', 0);
			}
		}
	}
	public function logout ()
	{
		if ($this->_error === false) {
			$return = AutoUser::logout();
			$this->error('Logout', $return['msg'], $return['type']);
			$this->redirect('user', 3);
		}
	}
	public function lostpassword ()
	{
		if ($this->_error === false) {
			if (AutoUser::isLogged() === false) {
				$this->data = (bool) true;
				$this->render('lostpassword');
			}
		}
	}
	private function sendLostPassword ()
	{
		if ($this->_error === false) {
			unset($this->data['send']);
			$return = $this->ModelsUser->checkToken($this->data);
			if (!isset($return['pass'])) {
				$this->error('Password', $return['msg'], $return['type']);
				$this->redirect('User/LostPassword', 3);
			} else {
				$this->error('Password', $return['msg'], $return['type']);
			}
		}
	}
	public function send ()
	{
		if ($this->_error === false) {
			if ($this->data['send'] == 'register') {
				self::sendRegister();
			} else if ($this->data['send'] == 'login') {
				self::sendLogin();
			} else if ($this->data['send'] == 'mailpassword') {
				self::mailpassword();
			} else if ($this->data['send'] == 'editsocial') {
				self::editsocial();
			} else if ($this->data['send'] == 'editprofile') {
				self::editprofil();
			} else if ($this->data['send'] == 'lostpassword') {
				self::sendLostPassword();
			} else if ($this->data['send'] == 'sendavatar') {
				self::sendAvatar();
			} else if ($this->data['send'] == 'changeavatar') {
				self::changeAvatar();
			} else if ($this->data['send'] == 'deleteavatar') {
				self::deleteAvatar();
			} else {
				$this->redirect('user', 3);
			}
		}
	}
	private function sendAvatar ()
	{
		if ($this->_error === false) {
			$return = $this->ModelsUser->sendAvatarUpload();
			if ($return['type'] == 'success') {
				$this->error('Avatar Upload', $return['msg'], $return['type']);
				AutoUser::ResetSession();
				$this->redirect('user', 3);
			} else {
				$this->error('Avatar Upload', $return['msg'], $return['type']);
				$this->redirect('user', 3);
			}
		}
	}
	private function changeAvatar ()
	{
		if ($this->_error === false) {
			unset($_REQUEST['send']);
			$return = $this->ModelsUser->sendChangeAvatar($_REQUEST['value']);
			$this->jquery = array('type' => $return['type'], 'text' => $return['msg'] );
			AutoUser::ResetSession();
			$this->redirect('user', 3);
		}
	}

	private function deleteAvatar ()
	{
		if ($this->_error === false) {
			unset($_REQUEST['send']);
			$return = $this->ModelsUser->sendDeleteAvatar($_REQUEST['value']);
			$this->error('Delete Avatar', $return['msg'], $return['type']);
			$this->redirect('user', 3);
		}
	}

	private function sendRegister ()
	{
		if ($this->_error === false) {
			if (empty($this->data)){
				$this->error(ERROR, 'Field Empty', 'error');
				$this->redirect('user/register', 3);
			} else {
				$return = $this->ModelsUser->sendRegistration($this->data);
				$this->error('Registration', $return['msg'], $return['type']);
				$this->redirect('user', 2);
			}
		}
	}
	private function sendLogin ()
	{
		if ($this->_error === false) {
			if (empty($this->data)){
				$this->error(ERROR, 'Field Empty', 'error');
				$this->redirect('user/login', 3);
			} else {
				$return = AutoUser::login($this->data['username'], $this->data['password']);
				$this->redirect('user/login', 2);
				$this->error('Login', $return['msg'], $return['type']);
			}
		}
	}
	private function editprofil ()
	{
		if ($this->_error === false) {
			if (empty($this->data)) {
				$this->error(ERROR, 'Field Empty');
				$this->redirect('user/login', 3);
			} else {
				unset($this->data['send']);
				$return = $this->ModelsUser->sendEditProfil($this->data);
				$this->error('Edition Profile Information', $return['msg'], $return['type']);
				AutoUser::ResetSession();
				$this->redirect('User', 2);
			}
		}
	}
	private function editsocial ()
	{
		if ($this->_error === false) {
			if (empty($this->data)) {
				$this->error(ERROR, 'Field Empty');
				$this->redirect('user/login', 3);
			} else {
				unset($this->data['send']);
				$return = $this->ModelsUser->sendEditSocial($this->data);
				$this->error('Edit social media', $return['msg'], $return['type']);
				AutoUser::ResetSession();
				$this->redirect('User', 2);
			}
		}
	}
	private function mailpassword ()
	{
		if ($this->_error === false) {
			if (empty($this->data)) {
				$this->error(ERROR, 'Field Empty');
				$this->redirect('user/login', 3);
			} else {
				unset($this->data['send']);
				$return = $this->ModelsUser->sendEditPassword($this->data);
				$this->view = array('Change email and password', $return['msg'], $return['type']);
				AutoUser::ResetSession();
				$this->redirect('User', 2);
			}
		}
	}
	public function GetUser($usermail = null, $userpass = null, $api_key = null)
	{
		if ($usermail !== null && $userpass !== null && $api_key) {
			if (defined('API_KEY')) {
				if (!empty($api_key) && $api_key == constant('API_KEY')) {
					$this->json = $this->ModelsUser->GetInfosUser($usermail, $userpass);
				}
			}
		} else {
			$this->json = null;
		}
	}
}
