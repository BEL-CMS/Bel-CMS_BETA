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
$BDD = 'server';
$databases["server"] = array(
#####################################
# Réglages MySQL - SERVEUR
#####################################
'DB_DRIVER'   => 'mysql',
'DB_NAME'     => 'belcms',
'DB_USER'     => 'root',
'DB_PASSWORD' => '',
'DB_HOST'     => 'localhost',
'DB_PREFIX'   => 'belcms_',
'DB_PORT'     => '3306'
);
Common::constant($databases[$BDD]); unset($databases, $BDD);
