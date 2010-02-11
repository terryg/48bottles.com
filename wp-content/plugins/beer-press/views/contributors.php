<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }
/**
 * contributors.php - View for the Contributors page.
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
    <h2><?php echo $this->pluginName; ?> &raquo; <?php _e('Contributors', 'recipe-press'); ?></h2>
    <p><?php _e('This page includes a list of Recipe Press users who have contributed time or money to the development of this plugin.', 'recipe-press'); ?></p>
    <div class="col-wrap">
        <div style="clear:both; margin-top:10px;">
            <div class="postbox" style="width:49%; float:left;">
                <h3 class="handl" style="margin:0; padding:3px;cursor:default;"><?php _e('Major Contributors', 'recipe-press'); ?></h3>
                <div style="padding:8px">
                    <?php $this->theList('major'); ?>
                </div>
            </div>
            <div class="postbox" style="width:49%; float:right;">
                <h3 class="handl" style="margin:0; padding:3px;cursor:default;"><?php _e('Additional Contributors', 'recipe-press'); ?></h3>
                <div style="padding:8px;">
                    <?php $this->theList('contributors'); ?>
                </div>
            </div>
        </div>
    </div>
    <?php include('footer.php'); ?>
</div>
