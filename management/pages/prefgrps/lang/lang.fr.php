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
	'MANAGEMENT_TITLE_NAME' => 'Paramètres des groupes',
	'NB_GROUPS'             => 'Nombre d\'utilisateurs',
	'NAME_TO_GROUP'         => 'Nom du groupe',
	'HELP_NAME_TO_GROUP'    => 'Majuscule pour le nom au format multi-langue (fichier lang)',
	'DEL_GROUP_SUCCESS'     => 'Groupe supprimer avec succès',
	'DEL_GROUP_ERROR'       => 'Erreur lors de la suppression du groupe',
	'ERROR_NO_ID_DEL'       => 'Impossible de supprimer le groupe Administrateur && Membres',
	'ERROR_NO_ID_EDIT'      => 'Impossible d\'éditer ce groupe',
	'ADD_THE_GROUPS'        => 'Ajouter le groupe',
	'NEW_GROUP_SUCCESS'     => 'Groupe ajouter avec succès',
	'NEW_GROUP_ERROR'       => 'Erreur lors de la création du groupe',
));
