<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }
/**
 * rp_options_create.php - Create the rp_options table.
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
    'id'        => '`id` bigint(11) unsigned NOT NULL AUTO_INCREMENT',
    'key'       => '`key` tinytext NOT NULL',
    'value'     => '`value` text NOT NULL',
    'status'    => "`status` VARCHAR( 16 ) NOT NULL DEFAULT 'active'",
    'sort_order'=> '`sort_order` bigint(11) unsigned NOT NULL',
    'modified'  => '`modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',

);

/* Table Structure */
$tableSQL = "
    CREATE TABLE IF NOT EXISTS `" . $tableName . "` (
    `id` bigint(11) unsigned NOT NULL AUTO_INCREMENT,
    `key` tinytext NOT NULL,
    `value` text NOT NULL,
    `status` VARCHAR( 16 ) NOT NULL DEFAULT 'active',
    `sort_order` bigint(11) unsigned NOT NULL,
    `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
    ) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
";


/* Default Options */
$defaultSQL = "
    INSERT INTO `$tableName` (`key`, `value`, `sort_order`) VALUES
    ('fermentable', 'American Two-row Pail', 1),
    ('hop', 'Centenniel', 1);
";