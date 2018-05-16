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

Common::constant(array(
	#####################################
	# Fichier lang en français - Admin
	#####################################
	'PARAMETER'                     => 'Paramètre',
	'PARAMETERS'                    => 'Paramètres',
	'ERROR_UNKNOWN_MANAGEMENT'      => 'Erreur inconnu',
	'DASHBOARD'                     => 'Tableau de bord',
	'GUIDELY'                       => 'Guide',
	'SHOUTBOX'                      => 'T\'chat',
	'SAVE_BDD_SUCCESS'              => 'La sauvegarde a été effectué avec succès',
	'LISCENCE'                      => 'Licence GNU/GPL',
	'FORUM_OFFICIAL'                => 'Forum officiel',
	#####################################
	# _parameter - Admin
	#####################################
	'ADMIN_CMS_WEBSITE_NAME'        => 'Nom',
	'ADMIN_CMS_TPL_WEBSITE'         => 'Template',
	'ADMIN_CMS_WEBSITE_DESCRIPTION' => 'Descripion',
	'ADMIN_CMS_REGISTER_CHARTER'    => 'Règlement',
	'ADMIN_CMS_MAIL_WEBSITE'        => 'E-mail Administrateur',
	'ADMIN_CMS_WEBSITE_KEYWORDS'    => 'Mot Clé',
	'ADMIN_CMS_WEBSITE_LANG'        => 'Langue du site',
	'ADMIN_CMS_TPL_FULL'            => 'Page sans widgets (droit / gauche)',
	'ADMIN_CMS_MAIL'                => '',
	'ADMIN_CMS_JQUERY'              => 'jQuery CMS',
	'ADMIN_CMS_JQUERY_UI'           => 'jQuery UI CMS',
	'ADMIN_CMS_BOOTSTRAP'           => 'Bootstrap CMS',
	#####################################
	# _access - Admin
	#####################################
	'ID_GROUP'                      => 'ID : Groupe',
	#####################################
	# _name - Admin
	#####################################
	'PREFGEN'                       => 'Paramètres Général',
	'PREFGRPS'                      => 'Paramètres Groupes',
	'PREFPAGES'                     => 'Paramètres Pages',
	'PREFWIDGETS'                   => 'Paramètres Widgets',
	'PREFACCESS'                    => 'Accès',
));
