<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }
/**
 * rp_ingredients_create.php - Create the rp_ingredients table.
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
    'id'        => "`id` bigint(11) unsigned NOT NULL AUTO_INCREMENT",
    'name'      => "`name` varchar(64) NOT NULL",
    'slug'      => "`slug` varchar(64) NOT NULL",
    'page'      => "`page` bigint(11) unsigned NOT NULL DEFAULT '0'",
    'url'       => "`url` text NOT NULL",
    'recipes'   => "`recipes` text NOT NULL",
    'status'    => "`status` VARCHAR( 16 ) NOT NULL DEFAULT 'active'",
    'modified'  => "`modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
);

/* Table Structure */
$tableSQL = "
    CREATE TABLE IF NOT EXISTS `$tableName` (
      `id` bigint(11) unsigned NOT NULL AUTO_INCREMENT,
      `name` varchar(64) NOT NULL,
      `slug` varchar(64) NOT NULL,
      `page` bigint(11) unsigned NOT NULL DEFAULT '0',
      `url` text NOT NULL,
      `recipes` text NOT NULL,
      `status` VARCHAR( 16 ) NOT NULL DEFAULT 'active',
      `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
";