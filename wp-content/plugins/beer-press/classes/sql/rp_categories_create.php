<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }
/**
 * rp_categories_create.php - Create the rp_categories table.
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
    'id'          => '`id` bigint(11) unsigned NOT NULL AUTO_INCREMENT',
    'media_id'    => "`media_id` bigint( 11 ) unsigned NOT NULL DEFAULT '0'",
    'parent'      => '`parent` bigint(11) unsigned NOT NULL',
    'name'        => '`name` tinytext COLLATE utf8_unicode_ci NOT NULL',
    'slug'        => '`slug` tinytext COLLATE utf8_unicode_ci NOT NULL',
    'description' => '`description` text COLLATE utf8_unicode_ci NOT NULL',
    'status'      => "`status` VARCHAR( 16 ) NOT NULL DEFAULT 'active'",
    'created'     => '`created` datetime NOT NULL',
    'modified'    => '`modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
);



/* Table Structure */
$tableSQL = "
    CREATE TABLE IF NOT EXISTS `" . $tableName . "` (
        `id` bigint(11) unsigned NOT NULL AUTO_INCREMENT,
        `media_id` bigint(11) unsigned NOT NULL DEFAULT '0',
        `parent` bigint(11) unsigned NOT NULL,
        `name` tinytext COLLATE utf8_unicode_ci NOT NULL,
        `slug` tinytext COLLATE utf8_unicode_ci NOT NULL,
        `description` text COLLATE utf8_unicode_ci NOT NULL,
        `status` VARCHAR( 16 ) NOT NULL DEFAULT 'active',
        `created` datetime NOT NULL,
        `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
";

/* Default Categories */
$defaultSQL = "
    INSERT INTO `$tableName` (`id`, `name`, `slug`, `created`) VALUES
    (1, 'Main Dishes', 'main-dishes', now()),
    (2, 'Side Dishes', 'side-dishes', now()),
    (3, 'Desserts', 'desserts', now()),
    (4, 'Appetizers', 'appetizers', now());
";