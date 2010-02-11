<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }
/**
 * edit_category.php - View for the Edit Category page.
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
    <h2><?php echo $this->pluginName; ?> &raquo; <?php _e('Edit Category', 'recipe-press'); ?></h2>
    <form class="validate" action="<?php $this->adminURL(rp_Categories::menuName); ?>" method="post" id="editcat" name="editcat" enctype="multipart/form-data">
        <input type="hidden" value="update" name="action"/>
        <input type="hidden" value="<?php $this->category_id(); ?>" name="id"/>
        <?php
        if (function_exists('wpmu_create_blog'))
            wp_nonce_field('recipe-press-options');
        else
            wp_nonce_field('update-options');
        ?>
        <table class="form-table">
            <tbody>
                <tr class="form-field form-required">
                    <th valign="top" scope="row"><label for="name"><?php _e('Category name', 'recipe-press'); ?></label></th>
                    <td colspan="2"><input type="text" aria-required="true" size="40" value="<?php $this->category_name(); ?>" id="name" name="name"/></td>
                </tr>
                <tr class="form-field">
                    <th valign="top" scope="row"><label for="slug"><?php _e('Category slug', 'recipe-press'); ?></label></th>
                    <td colspan="2">
                        <input type="text" size="40" value="<?php $this->category_slug(); ?>" id="slug" name="slug"/>
                        <br/>
                        <?php _e('The "slug" is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.', 'recipe-press'); ?>
                    </td>
                </tr>
                <tr>
                    <th valign="top" scope="row"><label for="image"><?php _e('Image File', 'recipe-press'); ?></label></th>
                    <td valign="top">
                        <input type="file" name="image" value="" /><br />
                        <?php _e('Leave this field blank to keep the existing image', 'recip-press'); ?><br />
                        <label>
                            <input type="checkbox" name="delete_existing" value="1" <?php if ($this->get_category_media_id() > 0) echo 'checked="checked"'; ?> />
                            <?php _e('Delete existing image?', 'recipe-press'); ?>
                        </label>
                        <input type="hidden" name="old_media_id" value="<?php $this->category_media_id(); ?>" />

                    </td>
                    <td>
                        <?php if ($this->get_category_media_id()) : ?>
                        <input type="hidden" name="media_id" value="<?php $this->category_media_id(); ?>" id="media_id" />
                            <?php echo wp_get_attachment_image( $this->get_category_media_id() ); ?>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th valign="top" scope="row"><label for="parent"<?php _e('>Category Parent', 'recipe-press'); ?></label></th>
                    <td colspan="2">
                        <select name="parent">
                            <option value="0">None</option>
                            <?php echo $this->listOptions('categories', $this->get_category_parent()); ?>
                        </select>
                    </td>
                </tr>
                <tr class="form-field">
                    <th valign="top" scope="row"><label for="description"><?php _e('Description (optional)', 'recipe-press'); ?></label></th>
                    <td colspan="2"><textarea style="width: 97%;" cols="50" rows="5" id="description" name="description"><?php $this->category_description(); ?></textarea></td>
                </tr>
            </tbody>
        </table>
        <p class="submit">
            <?php if (function_exists('wpmu_create_blog')) : ?>
            <input type="hidden" name="option_page" value="<?php echo rp_Categories::menuName; ?>" />
            <?php  else : ?>
            <input type="hidden" name="page_options" value="name,slug,description" />
            <?php endif; ?>
            <input type="submit" value="Update Category" name="submit" class="button-primary"/>
        </p>
    </form>
    <?php include('footer.php'); ?>
</div>