<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }
/**
 * recipes.php - View for the Recipes page.
 *
 * @package Recipe Press
 * @subpackage views
 * @author GrandSlambert
 * @copyright 2009-2010
 * @access public
 */

/* Get Pagination Information */
$pagination = new rp_Pagination( array(
    'page'=>$this->getAdminURL(rp_Recipes::menuName, array('recipe'=>$_GET['recipe'], 'slug'=>$_GET['slug'], 'category'=>$_GET['category'], 'user'=>$_GET['user'], 'status'=>$_GET['status'])),
    'paged'=>$_GET['show_page'],
    'limit'=>$this->getLimit($_GET['limit'])
    )
);

/* Get all the recipes to displayt */
$recipes = $this->getRecipes(array(
    'recipe'    => $_GET['recipe'],
    'slug'      => $_GET['slug'],
    'category'  => $_GET['category'],
    'user'      => $_GET['user'],
    'status'    => $_GET['status'],
    'orderby'   => $_GET['orderby'],
    'pagination'=> &$pagination,
    )
);

?>

<div class="wrap">
    <div class="icon32" id="icon-recipe-press"><br/></div>
    <h2><?php echo $this->pluginName; ?> &raquo; <?php _e('Recipes', 'recipe-press'); ?></h2>
    <div class="col-wrap">
        <?php if ( isset($this->message) ) : ?>
        <div class="updated fade below-h2" id="message" style="background-color: rgb(255, 251, 204);">
            <p><?php echo $this->message; ?></p>
        </div>
        <?php endif; ?>
        <form id="recipe_form" name="recipe_form" method="<?php $this->adminURL(rp_Recipes::menuName); ?>">
            <table class="post fixed" width="100%">
                <tr>
                    <td class="rp_summary" width="49%">
                        <ul>
                            <li><a href="<?php $this->adminURL(rp_Recipes::menuName); ?>"><?php _e('All', 'recipe-press'); ?> (<?php $this->showCount('recipes'); ?>)</a></li>

                            <?php if ($this->getCount('recipes', 'active') ) : ?>
                            <li>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php $this->adminURL(rp_Recipes::menuName, array('status'=>'active', 'limit'=>$_GET['limit'])); ?>"><?php _e('Published', 'recipe-press'); ?> (<?php $this->showCount('recipes', 'active'); ?>)</a></li>
                            <?php endif; ?>

                            <?php if ($this->getCount('recipes', 'pending') ) : ?>
                            <li>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php $this->adminURL(rp_Recipes::menuName, array('status'=>'pending', 'limit'=>$_GET['limit'])); ?>"><?php _e('Pending Review', 'recipe-press'); ?> (<?php $this->showCount('recipes', 'pending'); ?>)</a></li>
                            <?php endif; ?>

                            <?php if ($this->getCount('recipes', 'trash') ) : ?>
                            <li>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php $this->adminURL(rp_Recipes::menuName, array('status'=>'trash', 'limit'=>$_GET['limit'])); ?>"><?php _e('Trash', 'recipe-press'); ?> (<?php $this->showCount('recipes', 'trash'); ?>)</a></li>
                            <?php endif; ?>

                            <li>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php $this->adminURL(rp_Recipes::menuName, array('action'=>'import', 'limit'=>$_GET['limit'])); ?>"><?php _e('Import Recipes', 'recipe-press'); ?></a></li>
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
                        <th style="display:none" class="manage-column column-cb check-column" id="cb" scope="col">&nbsp;</th>
                        <th><input name="recipe_search" type="text" id="recipe_search" value="<?php echo $_GET['recipe']; ?>" /></th>
                        <th><input name="slug_search" type="text" id="slug_search" value="<?php echo $_GET['slug']; ?>" /></th>
                        <th><?php if ($this->superUser()) wp_dropdown_users(array('selected'=>$_GET['user'], 'name'=>'user_search', 'show_option_all'=>'All')); ?></th>
                        <th> <select name="category_search" id="category_search" onchange="recipeSearch()">
                                <option value="all">All</option>
                                <?php echo $this->listOptions('categories', $_GET['category']); ?>
                            </select>
                        </th>
                        <th><select name="status_search" id="status_search" onChange="recipeSearch()">
                                <option value="all"><?php _e('All', 'recipe-press'); ?></option>
                                <option value="draft" <?php selected($_GET['status'], 'draft'); ?> ><?php _e('Draft', 'recipe-press'); ?></option>
                                <option value="pending" <?php selected($_GET['status'], 'pending'); ?> ><?php _e('Pending Review', 'recipe-press'); ?></option>
                                <option value="active" <?php selected($_GET['status'], 'active'); ?> ><?php _e('Published', 'recipe-press'); ?></option>
                                <option value="trash" <?php selected($_GET['status'], 'trash'); ?> ><?php _e('Trash', 'recipe-press'); ?></option>
                            </select>
                        </th>
                        <th><input name="filter" type="button" id="filter" onclick="recipeSearch()" value="<?php _e('Filter', 'recipe-press'); ?>" /></th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                    </tr>
                    <tr>
                        <th style="display:none" class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"/></th>
                        <th><a href="<?php $this->adminURL(rp_Recipes::menuName, array('action'=>'list', 'orderby'=>'name')); ?>"><?php _e('Recipe Name', 'recipe-press'); ?></a></th>
                        <th><a href="<?php $this->adminURL(rp_Recipes::menuName, array('action'=>'list', 'orderby'=>'slug')); ?>"><?php _e('Slug', 'recipe-press'); ?></a></th>
                        <th><?php _e('Contributor', 'recipe-press'); ?></th>
                        <th><?php _e('Category', 'recipe-press'); ?></th>
                        <th><a href="<?php $this->adminURL(rp_Recipes::menuName, array('action'=>'list', 'orderby'=>'status')); ?>"><?php _e('Status', 'recipe-press'); ?></a></th>
                        <th><a href="<?php $this->adminURL(rp_Recipes::menuName, array('action'=>'list', 'orderby'=>'views_total')); ?>"><?php _e('Views', 'recipe-press'); ?></a></th>
                        <th><a href="<?php $this->adminURL(rp_Recipes::menuName, array('action'=>'list', 'orderby'=>'comment_total')); ?>"><?php _e('Comments', 'recipe-press'); ?></a></th>
                        <th><?php _e('Date', 'recipe-press'); ?></th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th style="display:none" class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"/></th>
                        <th><?php _e('Recipe Name', 'recipe-press'); ?></th>
                        <th><?php _e('Slug', 'recipe-press'); ?></th>
                        <th><?php _e('Contributor', 'recipe-press'); ?></th>
                        <th><?php _e('Category', 'recipe-press'); ?></th>
                        <th><?php _e('Status', 'recipe-press'); ?></th>
                        <th><?php _e('Views', 'recipe-press'); ?></th>
                        <th><?php _e('Comments', 'recipe-press'); ?></th>
                        <th><?php _e('Date', 'recipe-press'); ?></th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php foreach ($recipes as $recipe) : ?>
                    <tr class="<?php echo $recipe->status; ?>">
                        <th style="display:none" class="check-column" scope="row"><input type="checkbox" value="<?php echo $recipe->id; ?>" name="linkcheck[]"/></th>
                        <td class="name column-name">
                            <a href="<?php $this->adminURL(rp_Recipes::menuName, array('action'=>'edit', 'id'=>$recipe->id)); ?>">
                                    <?php echo wp_get_attachment_image($recipe->media_id, array(32,32)); ?>
                                    <?php echo esc_attr(stripslashes_deep($recipe->title)); ?>
                            </a><br />
                            <div class="row-actions">
                                <span class="edit"><a href="<?php $this->adminURL(rp_Recipes::menuName, array('action'=>'edit', 'id'=>$recipe->id)); ?>"><?php _e('Edit', 'recipe-press'); ?></a></span>
                                <span class="view"> | <a href="<?php $this->recipeLink($recipe); ?>" target="_blank"><?php _e('View', 'recipe-press'); ?></a></span>
                                <!--<span class="inline hide-if-no-js"><a class="editinline" href="#"><?php _e('Quick Edit', 'recipe-press'); ?></a> | </span>-->

                                    <?php if ($this->recipe->status != 'trash') : ?>
                                <span class="delete">
                                    | <a href="<?php $this->adminURL(rp_Recipes::menuName, array('action'=>'trash', 'id'=>$recipe->id)); ?>" class="delete:the-list:link-cat-9 submitdelete" onclick="
                                        return confirm('<?php echo esc_js(sprintf(__('Warning: You are about to delete the "%1$s" recipe. Any posts or pages with short codes for this recipe will now appear blank. Are you sure?'), $recipe->title));?>')"><?php _e('Trash', 'recipe-press'); ?></a>
                                </span>
                                    <?php endif; ?>

                            </div>
                        </td>
                        <td class="slug column-slug"><?php echo $recipe->slug; ?></td>
                        <td class="user column-user"><?php echo $this->displayUser($recipe->user_id); ?></td>
                        <td class="category column-category"><?php echo esc_attr(stripslashes_deep($recipe->category_name)); ?></td>
                        <td class="status column-status"><?php echo ucfirst($recipe->status); ?></td>
                        <td class="views column-views num"><?php echo $recipe->views_total; ?></td>
                        <td class="comments column-comments num"><?php echo $recipe->comment_total; ?></td>
                        <td class="recipes column-recipes num"><?php echo date("F d, Y", strtotime($recipe->added)); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </form>
    </div>
    <?php include('footer.php'); ?>
</div>