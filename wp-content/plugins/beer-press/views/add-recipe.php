<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }
/**
 * add-recipe.php - View for the Add Recipe page.
 *
 * @package Recipe Press
 * @subpackage views
 * @author GrandSlambert
 * @copyright 2009-2010
 * @access public
 */
?>

<div class="wrap">
    <div class="icon32" id="icon-recipe-press"><br/></div>
    <h2><?php echo $this->pluginName; ?> &raquo; <?php _e('Add Recipe', 'recipe-press'); ?></h2>
    <?php if ( isset($this->message) ) : ?>
    <div class="updated fade below-h2" id="message" style="background-color: rgb(255, 251, 204);">
        <p><?php echo $this->message; ?></p>
    </div>
    <?php endif; ?>
    <form class="validate" action="<?php $this->adminURL(rp_Add_Recipe::menuName); ?>" method="post" id="recipe-form" name="recipe-form">
        <input type="hidden" value="add" name="form-action"/>
        <?php settings_fields($this->optionsName); ?>
        <?php include ('recipe-form.php'); ?>

    </form>
    <?php include('footer.php'); ?>
</div>
