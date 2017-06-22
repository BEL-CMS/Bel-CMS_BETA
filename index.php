<?php
$GLOBALS['timestart'] = microtime(true);
#########################################
# Initialise TimeZone
#########################################
date_default_timezone_set("Europe/Brussels");
setlocale(LC_TIME, 'fr','fr_FR','fr_FR@euro','fr_FR.utf8','fr-FR','fra');
#########################################
# Define
#########################################
define ('CHECK_INDEX', true);
define ('DEBUG', true);
define ('WEB_ROOT',str_replace('index.php','',$_SERVER['SCRIPT_NAME']));
define ('ROOT',str_replace('index.php','',$_SERVER['SCRIPT_FILENAME']));
define ('DS', '/');
define ('ROOT_HTML', 'templates'.DS);
define ('ROOT_HTML_DFT', 'assets'.DS.'tpl'.DS);
define ('DIR_CORE', ROOT.'core'.DS);
define ('DIR_TPL', ROOT.'templates'.DS);
define ('DIR_CONFIG', ROOT.'config'.DS);
define ('DIR_PAGES', ROOT.'pages'.DS);
define ('DIR_LANG', ROOT.'assets'.DS.'lang'.DS);
define ('DIR_ASSET_TPL',ROOT.'assets'.DS.'tpl'.DS);
define ('DIR_WIDGETS', ROOT.'widgets'.DS);
define ('DIR_VISITORS', ROOT.'visitors'.DS);
define ('DIR_UPLOADS', ROOT.'uploads'.DS);
define ('ERROR_INDEX', '<!DOCTYPE html>\r\n<html><head>\r\n<title>403 Direct access forbidden</title>\r\n</head><body>\r\n<h1>Direct access forbidden</h1>\r\n<p>The requested URL '.$_SERVER['SCRIPT_NAME'].' is prohibited.</p>\r\n</body></html>');
/* TMP Futur reset management ) */
define ('ROOT_MANAGEMENT',ROOT.'management'.DS);
define ('ROOT_MANAGEMENT_TPL','management'.DS);
#########################################
# Inisialize session
#########################################
require_once DIR_CORE.'session.class.php';
#########################################
# Install
#########################################
if (is_file(ROOT.'INSTALL'.DS.'index.php')) {
	header('Location: INSTALL/index.php');
	die();
}
#########################################
# Require Files
#########################################
require DIR_CORE.'require_files.class.php';
New RequireFiles;

$bel_cms = New BelCMS;
$bel_cms->_init();
echo $bel_cms->render;