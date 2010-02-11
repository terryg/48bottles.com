<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }
/**
 * rp_options_update_092.php - Adds size options during update.
 *
 * @package Recipe Press
 * @subpackage classes
 * @subpackage sql
 * @author GrandSlambert
 * @copyright 2009-2010
 * @access public
 */
$query = "
    INSERT INTO `$tableName` (`key`, `value`, `sort_order`) VALUES
    ('ingredient_size', 'bag', 0),
    ('ingredient_size', 'big', 0),
    ('ingredient_size', 'bottle', 0),
    ('ingredient_size', 'box', 0),
    ('ingredient_size', 'bunch', 0),
    ('ingredient_size', 'can', 0),
    ('ingredient_size', 'carton', 0),
    ('ingredient_size', 'container', 0),
    ('ingredient_size', 'count', 0),
    ('ingredient_size', 'cup', 0),
    ('ingredient_size', 'clove', 0),
    ('ingredient_size', 'dash', 0),
    ('ingredient_size', 'dozen', 0),
    ('ingredient_size', 'drop', 0),
    ('ingredient_size', 'envelope', 0),
    ('ingredient_size', 'fluid ounce', 0),
    ('ingredient_size', 'gallon', 0),
    ('ingredient_size', 'gram', 0),
    ('ingredient_size', 'head', 0),
    ('ingredient_size', 'jar', 0),
    ('ingredient_size', 'large', 0),
    ('ingredient_size', 'pound', 0),
    ('ingredient_size', 'leaf', 0),
    ('ingredient_size', 'link', 0),
    ('ingredient_size', 'liter', 0),
    ('ingredient_size', 'loaf', 0),
    ('ingredient_size', 'medium', 0),
    ('ingredient_size', 'ounce', 0),
    ('ingredient_size', 'package', 0),
    ('ingredient_size', 'piece', 0),
    ('ingredient_size', 'pinch', 0),
    ('ingredient_size', 'pint', 0),
    ('ingredient_size', 'packet', 0),
    ('ingredient_size', 'quart', 0),
    ('ingredient_size', 'scoop', 0),
    ('ingredient_size', 'sheet', 0),
    ('ingredient_size', 'slice', 0),
    ('ingredient_size', 'small', 0),
    ('ingredient_size', 'sprig', 0),
    ('ingredient_size', 'stalk', 0),
    ('ingredient_size', 'stick', 0),
    ('ingredient_size', 'strip', 0),
    ('ingredient_size', 'tablespoon', 0),
    ('ingredient_size', 'teaspoon', 0),
    ('ingredient_size', 'whole', 0);
";