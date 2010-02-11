<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

/**
 * bp_Overview.classes.php - Class for overview page
 *
 * @package Recipe Press
 * @subpackage classes
 * @author GrandSlambert
 * @copyright 2009-2010
 * @access public
 */
 
class bp_Overview extends bp_Base {
    /* Set Variables */
    const menuName = 'recipe-press-overview';
    protected $view = 'overview';
}
