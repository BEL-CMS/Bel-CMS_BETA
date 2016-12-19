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

#########################################
# Initialise TimeZone
#########################################
date_default_timezone_set("Europe/Brussels");
setlocale(LC_TIME, 'fr','fr_FR','fr_FR@euro','fr_FR.utf8','fr-FR','fra');
################################################
# Définition du ROOT & séparation & check index
################################################
define ('BELCMS_DEBUG', true);
define ('ROOT', str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']));
define ('DS', '/');
define ('CHECK_INDEX', true);
define ('ERROR_INDEX', '<!DOCTYPE html>\r\n<html><head>\r\n<title>403 Direct access forbidden</title>\r\n</head><body>\r\n<h1>Direct access forbidden</h1>\r\n<p>The requested URL '.$_SERVER['SCRIPT_NAME'].' is prohibited.</p>\r\n</body></html>');
################################################
# Inclusion du fichier primaire
################################################
include ROOT.'class/check_files_and_folder.class.php';
include ROOT.'class/error.class.php';
include ROOT.'class/dispatcher.class.php';
New CheckFilesFolders;
require ROOT.DS.'class'.DS.'belcms.class.php';
$belcms = New BelCMS;
echo $belcms->GetBuffer();
