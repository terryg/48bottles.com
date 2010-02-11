<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }
/**
 * edit_ingredient.php - View for the Edit Ingredient page.
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
    <h2><?php echo $this->pluginName; ?> &raquo; <?php _e('Edit Ingredient', 'recipe-press'); ?></h2>
    <form class="validate" action="<?php $this->adminURL(rp_Ingredients::menuName); ?>" method="post" id="editcat" name="editcat" enctype="multipart/form-data">
        <input type="hidden" value="update" name="action"/>
        <input type="hidden" value="<?php $this->ingredient_id(); ?>" name="id"/>
        <input type="hidden" value="<?php $this->ingredient_recipes(); ?>" name="recipes" />
        <?php
        if (function_exists('wpmu_create_blog'))
            wp_nonce_field('recipe-press-options');
        else
            wp_nonce_field('update-options');
        ?>
        <table class="form-table">
            <tbody>
                <tr class="form-field form-required">
                    <th valign="top" scope="row"><label for="name"><?php _e('Ingredient name', 'recipe-press'); ?></label></th>
                    <td colspan="2"><input type="text" aria-required="true" size="40" value="<?php $this->ingredient_name(); ?>" id="name" name="name"/></td>
                </tr>
                <tr class="form-field">
                    <th valign="top" scope="row"><label for="slug"><?php _e('Ingredient slug', 'recipe-press'); ?></label></th>
                    <td colspan="2">
                        <input type="text" size="40" value="<?php $this->ingredient_slug(); ?>" id="slug" name="slug"/>
                        <br/>
                        <?php _e('The "slug" is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.', 'recipe-press'); ?>
                    </td>
                </tr>
               <tr>
                    <th valign="top" scope="row"><label for="page"<?php _e('>Ingredient Page', 'recipe-press'); ?></label></th>
                    <td colspan="2">
                        <?php wp_dropdown_pages(array('name'=>'page', 'selected'=>$this->get_ingredient_page(), 'show_option_none'=>'None')); ?>
                        <label for="create-page"><input type="checkbox" name="create-page" value="1" /> <?php _e('Create New Page', 'recipe-press'); ?></label>
                    </td>
                </tr>
                <tr class="form-field ">
                    <th valign="top" scope="row"><label for="url"><?php _e('Ingredient URL', 'recipe-press'); ?></label></th>
                    <td colspan="2"><input type="text" aria-required="true" size="40" value="<?php $this->ingredient_url(); ?>" id="url" name="url"/></td>
                </tr>
            </tbody>
        </table>
        <p class="submit">
            <?php if (function_exists('wpmu_create_blog')) : ?>
            <input type="hidden" name="option_page" value="<?php echo rp_Categories::menuName; ?>" />
            <?php  else : ?>
            <input type="hidden" name="page_options" value="name,slug,description" />
            <?php endif; ?>
            <input type="submit" value="Update Ingredient" name="submit" class="button-primary"/>
        </p>
    </form>
    <?php include('footer.php'); ?>
</div>