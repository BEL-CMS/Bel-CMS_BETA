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

class User
{
	#####################################
	# Variable declaration
	#####################################
	private $sql,
			$id;
	#########################################
	# Start Class
	#########################################
	public function __construct () {
		if ($GLOBALS['CONFIG'] === false) {
			New Config;
		}
		self::autoLogin();
	}
	public static function ResetSession () {
		if (isset($_SESSION['user'])) {
			unset($_SESSION['user']);
		}
		$user = new User;
		$user->autoLogin();
	}
	public static function ReturnUser () {
		$return = false;

		if (!isset($_SESSION['user'])) {
			$_SESSION['user'] = false;
			return false;
		}
		if ($_SESSION['user'] === false) {
			return false;
		}

		if (isset($_SESSION['user']) && is_object($_SESSION['user'])) {
			$return = $_SESSION['user'];
		} else {
			if (isset($_SESSION['user'])) {
				if (isset($_SESSION['user']['hash_key']) && strlen($_SESSION['user']['hash_key']) == 32) {
					$user = $_SESSION['user']; unset($_SESSION['user']);
					$_SESSION['user'] = new stdClass();
					foreach ($user as $k => $v) {
						$_SESSION['user']->$k = $v;
					}
					unset($user);
					$return = $_SESSION['user'];
				}
			}
		}

		return $return;
	}
	#########################################
	# login function
	#########################################
	public static function login($name = null, $password = null, $hash_key = false)
	{
		$sql = New BDD();
		$sql->table('TABLE_USERS');
		$sql->isObject(false);

		// Verifie que $name & $password ne son pas vide
		if (!empty($name) AND !empty($password)) {
			// Connexion par mail, name ou seulement par hash_key
			if ($hash_key AND strlen($hash_key) == 32) {
				$hash_key_search = array(
					'name'  => 'hash_key',
					'value' => $hash_key
				);
			} else {
				$hash_key_search = null;
			}
			if (strpos($name, '@')) {
				$request = 'email';
			} else {
				$request = 'username';
			}

			$sql->where(
				array(
					'name'  => $request,
					'value' => $name
				), $hash_key_search
			);

			$sql->queryOne();

			$results = $sql->data;

			if ($results && is_array($results) && sizeof($results)) {
				if ($hash_key AND strlen($hash_key) == 32) {
					$check_password = $password == $results['password'] ? true : false;
				} else {
					$check_password = false;
				}
				if (password_verify($password, $results['password']) OR $check_password) {
					$setcookie = $results['username'].'###'.$results['hash_key'].'###'.date('Y-m-d H:i:s').'###'.$results['password'];
					setcookie('BEL-CMS-COOKIE', $setcookie, time()+60*60*24*30, '/');
					$_SESSION['user'] = self::getInfosUser($results['hash_key']);
					$return['msg']  = 'La connexion a été éffectuée avec succès';
					$return['type'] = 'green';
				} else {
					$return['msg']  = 'Mauvaise combinaison de Pseudonyme/mail et mot de passe';
					$return['type'] = 'red';
				}
			} else {
				$return['msg']  = 'Aucun utilisateur avec ce Pseudonyme/mail';
				$return['type'] = 'green';
			}
		} else {
			$return['msg']  = 'Le nom ou le mot de passe est obligatoire';
			$return['type'] = 'red';
		}
		User::ReturnUser();
		return $return;
	}
	#########################################
	# Logout
	#########################################
	public static function logout()
	{
		unset($_SESSION['user']);
		setcookie('BEL-CMS-COOKIE', NULL, -1, '/');
		session_destroy();
		$return['msg']  = 'Votre session est vos cookie de ce site sont effacés';
		$return['type'] = 'yellow';
		New Config;
		return $return;
	}
	#########################################
	# Auto connection through cookie
	#########################################
	private function autoLogin()
	{
		// Si la session existe déjà, inutile d'aller plus loin
		if (!isset($_SESSION['user']) OR empty($_SESSION['user'])) {
			// Control si la variable $_COOKIE existe
			if (isset($_COOKIE['BEL-CMS-COOKIE']) AND !empty($_COOKIE['BEL-CMS-COOKIE'])  ) {
				// Passe en tableaux les valeurs du $_COOKIE
				$cookie = explode('###', $_COOKIE['BEL-CMS-COOKIE']);
				$name = $cookie[0]; $hash_key = $cookie[1]; $date = $cookie[2]; $hash = $cookie[3];
				if ($hash_key AND strlen($hash_key) == 32) {
					self::login($name, $hash, $hash_key);
				} else {
					$_SESSION['user'] = false;
				}
			} else {
				$_SESSION['user'] = false;
			}
		} else if (!is_object($_SESSION['user'])) {
			if (!isset($_SESSION['user']['groups'])) {
				$_SESSION['user'] = false;
			}
		}

	}
	#########################################
	# Get infos user by id
	#########################################
	public static function getInfosUser($hash_key = false, $name = false)
	{
		if ($hash_key and ctype_alnum($hash_key) or $name === true and $hash_key) {
			
			if ($name === true) {
				$name = 'username';
			} else {
				$name = 'hash_key';
			}

			$sql_user = New BDD();
			$sql_user->table('TABLE_USERS');
			$sql_user->isObject(false);
			$sql_user->where(array(
				'name'  => $name,
				'value' => Common::SecureRequest($hash_key)
			));

			$sql_user->queryOne();
			$results = $sql_user->data;

			if (!empty($results)) {

				$sql_user_profil = New BDD();
				$sql_user_profil->table('TABLE_USERS_PROFILS');
				$sql_user_profil->isObject(false);
				$sql_user_profil->where(array(
					'name'  => 'hash_key',
					'value' => $results['hash_key']
				));
				$sql_user_profil->queryOne();
				$resultsProfils = $sql_user_profil->data;

				$returnMerge = array_merge($results, $resultsProfils);
				if ($returnMerge && sizeof($returnMerge)) {

					$sql_user_social = New BDD();
					$sql_user_social->table('TABLE_USERS_SOCIAL');
					$sql_user_social->isObject(false);
					$sql_user_social->where(array(
						'name'  => 'hash_key',
						'value' => $returnMerge['hash_key']
					));
					$sql_user_social->queryOne();
					$resultsSocial = $sql_user_social->data;
				}


				$returnMergeTwo = array_merge($returnMerge, $resultsSocial);

				foreach ($returnMergeTwo as $k => $v) {
					if ($k == 'birthday') {
						$v = Common::transformDate($v);
					} else if ($k == 'date_registration' OR $k == 'last_visit') {
						$v = Common::transformDate($v, true);
					}
					if ($k == 'avatar') {
						if (empty($v) OR !is_file($v)) {
							$v = DEFAULT_AVATAR;
						}
					}

					if ($k == 'groups') {
						$v = explode('|', $v);
						$v = is_array($v) ? $v : (array) $v;
					}
					if ($k == 'main_groups') {
						$v = (int) $v;
					}

					if ($k == 'friends') {
						if (empty($v)) {
							$v = array();
						} else {
							$arrayHash = explode('|', $v);
							$v = array();
							foreach ($arrayHash as $key => $value) {
								$get = User::getNameAvatar($value);
								if ($get) {
									$v[$key]['name'] = $get->username;
									$v[$key]['avatar'] = $get->avatar;
								}
							}
							/*
							foreach ($arrayHash as $keyTmp => $valueTmp) {
								$v[$keyTmp]['name']   = $valueTmp->name;
								$v[$keyTmp]['avatar'] = $valueTmp->avatar;
								if (empty($v[$keyTmp]['avatar']) OR !is_file($v[$keyTmp]['avatar'])) {
									$v[$keyTmp]['avatar'] = DEFAULT_AVATAR;
								}
							}
							*/
						}
					}

					if ($k == 'gender') {
						$v = defined(strtoupper($v)) ? constant(strtoupper($v)) : $v;
					}

					if (!is_array($v)) {
						$return[$k] = empty($v) ? UNKNOWN : $v;
					} else {
						$return[$k] = $v;
					}

					$directoryAvatar = ROOT.'uploads/users/'.$hash_key;

					if (!file_exists($directoryAvatar)) {
						if (!mkdir($directoryAvatar, 0777, true)) {
							throw new Exception('Failed to create directory');
						} else {
							$fopen = fopen($directoryAvatar.'/index.html', 'a+');
							$fclose = fclose($fopen);
						}
					}
				}
				$return['list_avatar'] = array();
				$getListAvatar = Common::scanFiles('uploads/users/'.$hash_key.'/', array('gif', 'jpg', 'jpeg', 'png'), true);
				foreach ($getListAvatar as $valueListAvatar) {
					$return['list_avatar'][] = $valueListAvatar;
				}

				unset($return['id'], $return['password']);
			} else {
				$return = array(
					'username' => DELETE,
					'avatar'   => DEFAULT_AVATAR
				);
			}

		} else {
			$sql_user = New BDD();
			$sql_user->table('TABLE_USERS');
			$sql_user->isObject(false);
			$sql_user->queryAll();
			$results = $sql_user->data;

			$sql_user_profil = New BDD();
			$sql_user_profil->table('TABLE_USERS_PROFILS');
			$sql_user_profil->isObject(false);

			$sql_user_social = New BDD();
			$sql_user_social->table('TABLE_USERS_SOCIAL');
			$sql_user_social->isObject(false);


			if ($results && sizeof($results)) {

				foreach ($results as $k => $value_users) {
					$tmp_where_users[] = array(
						'name'  => 'hash_key',
						'value' => $value_users['hash_key']
					);
				}

				$sql_user_profil->where($tmp_where_users);
				$sql_user_profil->queryAll();
				$resultsProfils = $sql_user_profil->data;

				$sql_user_social->where($tmp_where_users);
				$sql_user_social->queryAll();
				$resultsSocial = $sql_user_social->data;

				foreach ($resultsProfils as $k => $value_users_profil) {
					$tmp_users_profil[$value_users_profil['hash_key']] = $value_users_profil;
				}

				foreach ($resultsSocial as $k => $value_users_social) {
					$tmp_users_social[$value_users_social['hash_key']] = $value_users_social;
				}

				foreach ($results as $k => $value_users) {
					if (array_key_exists($value_users['hash_key'], $tmp_users_profil) AND array_key_exists($value_users['hash_key'], $tmp_users_social)) {
						$result[$value_users['hash_key']] = array_merge($value_users, $tmp_users_profil[$value_users['hash_key']], $tmp_users_social[$value_users['hash_key']]);
						unset($result[$value_users['hash_key']]['hash_key'],
							  $result[$value_users['hash_key']]['password'],
							  $result[$value_users['hash_key']]['id']
						);
					}
				}

				$return = $result;

			}
		}

		return $return;
	}

