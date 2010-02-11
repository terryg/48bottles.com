<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }
/**
 * footer.php - View for the footer of all pages.
 *
 * @package Recipe Press
 * @subpackage views
 * @author GrandSlambert
 * @copyright 2009-2010
 * @access public
 */
?>

<div style="clear:both; margin-top:10px;">
    <div class="postbox" style="width:49%; height: 175px; float:left;">
        <h3 class="handl" style="margin:0; padding:3px;cursor:default;"><?php _e('Credits', 'recipe-press'); ?></h3>
        <div style="padding:8px;">
            <p>
                <?php printf(__('Thank you for trying the %1$s plugin - I hope you find it useful. For the latest updates on this plugin, vist the %2$s. If you have problems with this plugin, please use our %3$s', 'recipe-press'),
                    $this->pluginName,
                    '<a href="http://wordpress.grandslambert.com/plugins/recipe_press.html" target="_blank">' . __('official site', 'recipe-press') . '</a>',
                    '<a href="http://support.grandslambert.com/forum/recipe-press" target="_blank">' . __('Support Forum', 'recipe-press') . '</a>'
                ); ?>
            </p>
            <p>
                <?php printf(__('This plugin is &copy; %1$s by %2$s and is released under the %3$s', 'recipe-press'),
                    '2009-' . date("Y"),
                    '<a href="http://grandslambert.com" target="_blank">GrandSlambert, Inc.</a>',
                    '<a href="http://www.gnu.org/licenses/gpl.html" target="_blank">' . __('GNU General Public License', 'recipe-press') . '</a>'
                ); ?>
            </p>
        </div>
    </div>
    <div class="postbox" style="width:49%; height: 175px; float:right;">
        <h3 class="handl" style="margin:0; padding:3px;cursor:default;"><?php _e('Donate', 'recipe-press'); ?></h3>
        <div style="padding:8px">
            <p>
                <?php printf(__('If you find this plugin useful, please consider supporting this and our other great %1$s.', 'recipe-press'), '<a href="http://wordpress.grandslambert.com/plugins.html" target="_blank">' . __('plugins', 'recipe-press') . '</a>'); ?>
                <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&amp;hosted_button_id=8972425" target="_blank"><?php _e('Donate a few bucks!', 'recipe-press'); ?></a>
            </p>
            <p style="text-align: center;"><a target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&amp;hosted_button_id=8972425"><img width="122" height="47" alt="paypal_btn_donateCC_LG" src="http://wordpress.grandslambert.com/wp-content/uploads/2009/07/paypal_btn_donateCC_LG.gif" title="paypal_btn_donateCC_LG" class="aligncenter size-full wp-image-174"/></a></p>
        </div>
    </div>
</div>