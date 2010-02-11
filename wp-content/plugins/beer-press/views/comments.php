<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }
/**
 * comments.php - View for the Comments page.
 *
 * @package Recipe Press
 * @subpackage views
 * @author GrandSlambert
 * @copyright 2009-2010
 * @access public
 */

 /* Get Pagination Information */
$pagination = new rp_Pagination( array(
    'page'=>$this->getAdminURL(rp_Comments::menuName, array('author'=>$_GET['author'], 'comment'=>$_GET['comment'], 'recipe'=>$_GET['recipe'], 'status'=>$_GET['status'])),
    'paged'=>$_GET['show_page'],
    'limit'=>$this->getLimit($_GET['limit'])
    )
);

$comments = $this->getComments(array(
    'author'    =>  $_GET['author'],
    'comment'   =>  $_GET['comment'],
    'recipe'    =>$_GET['recipe'],
    'status'    => $_GET['status'],
    'pagination'=> &$pagination,
    )
);
?>

<div class="wrap">
    <div class="icon32" id="icon-recipe-press"><br/></div>
    <h2><?php echo $this->pluginName; ?> &raquo; <?php _e('Comments', 'recipe-press'); ?></h2>
    <div class="col-wrap">
        <?php if ( isset($this->message) ) : ?>
        <div class="updated fade below-h2" id="message" style="background-color: rgb(255, 251, 204);">
            <p><?php echo $this->message; ?></p>
        </div>
        <?php endif; ?>
        <form id="recipe_form" name="recipe_form" method="<?php $this->adminURL(rp_Comments::menuName); ?>">
            <table class="post fixed" width="100%">
                <tr>
                    <td class="rp_summary" width="49%">
                        <ul>
                            <li><a href="<?php $this->adminURL(rp_Comments::menuName); ?>"><?php _e('All', 'recipe-press'); ?> (<?php $this->showCount('comments'); ?>)</a></li>

                            <?php if ($this->getCount('comments', 'active') ) : ?>
                            <li>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php $this->adminURL(rp_Comments::menuName, array('status'=>'active', 'limit'=>$_GET['limit'])); ?>"><?php _e('Approved', 'recipe-press'); ?> (<?php $this->showCount('comments', 'active'); ?>)</a></li>
                            <?php endif; ?>

                            <?php if ($this->getCount('comments', 'pending') ) : ?>
                            <li>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php $this->adminURL(rp_Comments::menuName, array('status'=>'pending', 'limit'=>$_GET['limit'])); ?>"><?php _e('Pending Review', 'recipe-press'); ?> (<?php $this->showCount('comments', 'pending'); ?>)</a></li>
                            <?php endif; ?>

                            <?php if ($this->getCount('comments', 'trash') ) : ?>
                            <li>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php $this->adminURL(rp_Comments::menuName, array('status'=>'trash', 'limit'=>$_GET['limit'])); ?>"><?php _e('Trash', 'recipe-press'); ?> (<?php $this->showCount('comments', 'trash'); ?>)</a></li>
                            <?php endif; ?>

                            <?php if ($this->getCount('comments', 'spam') ) : ?>
                            <li>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php $this->adminURL(rp_Comments::menuName, array('status'=>'spam', 'limit'=>$_GET['limit'])); ?>"><?php _e('Spam', 'recipe-press'); ?> (<?php $this->showCount('comments', 'trash'); ?>)</a></li>
                            <?php endif; ?>
                        </ul>
                    </td>
                    <td class="rp_pagination" width="49%">
                        <?php $pagination->render(); ?>
                    </td>
                </tr>
            </table>
            <table class="widefat post fixed" cellspacing="0">
                <thead>
                    <tr>
                        <th><input name="author_search" type="text" id="author_search" value="<?php echo $_GET['author']; ?>" /></th>
                        <th><input name="comment_search" type="text" id="comment_search" value="<?php echo $_GET['comment']; ?>" /></th>
                        <th><select name="recipe_search" id="recipe_search" onchange="commentSearch()">
                                <option value="all">All</option>
                                <?php echo $this->listOptions('recipes', $_GET['recipe'], 'id', 'title'); ?>
                            </select>
                        </th>
                        <th><select name="status_search" id="status_search" onChange="ingredientSearch()">
                                <option value="all"><?php _e('All', 'recipe-press'); ?></option>
                                <option value="pending" <?php selected($_GET['status'], 'pending'); ?> ><?php _e('Pending Review', 'recipe-press'); ?></option>
                                <option value="active" <?php selected($_GET['status'], 'active'); ?> ><?php _e('Approved', 'recipe-press'); ?></option>
                                <option value="trash" <?php selected($_GET['status'], 'trash'); ?> ><?php _e('Trash', 'recipe-press'); ?></option>
                                <option value="spam" <?php selected($_GET['status'], 'spam'); ?> ><?php _e('Spam', 'recipe-press'); ?></option>
                            </select>

                            <input name="filter" type="button" id="filter" onclick="commentSearch()" value="<?php _e('Filter', 'recipe-press'); ?>" />
                        </th>
                    </tr>
                    <tr>
                        <th><?php _e('Author', 'recipe-press'); ?></th>
                        <th><?php _e('Comment', 'recipe-press'); ?></th>
                        <th><?php _e('In Response To', 'recipe-press'); ?></th>
                        <th><?php _e('Status', 'recipes-press'); ?></th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th><?php _e('Author', 'recipe-press'); ?></th>
                        <th><?php _e('Comment', 'recipe-press'); ?></th>
                        <th><?php _e('In Response To', 'recipe-press'); ?></th>
                        <th><?php _e('Status', 'recipes-press'); ?></th>
                    </tr>
                </tfoot>
                <tbody id="the-comment-list" class="list:comment">
                    <?php
                    foreach ($comments as $comment) : ?>
                    <tr id="rp_comment_<?php echo $comment->id; ?>" class="<?php echo $this->rowClass($comment->status); ?>">
                        <td id="rp_comment_author_<?php echo $comment->id; ?>" class="author column-author"><?php echo stripslashes($comment->author); ?></td>
                        <td id="rp_comment_comment_<?php echo $comment->id; ?>" class="comment column-comment"><?php echo stripslashes($comment->content); ?><br/>
                            <div class="row-actions">
                                <span id="rp_comment_approve_<?php echo $comment->id; ?>" style="display:<?php if ($comment->status == 'active') echo 'none'; ?>" class="rp-approve"><a href="<?php $this->adminURL(rp_Comments::menuName, array('action'=>'unapprove', 'id'=>$comment->id)); ?>" onclick="return onClickRPComment('<?php echo $comment->id; ?>','approve')">Approve</a> | </span>
                                <span id="rp_comment_unapprove_<?php echo $comment->id; ?>" style="display:<?php if ($comment->status == 'pending' or $comment->status == 'spam') echo 'none'; ?>" class="rp-unapprove"><a href="<?php $this->adminURL(rp_Comments::menuName, array('action'=>'approve', 'id'=>$comment->id)); ?>" onclick="return onClickRPComment('<?php echo $comment->id; ?>','unapprove')">Unapprove</a> |</span>
                                <span id="rp_comment_spam_<?php echo $comment->id; ?>" style="display:<?php if ($comment->status == 'spam') echo 'none'; ?>" class="rp-spam"><a href="<?php $this->adminURL(rp_Comments::menuName, array('action'=>'spam', 'id'=>$comment->id)); ?>" onclick="return onClickRPComment('<?php echo $comment->id; ?>','spam')">Spam</a> | </span>
                                <span class="rp-edit"><a href="<?php $this->adminURL(rp_Comments::menuName, array('action'=>'edit', 'id'=>$comment->id)); ?>"><?php _e('Edit', 'recipe-press'); ?></a> | </span>
                                <span class="rp-delete">
                                    <a href="<?php $this->adminURL(rp_Comments::menuName, array('action'=>'trash', 'id'=>$comment->id)); ?>"
                                       onclick="return confirm('<?php echo esc_js(sprintf(__('Warning: You are about to delete the comment by "%1$s" on the "%2$s" recipe. Are you sure you want to do this?'), $comment->author, $comment->recipe_title)); ?>')"
                                       ><?php _e('Trash', 'recipe-press'); ?>
                                    </a>
                                </span>
                            </div>
                        </td>
                        <td id="rp_comment_recipe_<?php echo $comment->id; ?>" class="recipe column-recipe"><?php echo stripslashes($comment->recipe_title); ?></td>
                        <td id="rp_comment_status_<?php echo $comment->id; ?>" class="status column-status"><?php echo ucfirst($comment->status); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </form>
    </div>
    <?php include('footer.php'); ?>
</div>