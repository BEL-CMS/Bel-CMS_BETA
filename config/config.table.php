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

#########################################
# Correctif de l'absence de caractère
#########################################
if (defined('DB_PREFIX')) { $DB_PREFIX = DB_PREFIX; } else { $DB_PREFIX = ''; }
#########################################
# End Correctif
#########################################
Common::constant(array(
	#########################################
	# Tables
	#########################################
	'TABLE_CONFIG'           => trim($DB_PREFIX.'belcms_config'),
	'TABLE_PAGES_CONFIG'     => trim($DB_PREFIX.'belcms_config_pages'),
	'TABLE_GROUPS'           => trim($DB_PREFIX.'belcms_groups'),
	'TABLE_MAIL_BLACKLIST'   => trim($DB_PREFIX.'belcms_mails_blacklist'),
	'TABLE_PAGE'             => trim($DB_PREFIX.'belcms_page'),
	'TABLE_PAGES_BLOG'       => trim($DB_PREFIX.'belcms_pages_blog'),
	'TABLE_FORUM'            => trim($DB_PREFIX.'belcms_page_forum'),
	'TABLE_FORUM_POST'       => trim($DB_PREFIX.'belcms_page_forum_post'),
	'TABLE_FORUM_POSTS'      => trim($DB_PREFIX.'belcms_page_forum_posts'),
	'TABLE_FORUM_THREADS'    => trim($DB_PREFIX.'belcms_page_forum_threads'),
	'TABLE_SHOUTBOX'         => trim($DB_PREFIX.'belcms_page_shoutbox'),
	'TABLE_USERS'            => trim($DB_PREFIX.'belcms_page_users'),
	'TABLE_MANAGEMENT'       => trim($DB_PREFIX.'belcms_page_users_management'),
	'TABLE_USERS_PROFILS'    => trim($DB_PREFIX.'belcms_page_users_profils'),
	'TABLE_USERS_SOCIAL'     => trim($DB_PREFIX.'belcms_page_users_social'),
	'TABLE_STATS'            => trim($DB_PREFIX.'belcms_stats'),
	'TABLE_VISITORS'         => trim($DB_PREFIX.'belcms_visitors'),
	'TABLE_WIDGETS'          => trim($DB_PREFIX.'belcms_widgets'),
	'TABLE_DOWNLOADS_CAT'    => trim($DB_PREFIX.'belcms_downloads_cat'),
));
?>
