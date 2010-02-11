<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }
/**
 * tags.php - View for the Tags page.
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
    <h2><?php echo $this->pluginName; ?> &raquo; <?php _e('Tags', 'recipe-press'); ?></h2>
    <div class="col-wrap">
        <h3>Tags will be added in the next release! Stay Tuned!</h3>
        <?php if ( isset($this->message) ) : ?>
        <div class="updated fade below-h2" id="message" style="background-color: rgb(255, 251, 204);">
            <p><?php echo $this->message; ?></p>
        </div>
        <?php endif; ?>
    </div>
    <?php include('footer.php'); ?>
</div>