<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

/**
 * rp_Tags.class.php - Class for tags moderation
 *
 * @package Recipe Press
 * @subpackage classes
 * @author GrandSlambert
 * @copyright 2009-2010
 * @access public
 */
 
class rp_Tags extends rp_Base {
    /* Set Variables */
    const menuName = 'recipe-press-tags';
    protected $view = 'tags';
}