	public static function getNameAvatar ($hash_key = false) {
		if ($hash_key && strlen($hash_key) == 32) {
			$BDD = New BDD();
			$BDD->table('TABLE_USERS');
			$BDD->where(array(
				'name'  => 'hash_key',
				'value' => $hash_key
			));
			$BDD->fields(array('username','avatar'));
			$BDD->queryOne();
			$result = $BDD->data;
			if (empty($result)) {
				$result = new StdClass();
				$result->username = UNKNOWN;
				$result->avatar = DEFAULT_AVATAR;
			} else {
				if (empty($result->avatar) OR !is_file($result->avatar)) {
					$result->avatar = DEFAULT_AVATAR;
				}
			}
		} else {
			$result = false;
		}

		return $result;
	}

	public static function getAccessPage ($page = false, $user = false)
	{
		if (User::ReturnUser() !== false) {
			User::ReturnUser()->access = (object) array();
		}

		if ($page !== false and is_string($page)) {
			$config = Access::GetConfigPages($page, true);
			foreach ($config->access_groups as $k => $v) {
				if (is_array($v)) {
					foreach ($v as $k_null => $v_access) {
						if (in_array($v_access, $_SESSION['user']['groups'])) {
							$_SESSION['user']['access']->$page = (bool) true;
							///return true;
						} else {
							$_SESSION['user']['access']->$page = (bool) false;
						}
					}
				} else {
					if (in_array($v, $_SESSION['user']['groups'])) {
						$_SESSION['user']['access']->$page = (bool) true;
						//return true;
					} else {
						$_SESSION['user']['access']->$page = (bool) false;
					}
				}

				if ($v == '#') {
					$_SESSION['user']['access']->$page = (bool) true;
					///return true;
				}
				//if ($_SESSION['user']['group'])
				//$_SESSION['user']['access']->$k = $v->access_groups;
			}
			Common::Debug($_SESSION);
		}
	}
}
