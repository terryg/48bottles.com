<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }
/**
 * bp_recipes_create.php - Create the bp_recipes table.
 *
 * @package Beer Press
 * @subpackage classes
 * @subpackage sql
 * @author tgl@rideside.net
 * @copyright 2010
 * @access public
 */

/* Field Structure */
$fields = array(
    'id'            => "`id` bigint(11) NOT NULL AUTO_INCREMENT",
    'user_id'       => "`user_id` bigint(11) unsigned NOT NULL",
    'media_id'      => "`media_id` bigint(11) NOT NULL DEFAULT '0'",
    'category'      => "`category` bigint(11) unsigned NOT NULL DEFAULT '1'",
    'template'      => "`template` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'standard'",
    'title'         => "`title` tinytext COLLATE utf8_unicode_ci NOT NULL",
    'slug'          => "`slug` text COLLATE utf8_unicode_ci NOT NULL",
    'notes'         => "`notes` text COLLATE utf8_unicode_ci NOT NULL",
    'fermentables'  => "`fermentables' longtext COLLATE utf8_unicode_ci NOT NULL",
    'hops'          => "`hops` longtext COLLATE utf8_unicode_ci NOT NULL",
    'yeasts'        => "`yeasts` longtext COLLATE utf8_unicode_ci NOT NULL",
    'miscs'         => "`miscs` longtext COLLATE utf8_unicode_ci NOT NULL",
    'views_total'   => "`views_total` bigint(11) unsigned NOT NULL",
    'comment_total' => "`comment_total` bigint(11) NOT NULL",
    'status'        => "`status` VARCHAR( 16 ) NOT NULL DEFAULT 'active'",
    'featured'      => "`featured` INT( 1 ) NOT NULL DEFAULT '0'",
    'comments_open' => "`comments_open` INT( 1 ) NOT NULL DEFAULT '1'",
    'submitter'     => '`submitter` TEXT NOT NULL',
    'submitter_email'=> '`submitter_email` TEXT NOT NULL',
    'added'         => "`added` datetime NOT NULL",
    'published'     => "`published` datetime NOT NULL",
    'updated'       => "`updated` datetime NOT NULL",
    'modified'      => "`modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
);

/* Table Structure */
$tableSQL = "
    CREATE TABLE IF NOT EXISTS `" . $tableName. "` (
    `id` bigint(11) NOT NULL AUTO_INCREMENT,
    `user_id` bigint(11) unsigned NOT NULL,
    `media_id` bigint(11) NOT NULL DEFAULT '0',
    `category` bigint(11) unsigned NOT NULL DEFAULT '1',
    `template` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'standard',
    `title` tinytext COLLATE utf8_unicode_ci NOT NULL,
    `slug` text COLLATE utf8_unicode_ci NOT NULL,
    `notes` text COLLATE utf8_unicode_ci NOT NULL,
    `fermentables` longtext COLLATE utf8_unicode_ci NOT NULL,
    `hops` longtext COLLATE utf8_unicode_ci NOT NULL,
    `yeasts` longtext COLLATE utf8_unicode_ci NOT NULL,
    `miscs` longtext COLLATE utf8_unicode_ci NOT NULL,
    `views_total` bigint(11) unsigned NOT NULL,
    `comment_total` bigint(11) NOT NULL,
    `status` tinytext COLLATE utf8_unicode_ci NOT NULL,
    `featured` INT( 1 ) NOT NULL DEFAULT '0',
    `comments_open` INT( 1 ) NOT NULL DEFAULT '1',
    `submitter` TEXT NOT NULL,
    `submitter_email` TEXT NOT NULL,
    `added` datetime NOT NULL,
    `published` datetime NOT NULL,
    `updated` datetime NOT NULL,
    `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=0 ;
";

