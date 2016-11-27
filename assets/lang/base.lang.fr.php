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

Common::constant(array(
    #####################################
    # Fichier lang en français - Erreur
    #####################################
    'ERROR'                  => 'Erreur',
    'UNKNOWN_ERROR'          => 'Erreur inconnu',
    'WARNING'                => 'Avertissement',
    'INFO'                   => 'Information',
    'SUCCESS'                => 'Succès',
    'ACCESS'                 => 'Accès',
    'NO_ACCESS_REQUEST_PAGE' => 'L\'accès a la page demandée est interdite',
    'NO_ACCESS_PAGE'         => 'La page demandée est actuellement fermée',
    'NO_ACCESS_GROUP_PAGE'   => 'Votre groupe d\'accès ne vous permet pas d\'accéder à cette page',
    'DEFAULT_AVATAR'         => 'assets/imagery/default_avatar.jpg',
    #####################################
    # COMMUN
    #####################################
    'VALIDATE_MEMBER'        => 'Membre validé',
    'VALIDATE_MEMBER'        => 'Membre en actif',
    'PENDINNG_MEMBER'        => 'Membre en attente',
    'GUEST'                  => 'Visiteur',
    'VALID'                  => 'Valider',
    'SEE'                    => 'Voir',
    'ADD'                    => 'Ajouter',
    'EDIT'                   => 'Editer',
    'MODIFY'                 => 'Modifier',
    'DELETE'                 => 'Supprimer',
    'BACK'                   => 'Retour',
    'CONFIRM'                => 'Confirmer',
    'UNKNOWN'                => 'Inconnu',
    'MESSAGE'                => 'Message',
    'MESSAGES'               => 'Messages',
    'ON'                     => 'Le',
    'SEND'                   => 'Envoyer',
    'ABOUT_ME'               => 'À propos de moi',
    'VIEW'                   => 'Voir',
    'USERNAME'               => 'Nom d\'utilisateur',
    'BIRTHDAY'               => 'Anniversaire',
    'LOCATION'               => 'Emplacement',
    'GENDER'                 => 'Genre',
    'WEBSITE'                => 'Site Internet',
    'NAME'                   => 'Nom',
    'DATE'                   => 'Date',
    'OPTIONS'                => 'Options',
    'SAVE'                   => 'Enregister',
    'CANCEL'                 => 'Annuler',
    'LOGIN_REQUIRE'          => 'Login requis',
    #####################################
    # COLOR
    #####################################
    'RED'                    => 'Rouge',
    'BLUE'                   => 'Bleu',
    'YELLOW'                 => 'Jaune',
    'GREEN'                  => 'Vert',
    #####################################
    # POSITION
    #####################################
    'TOP'                    => 'Haut',
    'RIGHT'                  => 'Droit',
    'BOTTOM'                 => 'Bas',
    'LEFT'                   => 'Gauche',
    #####################################
    # Management
    #####################################
    'PREFGEN'                => 'Préférences Générales',
    #####################################
    # Nom des modules
    #####################################
    'HOME'                   => 'Accueil',
    'BLOG'                   => 'Blog',
    'NEWS'                   => 'News',
    'DOWNLOADS'              => 'Téléchargements',
    'FORUM'                  => 'Forum',
    'USER'                   => 'Utilisateur',
    'USERS'                  => 'Utilisateurs',
    'COMMENTS'               => 'Commentaires',
    'COMMENT'                => 'Commentaire',
    'READMORE'               => 'Lire la suite',
    'NEWTHREAD'              => 'Nouveau Post',
    #####################################
    # USER
    #####################################
    'FEMALE'                 => 'Femme',
    'MALE'                   => 'Homme',
    'UNISEXUAL'              => 'Unisexe',
    #####################################
    # WIDGETS USERS
    # ###################################
    'CONNECTED'              => 'Connectés',
));