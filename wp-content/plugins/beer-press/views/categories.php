<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }
/**
 * categories.php - View for the Categories page.
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
    <h2><?php echo $this->pluginName; ?> &raquo; <?php _e('Categories', 'recipe-press'); ?></h2>
    <?php if ( isset($this->message) ) : ?>
    <div class="updated fade below-h2" id="message" style="background-color: rgb(255, 251, 204);">
        <p><?php echo $this->message; ?></p>
    </div>
    <?php endif; ?>

    <div id="col-container">
        <div id="col-right">
            <div class="col-wrap">
                <form id="posts-filter" method="get" action="<?php  $this->adminURL(rp_Categories::menuName); ?>">
                    <h3><?php _e('Existing Categories', 'recipe-press'); ?></h3>
                    <table class="widefat fixed" cellspacing="0">
                        <thead>
                            <tr>
                                <th><?php _e('Name', 'recipe-press'); ?></th>
                                <th><?php _e('Slug', 'recipe-press'); ?></th>
                                <th><?php _e('Recipes', 'recipe-press'); ?></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th><?php _e('Name', 'recipe-press'); ?></th>
                                <th><?php _e('Slug', 'recipe-press'); ?></th>
                                <th><?php _e('Recipes', 'recipe-press'); ?></th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php
                            $cats = $this->getRecordsCascade('categories', array('order-by'=>'name'));
                            foreach ($cats as $cat) : ?>
                            <tr id="recipe-cat-<?php echo $cat->id; ?>" class="iedit">
                                <td class="name column-name">
                                    <a href="<?php $this->adminURL(rp_Categories::menuName, array('action'=>'edit', 'id'=>$cat->id) ); ?>">
                                            <?php echo wp_get_attachment_image($cat->media_id, array(32,32)); ?>

                                            <?php for ($ctr = 1; $ctr < $cat->level; ++$ctr) : ?>
                                        &mdash;
                                            <?php endfor; ?>
                                            <?php echo esc_attr(stripslashes_deep($cat->name)); ?>
                                    </a><br />
                                    <div class="row-actions">
                                        <span class="edit"><a href="<?php $this->adminURL(rp_Categories::menuName, array('action'=>'edit', 'id'=>$cat->id) ); ?>"><?php _e('Edit', 'recipe-press'); ?></a></span>
                                        <!--<span class="inline hide-if-no-js"> | <a class="editinline" href="#">Quick Edit</a> | </span>-->
                                            <?php if ($cat->id != $this->options['default-category']) : ?>
                                        <span class="delete"> | 
                                            <a href="<?php $this->adminURL(rp_Categories::menuName, array('action'=>'delete', 'id'=>$cat->id) ); ?>"
                                               onclick="return confirm('<?php echo esc_js(sprintf(__('Warning: You are about to delete the "%1$s" category. Any recipes in this category will be moved to the default category. Are you sure?'), $cat->name));?>')"
                                               class="delete:the-list:link-cat-9 submitdelete"><?php _e('Delete', 'recipe-press'); ?>
                                            </a>
                                        </span>
                                            <?php else : ?>
                                        <span class="delete"> | <?php _e('Default', 'recipe-press'); ?> </span>
                                            <?php endif; ?>
                                    </div></td>
                                <td class="slug column-slug"><?php echo $cat->slug; ?></td>
                                <td class="links column-links num"><?php $this->showCount('recipes', 'all', $cat->id); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
        <div id="col-left">
            <div class="col-wrap">
                <div class="form-wrap">
                    <h3><?php _e('Add Recipe Category', 'recipe-press'); ?></h3>
                    <form action="<?php $this->adminURL(rp_Categories::menuName); ?>" method="post" class="add:the-list: validate" id="addcat" name="addcat"  enctype="multipart/form-data">
                        <input type="hidden" value="add" name="action"/>
                        <?php
                        if (function_exists('wpmu_create_blog'))
                            wp_nonce_field('wp_recipe-options');
                        else
                            wp_nonce_field('update-options');
                        ?>
                        <div class="form-field form-required">
                            <label for="name"><?php _e('Category name', 'recipe-press'); ?></label>
                            <input type="text" aria-required="true" size="40" value="" id="name" name="name"/>
                        </div>
                        <div class="form-field">
                            <label for="slug"><?php _e('Category slug', 'recipe-press'); ?></label>
                            <input type="text" size="40" value="" id="slug" name="slug"/>
                            <p><?php _e('The "slug" is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.', 'recipe-press'); ?></p>
                        </div>
                        <div class="form-field">
                            <label for="image"><?php _e('Image File', 'recipe-press'); ?></label>
                            <input type="file" name="image" value="" />
                        </div>
                        <div class="form-field">
                            <label for="parent"><?php _e('Parent', 'recipe-press'); ?></label>
                            <select name="parent">
                                <option value="0"><?php ('None'); ?></option>
                                <?php echo $this->listOptions('categories'); ?>
                            </select>
                            <p><?php _e('Categories have a hierarchy that you create.', 'recipe-press'); ?></p>
                        </div>
                        <div class="form-field">
                            <label for="description"><?php _e('Description (optional)', 'recipe-press'); ?></label>
                            <textarea cols="40" rows="5" id="description" name="description"> </textarea>
                        </div>
                        <p class="submit">
                            <?php if (function_exists('wpmu_create_blog')) : ?>
                            <input type="hidden" name="option_page" value="<?php echo rp_Categories::menuName; ?>" />
                            <?php  else : ?>
                            <input type="hidden" name="page_options" value="name,slug,description" />
                            <?php endif; ?>
                            <input type="submit" value="<?php _e('Add Category', 'recipe-press'); ?>" name="submit" class="button"/>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php include('footer.php'); ?>
</div>