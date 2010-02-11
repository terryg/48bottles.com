<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }
/**
 * ingredients.php - View for the Ingredients page.
 *
 * @package Recipe Press
 * @subpackage views
 * @author GrandSlambert
 * @copyright 2009-2010
 * @access public
 */
/* Get Pagination Information */
$pagination = new rp_Pagination( array(
    'page'  => $this->getAdminURL(rp_Ingredients::menuName, array('name'=>$_GET['name'], 'slug'=>$_GET['slug'], 'page_link'=>$_GET['page_link'], 'url'=>$_GET['url'], 'status'=>$_GET['status'])),
    'paged' => $_GET['show_page'],
    'limit' => $this->getLimit($_GET['limit'])));

$ingredients = $this->getRecords('ingredients', array(
    'name'      =>$_GET['name'],
    'slug'      =>$_GET['slug'],
    'page'      =>$_GET['page_link'],
    'url'       =>$_GET['url'],
    'status'    => $_GET['status'],
    'pagination'=> &$pagination,
    )
);
?>

<div class="wrap">
    <div class="icon32" id="icon-recipe-press"><br/></div>
    <h2><?php echo $this->pluginName; ?> &raquo; <?php _e('Ingredients', 'recipe-press'); ?></h2>
    <div class="col-wrap">
        <?php if ( isset($this->message) ) : ?>
        <div class="updated fade below-h2" id="message" style="background-color: rgb(255, 251, 204);">
            <p><?php echo $this->message; ?></p>
        </div>
        <?php endif; ?>
        <form id="ingredient_form" name="ingredient_form"  method="<?php $this->adminURL(rp_Ingredients::menuName); ?>">
            <table class="post fixed" width="100%">
                <tr>
                    <td class="rp_summary" width="49%">
                        <ul>
                            <li><a href="<?php $this->adminURL(rp_Ingredients::menuName); ?>"><?php _e('All', 'recipe-press'); ?> (<?php $this->showCount('ingredients'); ?>)</a></li>

                            <?php if ($this->getCount('ingredients', 'active') ) : ?>
                            <li>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php $this->adminURL(rp_Ingredients::menuName, array('status'=>'active', 'limit'=>$_GET['limit'])); ?>"><?php _e('Published', 'recipe-press'); ?> (<?php $this->showCount('ingredients', 'active'); ?>)</a></li>
                            <?php endif; ?>

                            <?php if ($this->getCount('ingredients', 'pending') ) : ?>
                            <li>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php $this->adminURL(rp_Ingredients::menuName, array('status'=>'pending', 'limit'=>$_GET['limit'])); ?>"><?php _e('Pending Review', 'recipe-press'); ?> (<?php $this->showCount('ingredients', 'pending'); ?>)</a></li>
                            <?php endif; ?>

                            <?php if ($this->getCount('ingredients', 'trash') ) : ?>
                            <li>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php $this->adminURL(rp_Ingredients::menuName, array('status'=>'trash', 'limit'=>$_GET['limit'])); ?>"><?php _e('Trash', 'recipe-press'); ?> (<?php $this->showCount('ingredients', 'trash'); ?>)</a></li>
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
                        <th style="display:none" class="manage-column column-cb check-column" id="cb" scope="col">&nbsp;</th>
                        <th><input name="name_search" type="text" id="name_search" value="<?php echo $_GET['name']; ?>" /></th>
                        <th><input name="slug_search" type="text" id="slug_search" value="<?php echo $_GET['slug']; ?>" /></th>
                        <th><?php wp_dropdown_pages(array('name'=>'page_search', 'selected'=>$_GET['page_link'], 'show_option_none'=>'None')); ?></th>
                        <th><input name="url_search" type="text" id="url_search" value="<?php echo $_GET['url']; ?>" /></th>
                        <th><select name="status_search" id="status_search" onChange="ingredientSearch()">
                                <option value="all"><?php _e('All', 'recipe-press'); ?></option>
                                <option value="pending" <?php selected($_GET['status'], 'pending'); ?> ><?php _e('Pending Review', 'recipe-press'); ?></option>
                                <option value="active" <?php selected($_GET['status'], 'active'); ?> ><?php _e('Published', 'recipe-press'); ?></option>
                                <option value="trash" <?php selected($_GET['status'], 'trash'); ?> ><?php _e('Trash', 'recipe-press'); ?></option>
                            </select>
                        </th>
                        <th style="width:75px;"><input name="filter" type="button" id="filter" onclick="ingredientSearch()" value="<?php _e('Filter', 'recipe-press'); ?>" /></th>
                    </tr>
                    <tr>
                        <th style="display:none" class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"/></th>
                        <th><?php _e('Ingredient Name', 'recipe-press'); ?></th>
                        <th><?php _e('Slug', 'recipe-press'); ?></th>
                        <th><?php _e('Page', 'recipe-press'); ?></th>
                        <th><?php _e('URL', 'recipe-press'); ?></th>
                        <th><?php _e('Status', 'recipe-press'); ?></th>
                        <th style="width:75px;"><?php _e('Recipes', 'recipe-press'); ?></th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th style="display:none" class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"/></th>
                        <th><?php _e('Ingredient Name', 'recipe-press'); ?></th>
                        <th><?php _e('Slug', 'recipe-press'); ?></th>
                        <th><?php _e('Page', 'recipe-press'); ?></th>
                        <th><?php _e('URL', 'recipe-press'); ?></th>
                        <th><?php _e('Status', 'recipe-press'); ?></th>
                        <th style="width:75px;"><?php _e('Recipes', 'recipe-press'); ?></th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php
                    foreach ($ingredients as $this->ingredient) : ?>
                    <tr class="<?php echo $ingredient->status; ?>">
                        <th style="display:none" class="check-column" scope="row"><input type="checkbox" value="<?php echo $ingredient->id; ?>" name="linkcheck[]"/></th>
                        <td class="name column-name">
                            <a href="<?php $this->adminURL(rp_ingredients::menuName, array('action'=>'edit', 'id'=>$this->get_ingredient_id())); ?>">
                                    <?php // echo wp_get_attachment_image($ingredient->media_id, array(32,32)); ?>
                                    <?php $this->ingredient_name(); ?>
                            </a><br />
                            <div class="row-actions">
                                <span class="edit"><a href="<?php $this->adminURL(rp_Ingredients::menuName, array('action'=>'edit', 'id'=>$this->get_ingredient_id())); ?>"><?php _e('Edit', 'recipe-press'); ?></a> | </span>
                                
                                <?php if ($this->ingredient->status != 'trash') : ?>
                                <span class="delete">
                                    <a href="<?php $this->adminURL(rp_Ingredients::menuName, array('action'=>'trash', 'id'=>$this->get_ingredient_id())); ?>" class="delete:the-list:link-cat-9 submitdelete"
                                       onclick="return confirm('<?php echo esc_js(sprintf(__('Warning: You are about to delete the "%1$s" ingredient. This will only delete this from the ingredients table and not from individual recipes. Note: This ingredient could be added back to this list when the ingredient list regnerates. Are you sure?'), $this->get_ingredient_name())); ?>')"
                                       ><?php _e('Trash', 'recipe-press'); ?>
                                    </a>
                                </span>
                                <?php else : ?>
                                <span class="delete">
                                    <a href="<?php $this->adminURL(rp_Ingredients::menuName, array('action'=>'activate', 'status'=>$_GET['status'], 'id'=>$this->get_ingredient_id())); ?>" class="delete:the-list:link-cat-9 submitdelete"
                                       onclick="return confirm('<?php echo esc_js(sprintf(__('Are you sure you want to activate the "%1$s" ingredient?'), $this->get_ingredient_name())); ?>')"
                                       ><?php _e('Activate', 'recipe-press'); ?>
                                    </a>
                                </span>

                                <?php endif; ?>

                            </div>
                        </td>
                        <td class="slug column-slug"><?php $this->ingredient_slug(); ?></td>
                        <td class="page column-page"><?php echo $this->displayPage($this->get_ingredient_page()); ?></td>
                        <td class="URL column-url"><?php $this->ingredient_url(true); ?></td>
                        <td class="status column-status"><?php echo ucfirst($this->ingredient->status); ?></td>
                        <td class="recipes column-recipes" style="width:75px;"><?php $this->ingredient_recipes(true); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </form>
    </div>
    <?php include('footer.php'); ?>
</div>