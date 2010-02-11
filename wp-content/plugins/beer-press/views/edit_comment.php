<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }
/**
 * edit_comment.php - View for the Edit Comment page.
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
    <h2><?php echo $this->pluginName; ?> &raquo; <?php _e('Edit Comment', 'recipe-press'); ?></h2>
    <form name="post" action="<?php $this->adminURL(rp_Comments::menuName); ?>" method="post" id="post">
        <?php wp_nonce_field('update-comment_' . $this->comment->id) ?>
        <div class="wrap">
            <div id="poststuff" class="metabox-holder has-right-sidebar">
                <input type="hidden" name="id" value="<?php echo (int) $this->comment->id ?>" />
                <input type="hidden" name="action" value='update' />

                <div id="side-info-column" class="inner-sidebar">
                    <div id="submitdiv" class="stuffbox" >
                        <h3><span class='hndle'><?php _e('Status', 'recipe-press') ?></span></h3>
                        <div class="inside">
                            <div class="submitbox" id="submitcomment">
                                <div id="minor-publishing">
                                    <div id="preview-action">
                                        <a target="_blank" href="<?php $this->comment_link(false); ?>" class="preview button">View Comment</a>
                                    </div>
                                    <div class="clear"></div>
                                    <!-- Removed until comments display
                                    <div id="minor-publishing-actions">
                                        <div id="preview-action">
                                            <a class="preview button" href="<?php echo get_comment_link(); ?>" target="_blank"><?php _e('View Comment', 'recipe-press'); ?></a>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    -->

                                    <div id="misc-publishing-actions">

                                        <div class="misc-pub-section" id="comment-status-radio">
                                            <label class="approved"><input type="radio"<?php checked( $this->comment->status, 'active' ); ?> name="status" value="active" /><?php /* translators: comment type radio button */ echo _x('Approved', 'adjective') ?></label><br />
                                            <label class="waiting"><input type="radio"<?php checked( $this->comment->status, 'pending' ); ?> name="status" value="pending" /><?php /* translators: comment type radio button */ echo _x('Pending', 'adjective') ?></label><br />
                                            <label class="spam"><input type="radio"<?php checked( $this->comment->status, 'spam' ); ?> name="status" value="spam" /><?php /* translators: comment type radio button */ echo _x('Spam', 'adjective'); ?></label>
                                        </div>

                                        <div class="misc-pub-section curtime misc-pub-section-last">
                                            <?php
                                            // translators: Publish box date formt, see http://php.net/date
                                            $datef = __( 'M j, Y @ G:i' , 'recipe-press');
                                            $stamp = __('Submitted on: <b>%1$s</b>', 'recipe-press');
                                            $date = date_i18n( $datef, strtotime( $this->comment->date ) );
                                            ?>
                                            <span id="timestamp"><?php printf($stamp, $date); ?></span>
                                            &nbsp;<a href="#edit_timestamp" class="edit-timestamp hide-if-no-js" tabindex='4'><?php _e('Edit', 'recipe-press') ?></a>
                                            <div id='timestampdiv' class='hide-if-js'><?php touch_time(('editcomment' == $action), $this->comment->id, 5); ?></div>

                                        </div>
                                    </div> <!-- misc actions -->
                                    <div class="clear"></div>
                                </div>

                                <div id="major-publishing-actions">
                                    <div id="delete-action">
                                        <a class="submitdelete deletion" href="<?php $this->adminURL(rp_Comments::menuName, array('action' => 'delete', 'id' => $this->comment->id)); ?>" onclick="if ( confirm('<?php echo esc_js(__("You are about to delete this comment. \n 'Cancel' to stop, 'OK' to delete.", 'recipe-press')); ?>')) {return true;} return false;"><?php _e('Delete', 'recipe-press'); ?></a>
                                    </div>
                                    <div id="publishing-action">
                                        <input type="submit" name="save" value="<?php esc_attr_e('Update Comment'); ?>" tabindex="4" class="button-primary" />
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="post-body">
                    <div id="post-body-content">
                        <div id="namediv" class="stuffbox">
                            <h3><label for="name"><?php _e( 'Author' , 'recipe-press') ?></label></h3>
                            <div class="inside">
                                <table class="form-table editcomment">
                                    <tbody>
                                        <tr valign="top">
                                            <td class="first"><?php _e( 'Name:' , 'recipe-press'); ?></td>
                                            <td><input type="text" name="author" size="30" value="<?php echo stripslashes( $this->comment->author ); ?>" tabindex="1" id="name" /></td>
                                        </tr>
                                        <tr valign="top">
                                            <td class="first">
                                                <?php
                                                if ( $this->comment->author_email ) {
                                                    printf( __( 'E-mail (%s):' , 'recipe-press'), $this->comment->author_email, '', '' );
                                                } else {
                                                    _e( 'E-mail:' , 'recipe-press');
                                                }
                                                ?></td>
                                            <td><input type="text" name="author_email" size="30" value="<?php echo $this->comment->author_email; ?>" tabindex="2" id="email" /></td>
                                        </tr>
                                        <tr valign="top">
                                            <td class="first">
                                                <?php
                                                if ( ! empty( $this->comment->author_url ) && 'http://' != $this->comment->author_url ) {
                                                    $link = '<a href="' . $this->comment->author_url . '" rel="external nofollow" target="_blank">' . __('visit site', 'recipe-press') . '</a>';
                                                    printf( __( 'URL (%s):' , 'recipe-press'), apply_filters('get_author_link', $link ) );
                                                } else {
                                                    _e( 'URL:' , 'recipe-press');
                                                } ?></td>
                                            <td><input type="text" id="author_url" name="author_url" size="30" class="code" value="<?php echo stripslashes($this->comment->author_url); ?>" tabindex="3" /></td>
                                        </tr>
                                        <tr valign="top">
                                            <td class="first"><?php _e('Recipe', 'recipe-press'); ?></td>
                                            <td>
                                                <select name="recipe_id" id="recipe_id">
                                                    <?php echo $this->listOptions('recipes', $this->comment->recipe_id, 'id', 'title' ); ?>
                                                </select>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <br />
                            </div>
                        </div>

                        <div id="postdiv" class="postarea">
                            <?php the_editor(esc_attr(stripslashes($this->comment->content)), 'content', 'author_url', false, 4); ?>
                            <?php wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false ); ?>
                        </div>

                        <?php do_meta_boxes('comment', 'normal', $this->comment); ?>

                        <input type="hidden" name="c" value="<?php echo esc_attr($this->comment->id) ?>" />
                        <input name="referredby" type="hidden" id="referredby" value="<?php echo esc_url(esc_attr(wp_get_referer())); ?>" />
                        <?php wp_original_referer_field(true, 'previous'); ?>
                        <input type="hidden" name="noredir" value="1" />

                    </div>
                </div>
            </div>
        </div>
    </form>
    <?php include('footer.php'); ?>
</div>