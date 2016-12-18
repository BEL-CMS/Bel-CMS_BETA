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

final class BelCMS extends dispatcher
{
	private $error = false,
			$buffer;
	#########################################
	# Démarrage de la class
	#########################################
	function __construct()
	{
		parent::__construct();
		#########################################
		# Initialise Session
		#########################################
		if (!session_id()) {
			session_start();
		}
		ob_start();
		$GLOBALS['timestart'] = microtime(true);
		#########################################
		# Inclusion du fichier config
		#########################################
		include ROOT.'config'.DS.'config.define.php';
		#########################################
		# Inclusion des fichiers
		#########################################
		try {
			require_once ROOT_CLASS.'require_file.class.php';

			New RequireFiles;
			New User;
			New Visitors;

			if (defined('MANAGEMENT')) {
				$Management = New Management;
				if ($this->RequestAjax() === true) {
					if (isset($Management->jquery['redirect'])) {
						$data['redirect'] = $Management->jquery['redirect'];
					}
					$data['type'] = $Management->jquery['type'];
					$data['text'] = $Management->jquery['text'];
					echo json_encode($data);
				} else {
					echo $Management->return;
				}
			} else {
				$Template = New Template;
				if ($this->RequestEcho() === true) {
					if (isset($_SESSION['ECHO'])) {
						echo $_SESSION['ECHO'];
						unset($_SESSION['ECHO']);
					} else {
						echo 'ERROR';
					}
				} else if ($this->RequestAjax() === true) {
					if (isset($_SESSION['JQUERY'])) {
						echo json_encode($_SESSION['JQUERY']);
						unset($_SESSION['JQUERY']);
					} else {
						echo json_encode(array('type' => 'ERROR', 'text' => 'ERROR'));
					}
				} else {
					echo $Template->view;
				}
			}
		} catch (gestionDesErreurs $e) {
			echo 'Exception : ',  $e->getMessage(), PHP_EOL;
		}
		$this->buffer = ob_get_contents();
		ob_end_clean();
	}
	public function GetBuffer()
	{
		return $this->buffer;
	}
}
