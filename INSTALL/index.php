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

ini_set('default_charset', 'utf-8');
error_reporting (0);

define ('ROOT', str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']));
define ('DS', '/');

require ROOT.DS.'includes'.DS.'belcms.class.php';

$install = New BelCMS;
echo $install->HTML();