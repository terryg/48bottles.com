<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }
/**
 * edit_recipe.php - View for the Edit Recipe page.
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
    <h2><?php echo $this->pluginName; ?> &raquo; <?php _e('Edit Recipe', 'recipe-press'); ?></h2>
    <?php if ( isset($this->message) ) : ?>
    <div class="updated fade below-h2" id="message" style="background-color: rgb(255, 251, 204);">
        <p><?php echo $this->message; ?></p>
    </div>
    <?php endif; ?>
    <form class="validate" action="<?php $this->adminURL(rp_Recipes::menuName); ?>" method="post" id="recipe-form" name="recipe-form" enctype="multipart/form-data">
        <input type="hidden" value="update" name="form-action"/>
        <input type="hidden" value="<?php echo $this->recipe->id; ?>" name="id"/>
        <?php settings_fields($this->optionsName); ?>
        <?php include ('recipe-form.php'); ?>
    </form>
    <?php include('footer.php'); ?>
</div>