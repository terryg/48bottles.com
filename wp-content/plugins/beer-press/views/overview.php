<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }
/**
 * overview.php - View for the Overview page.
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
    <h2><?php echo $this->pluginName; ?> &raquo; <?php _e('Overview', 'recipe-press'); ?></h2>
    <div class="postbox" style="width:49%; float:left">
        <h3 class="handl" style="margin:0;padding:3px;cursor:default;"><?php _e('At A Glance', 'recipe-press'); ?></h3>
        <div class="table">
            <table class="widefat recipe-press-overview">
                <tbody>
                    <tr>
                        <?php if ($this->userCan('recipes')) : ?>
                        <td class="num" style="width:1px"><a href="<?php $this->adminURL(rp_Recipes::menuName); ?>"><?php $this->showCount('recipes'); ?></a></td>
                        <td class="text"><a href="<?php  $this->adminURL(rp_Recipes::menuName); ?>"><?php _e('Recipes', 'recipe-press'); ?></a></td>
                        <?php else : ?>
                        <td colspan="2">&nbsp;</td>
                        <?php endif; ?>

                        <?php if ($this->userCan('comments')) : ?>
                        <td class="num" style="width:1px"><a href="<?php $this->adminURL(rp_Comments::menuName); ?>"><?php $this->showCount('comments'); ?></a></td>
                        <td class="text"><a href="<?php $this->adminURL(rp_Comments::menuName); ?>"><?php _e('Comments', 'recipe-press'); ?></a></td>
                        <?php else : ?>
                        <td colspan="2">&nbsp;</td>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <?php if ($this->userCan('recipes')) : ?>
                        <td class="num" style="width:1px"><a href="<?php $this->adminURL(rp_Recipes::menuName, array('status'=>'pending')); ?>"><?php $this->showCount('recipes', 'pending'); ?></a></td>
                        <td class="text pending"><a class="pending" href="<?php $this->adminURL(rp_Recipes::menuName, array('status'=>'pending')); ?>"><?php _e('Recipes Pending Review', 'recipe-press'); ?></a></td>
                        <?php else : ?>
                        <td colspan="2">&nbsp;</td>
                        <?php endif; ?>

                        <?php if ($this->userCan('comments')) : ?>
                        <td class="num approved" style="width:1px"><a href="<?php $this->adminURL(rp_Comments::menuName, array('status'=>'active')); ?>"><?php $this->showCount('comments', 'active'); ?></a></td>
                        <td class="text approved"><a class="approved" href="<?php $this->adminURL(rp_Comments::menuName, array('status'=>'active')); ?>"><?php _e(' Approved', 'recipe-press'); ?></a></td>
                        <?php else : ?>
                        <td colspan="2">&nbsp;</td>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <?php if ($this->userCan('categories')) : ?>
                        <td class="num" style="width:1px"><a href="<?php $this->adminURL(rp_Categories::menuName); ?>"><?php echo $this->showCount('categories'); ?></a></td>
                        <td class="text"><a href="<?php $this->adminURL(rp_Categories::menuName); ?>"><?php _e('Categories', 'recipe-press'); ?></a></td>
                        <?php else : ?>
                        <td colspan="2">&nbsp;</td>
                        <?php endif; ?>

                        <?php if ($this->userCan('comments')) : ?>
                        <td class="num pending" style="width:1px"><a href="<?php $this->adminURL(rp_Comments::menuName, array('status'=>'pending')); ?>"><?php $this->showCount('comments', 'pending'); ?></a></td>
                        <td class="text pending"><a class="pending" href="<?php $this->adminURL(rp_Comments::menuName, array('status'=>'pending')); ?>"><?php _e('Pending', 'recipe-press'); ?></a></td>
                        <?php else : ?>
                        <td colspan="2">&nbsp;</td>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <?php if ($this->userCan('tags')) : ?>
                        <td class="num" style="width:1px"><a href="<?php $this->adminURL(rp_Ingredients::menuName); ?>"><?php $this->showCount('ingredients'); ?></a></td>
                        <td class="text"><a href="<?php  $this->adminURL(rp_Ingredients::menuName); ?>"><?php _e('Ingredients', 'recipe-press'); ?></a></td>
                        <?php else : ?>
                        <td colspan="2">&nbsp;</td>
                        <?php endif; ?>

                        <?php if ($this->userCan('comments')) : ?>
                        <td class="num" style="width:1px"><a class="spam" href="<?php  $this->adminURL(rp_Comments::menuName, array('status'=>'spam')); ?>"><?php $this->showCount('comments', 'spam'); ?></a></td>
                        <td class="text spam"><a class="spam" href="<?php $this->adminURL(rp_Comments::menuName, array('status'=>'spam')); ?>"><?php _e('Spam', 'recipe-press'); ?></a></td>
                        <?php else : ?>
                        <td colspan="2">&nbsp;</td>
                        <?php endif; ?>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

    <div class="postbox" style="width:49%; float:right">
        <h3 class="handl" style="margin:0; padding:3px;cursor:default;"><?php _e('About', 'recipe-press'); ?> <?php echo $this->pluginName; ?></h3>
        <div style="padding:5px;">
            <p><span><?php _e('You are using', 'recipe-press'); ?> <strong><a href="http://wordpress.grandslambert.com/plugins/recipe-press.html" target="_blank"> <?php echo $this->pluginName; ?></a> version <?php echo $this->showVersion(); ?></strong>.</span></p>
            <p>
                <?php _e('Quick Navigation', 'recipe-press'); ?>:

                <?php if ($this->userCan('recipes')) : ?>
                <a href="<?php $this->adminURL(rp_Recipes::menuName); ?>"><?php _e('Recipes', 'recipe-press'); ?></a> |
                <?php endif; ?>

                <?php if ($this->userCan('add-recipe')) : ?>
                <a href="<?php $this->adminURL(rp_Add_Recipe::menuName); ?>"><?php _e('Add Recipe', 'recipe-press'); ?></a> |
                <?php endif; ?>

                <?php if ($this->userCan('categories')) : ?>
                <a href="<?php $this->adminURL(rp_Categories::menuName); ?>"><?php _e('Categories', 'recipe-press'); ?></a> |
                <?php endif; ?>

                <?php if ($this->userCan('comments')) : ?>
                <a href="<?php $this->adminURL(rp_Comments::menuName); ?>"><?php _e('Comments', 'recipe-press'); ?></a> |
                <?php endif; ?>

                <?php if ($this->userCan('tags')) : ?>
                <a href="<?php $this->adminURL(rp_Tags::menuName); ?>"><?php _e('Tags', 'recipe-press'); ?></a> |
                <?php endif; ?>

                <?php if ($this->userCan('settings')) : ?>
                <a href="<?php $this->adminURL(rp_Settings::menuName); ?>"><?php _e('Settings', 'recipe-press'); ?></a>
                <?php endif; ?>

            </p>
            <p>
                For help using this plugin or to report issues, please visit the offical <a href="http://support.grandslambert.com/forum/recipe-press" target="_blank"><?php _e('Support Forum', 'recipe-press'); ?></a>
            </p>
        </div>
    </div>
    <?php include "footer.php"; ?>
</div>