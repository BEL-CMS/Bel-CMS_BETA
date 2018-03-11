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

include DIR_PAGES.'blog'.DS.'models.php';

class ModelsLogin
{
	public function login ($mail = null, $password = null)
	{
		$return['text']     = "Login en cours ...";
		$return['type']     = 'success';
		$return['redirect'] = 'dashboard?management';

		if (!isset($_SESSION['user']->hash_key)) {
			Common::redirect('user/login');
			exit;
		}

		if ($mail != null && $password != null) {

			if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
				$return['text'] = "Invalid email format";
				$return['type'] = 'danger';
				return $return;
				exit;
			}

			$BDD = New BDD;
			$BDD->table('TABLE_USERS');

			$BDD->where(array(
				'name'  => 'email',
				'value' => $mail
			));

			$BDD->fields('password');

			$BDD->queryOne();

			$result = $BDD->data;

			if ($BDD->rowCount != 0) {

				if (!password_verify($password, $result->password)) {
					$return['text'] = "Invalid password";
					$return['type'] = 'danger';
					unset($return['redirect']);
				}

				$cookie = explode('###', $_COOKIE['BEL-CMS-COOKIE']);

				if ($cookie[3] != $result->password) {
					$return['text'] = "Session (Cookie) Invalid";
					$return['type'] = 'warning';
					unset($return['redirect']);
				}

				if ($return['type'] == 'success') {
					$_SESSION['LOGIN_MANAGEMENT'] = (bool) true;
				}
			} else {
				$return['text'] = "e-mail n'est pas répértorier";
				$return['type'] = 'danger';
				unset($return['redirect']);
			}

			return $return;

		}
	}
	public function logout ()
	{
		$return['text']     = 'Déconnexion en cours...';
		$return['type']     = 'success';
		$return['redirect'] = 'Home';

		$_SESSION['LOGIN_MANAGEMENT'] = (bool) false;

		return $return;
	}
}
