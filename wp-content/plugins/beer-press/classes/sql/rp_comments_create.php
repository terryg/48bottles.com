<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }
/**
 * rp_comments_create.php - Create the rp_comments table.
 *
 * @package Recipe Press
 * @subpackage classes
 * @subpackage sql
 * @author GrandSlambert
 * @copyright 2009-2010
 * @access public
 */

/* Field Structure */
$fields = array(
    'id'            => "`id` bigint(11) unsigned NOT NULL AUTO_INCREMENT",
    'parent'        => "`parent` bigint(11) unsigned NOT NULL DEFAULT '0'",
    'recipe_id'     => "`recipe_id` bigint(11) unsigned NOT NULL DEFAULT '0'",
    'author'        => "`author` tinytext NOT NULL",
    'author_email'  => "`author_email` varchar(100) NOT NULL",
    'author_url'    => "`author_url` varchar(200) NOT NULL",
    'author_IP'     => "`author_IP` varchar(100) NOT NULL",
    'date'          => "`date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'",
    'content'       => "`content` text NOT NULL",
    'status'        => "`status` VARCHAR( 16 ) NOT NULL DEFAULT 'pending'",
    'approved_by'   => "`approved_by` bigint(11) NOT NULL",
    'approved_on'   => "`approved_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'",
    'user_id'       => "`user_id` bigint(20) unsigned NOT NULL DEFAULT '0'"
);

/* Table Sturcture */
$tableSQL = "
    CREATE TABLE IF NOT EXISTS `" . $tableName . "` (
    `id` bigint(11) unsigned NOT NULL AUTO_INCREMENT,
    `parent` bigint(11) unsigned NOT NULL DEFAULT '0',
    `recipe_id` bigint(11) unsigned NOT NULL DEFAULT '0',
    `author` tinytext NOT NULL,
    `author_email` varchar(100) NOT NULL,
    `author_url` varchar(200) NOT NULL,
    `author_IP` varchar(100) NOT NULL,
    `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `content` text NOT NULL,
    `status` VARCHAR( 16 ) NOT NULL DEFAULT 'active',
    `approved_by` bigint(11) NOT NULL,
    `approved_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY `status` (`status`),
    KEY `recipe_id` (`recipe_id`)
    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";

