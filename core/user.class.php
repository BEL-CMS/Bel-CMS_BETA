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

class AutoUser
{
	function __construct ()
	{
		if (self::isLogged() === false) {
			self::autoLogin();
		}
		self::autoUpdateSession();
	}
	#########################################
	# Auto update last visit timer
	#########################################
	private function autoUpdateSession ()
	{
		if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
			$hash_key = $_SESSION['user']->hash_key;
			if (!isset($_SESSION['SESSION_USER'])) {
				$sql = New BDD();
				$sql->table('TABLE_USERS');
				$sql->where(array('name' => 'hash_key', 'value' => $hash_key));
				$sql->sqlData(array('last_visit' => date('Y-m-d H:i:s'), 'ip' => Common::GetIp()));
				$sql->update();
				if ($sql->rowCount == 1) {
					$_SESSION['SESSION_USER'] = true;
				}
			}
		}
	}
	#########################################
	# Auto connection through cookie
	#########################################
	public static function isLogged () {
		if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
			$return = (bool) true;
		} else {
			unset($_SESSION['user']);
			$return = (bool) false;
		}
		return $return;
	}
	#########################################
	# Reset Session User and return user
	#########################################
	public static function ResetSession ()
	{
		unset($_SESSION['user']);
		self::autoLogin();
	}
	#########################################
	# Logout
	#########################################
	public static function logout()
	{
		unset($_SESSION['user']);
		setcookie('BEL-CMS-COOKIE', NULL, -1, '/');
		$return['msg']  = 'Votre session est vos cookie de ce site sont effacés';
		$return['type'] = 'yellow';
		return $return;
	}
	#########################################
	# Auto connection through cookie
	#########################################
	private function autoLogin()
	{
		// Si la session existe déjà, inutile d'aller plus loin
		if (self::isLogged() === false) {
			// Control si la variable $_COOKIE existe
			if (isset($_COOKIE['BEL-CMS-COOKIE']) AND !empty($_COOKIE['BEL-CMS-COOKIE'])) {
				// Passe en tableaux les valeurs du $_COOKIE
				$cookie = explode('###', $_COOKIE['BEL-CMS-COOKIE']);
				$name   = $cookie[0]; $hash_key = $cookie[1]; $date = $cookie[2]; $hash = $cookie[3];
				// Verifie le hash_key est bien de 32 caractere
				if ($hash_key AND strlen($hash_key) == 32) {
					self::login($name, $hash, $hash_key);
				}
			}
		}
	}
	#########################################
	# login
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
					$return['type'] = 'success';
				} else {
					$return['msg']  = 'Mauvaise combinaison de Pseudonyme-email et/ou mot de passe';
					$return['type'] = 'error';
				}
			} else {
				$return['msg']  = 'Aucun utilisateur avec ce Pseudonyme/mail';
				$return['type'] = 'warning';
			}
		} else {
			$return['msg']  = 'Le nom ou le mot de passe est obligatoire';
			$return['type'] = 'error';
		}
		return $return;
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

			if (empty($hash_key) or $hash_key == null) {
				return (object) array(
					'username' => DELETE,
					'avatar'   => DEFAULT_AVATAR,
					'groups'   => array()
				);
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
								$get = AutoUser::getNameAvatar($value);
								if ($get) {
									$v[$key]['name'] = $get->username;
									$v[$key]['avatar'] = $get->avatar;
								}
							}
						}
					}

					if ($k == 'gender') {
						$v = defined(strtoupper($v)) ? constant(strtoupper($v)) : $v;
					}

					if (!is_array($v)) {
						$return[$k] = empty($v) ? '' : $v;
					} else {
						$return[$k] = $v;
					}

					$directoryUploads = ROOT.'uploads';
					if (is_dir($directoryUploads) === false) {
						if (!mkdir($directoryUploads, 0777)) {
							throw new Exception('Failed to create directory : '.$directoryUploads);
						} else {
							$fopen = fopen($directoryUploads.DS.'index.html', 'a+');
							$fclose = fclose($fopen);
						}
					}

					$directoryUsers = ROOT.'uploads'.DS.'users';
					if (is_dir($directoryUsers) === false) {
						if (!mkdir($directoryUsers, 0777)) {
							throw new Exception('Failed to create directory : '.$directoryUsers);
						} else {
							$fopen = fopen($directoryUsers.DS.'index.html', 'a+');
							$fclose = fclose($fopen);
						}
					}

					$directoryUser = ROOT.'uploads/users/'.$hash_key;
					if (is_dir($directoryUser) === false) {
						if (!mkdir($directoryUser, 0777)) {
							throw new Exception('Failed to create directory : '.$directoryUser);
						} else {
							$fopen = fopen($directoryUser, 'a+');
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
					'avatar'   => DEFAULT_AVATAR,
					'groups'   => array()
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

		return (object) $return;
	}
	#####################################
	# Insert registration
	#####################################
	public static function sendRegistration (array $data)
	{
		if ($data) {
			$error = null;
			// Ajout du blacklistage des mail jetables
			$sql = New BDD();
			$sql->table('TABLE_MAIL_BLACKLIST');
			$sql->isObject(false);
			$sql->queryAll();
			$results = $sql->data;

			$arrayBlackList = array();

			foreach ($results as $k => $v) {
				$arrayBlackList[$v['id']] = $v['name'];
			}

			if (!empty($data['email'])) {
				$tmpMailSplit = explode('@', $data['email']);
				$tmpNdd =  explode('.', $tmpMailSplit[1]);
			}
			/*
			foreach ($data as $k => $v) {
				if (!array_search($k, $this->structureUser)) {
					if ($k != 'name') {
						unset($data[$k]);
					}
				}
			}
			*/

			if (empty($data['username']) OR empty($data['email']) OR empty($data['password'])) {
				$return['text']  = 'Les champs nom d\'utilisateur & e-mail & mot de passe doivent être rempli'; ++$error;
				$return['type']  = 'error';
			} else if (in_array($tmpNdd[0], $arrayBlackList)) {
				$return['text']  = 'Les e-mails jetables ne sont pas autorise'; ++$error;
				$return['type']  = 'error';
			} else if ($_REQUEST['query_register'] != $_SESSION['TMP_QUERY_REGISTER']['OVERALL'])  {
				$return['text']  = 'Le code de sécurité est incorrect'; ++$error;
				$return['type']  = 'error';
			} else if (strlen($data['username']) < 4) {
				$return['text']  = 'Le nom d\'utilisateur est trop court, minimum 4 caractères'; ++$error;
				$return['type']  = 'error';
			} else if (strlen($data['username']) > 32) {
				$return['text']  = 'Le nom d\'utilisateur est trop long, maximum 32 caractères'; ++$error;
				$return['type']  = 'error';
			} else if (strlen($data['password']) < 6) {
				$return['text']  = 'Le mot de passe est trop court, minimum 6 caractères'; ++$error;
				$return['type']  = 'error';
			} else if ($data['password'] != $_REQUEST['password_repeat']) {
				$return['text']  = 'Le mot de passe et la confirmation ne sont pas identiques'; ++$error;
				$return['type']  = 'error';
			} else if ($_REQUEST['terms'] == 'off') {
				$return['text']  = 'Les termes n\'ont pas été acceptés'; ++$error;
				$return['type']  = 'error';
			}

			if ($error === null) {

				$sql = New BDD();
				$sql->table('TABLE_USERS');
				$sql->where(array('name'=>'username','value'=>$data['username']));
				$sql->count();
				$returnCheckName = (int) $sql->data;

				$sql = New BDD();
				$sql->table('TABLE_USERS');
				$sql->where(array('name'=>'email','value'=>$data['email']));
				$sql->count();
				$checkMail = (int) $sql->data;

				if ($returnCheckName >= 1) {
					$return['text']  = 'ce Nom / Pseudo est déjà réservé.';
					$return['type']  = 'warning';
				} elseif ($checkMail >= 1) {
					$return['text']  = 'ce courriel est déjà réservé.';
					$return['type']  = 'warning';
				} else {

					$data['password']          = password_hash($data['password'], PASSWORD_DEFAULT);
					$data['hash_key']          = md5(uniqid(rand(), true));
					$data['date_registration'] = date('Y-m-d H:i:s');
					$data['last_visit']        = date('Y-m-d H:i:s');
					$data['groups']            = (int) 0;
					$data['main_groups']       = (int) 0;
					$data['valid']             = (int) 1;
					$data['ip']                = Common::getIp();

					$insert = New BDD();
					$insert->table('TABLE_USERS');
					$insert->sqlData($data);
					$insert->insert();

					$dataProfils = array(
						'hash_key'     => $data['hash_key'],
						'gender'       => 'unisexual',
						'public_mail'  => '',
						'websites'     => '',
						'list_ip'      => '',
						'list_avatar'  => '',
						'config'       => 0,
						'info_text'    => '',
						'birthday'     => date('Y-m-d')
					);

					$insert = New BDD();
					$insert->table('TABLE_USERS_PROFILS');
					$insert->sqlData($dataProfils);
					$insert->insert();

					$insert = New BDD();
					$insert->table('TABLE_USERS_SOCIAL');
					$insert->sqlData(array('hash_key'=> $data['hash_key']));
					$insert->insert();

					AutoUser::login($_POST['username'],$_POST['password']);

					$return['text']     = 'Enregistrement en cours...';
					$return['type']     = 'success';
					$return['redirect'] = 'User';
				}
			}
			return $return;
		}
	}
	public static function getNameAvatar ($hash_key = false) {
		if ($hash_key && strlen($hash_key) == 32) {
			$BDD = New BDD();
			$BDD->table('TABLE_USERS');
			$BDD->where(array(
				'name'  => 'hash_key',
				'value' => $hash_key
			));
			$BDD->fields(array('username','avatar', 'date_registration'));
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
}
