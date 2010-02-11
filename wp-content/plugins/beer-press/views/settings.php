<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }
/**
 * settings.php - View for the Settings page.
 *
 * @package Recipe Press
 * @subpackage views
 * @author GrandSlambert
 * @copyright 2009-2010
 * @access public
 */
?>

<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;" class="overDiv"></div>
<div class="wrap">
    <form method="post" action="options.php" id="recipe_press_settings">
        <div class="icon32" id="icon-recipe-press"><br/></div>
        <h2><?php echo $this->pluginName; ?> &raquo; <?php _e('Plugin Settings', 'recipe-press'); ?> </h2>
        <?php settings_fields($this->optionsName); ?>
        <div style="width:49%; float:left">
            <div class="postbox">
                <h3 class="handl" style="margin:0;padding:3px;cursor:default;">
                    <?php _e('Display Settings', 'recipe-press'); ?>
                </h3>
                <div class="table">
                    <table class="form-table">
                        <tbody>
                            <tr align="top">
                                <th scope="row"><label for="recipe_press_display_page"><?php _e('Recipe List Page', 'recipe-press'); ?></label></th>
                                <td>
                                    <?php wp_dropdown_pages(array('name'=>$this->optionsName.'[display-page]', 'selected'=>$this->options['display-page'], 'show_option_none'=>'None')); ?>
                                    <?php $this->remind(__('Remember to add the [recipe-list] short code to this page. This requirement may be removed in future versions.', 'recipe-press')); ?>
                                </td>
                            </tr>
                            <tr align="top">
                                <th scope="row"><label for="recipe_press_custom_css"><?php _e('Use Plugin CSS?', 'recipe-press'); ?></label></th>
                                <td>
                                    <input name="<?php echo $this->optionsName; ?>[custom-css]" id="recipe_press_custom_css" type="checkbox" value="1" <?php checked($this->options['custom-css'], 1); ?> />
                                    <?php $this->help(__('Click this option to include the CSS from the plugin.', 'recipe-press')); ?>
                                </td>
                            </tr>
                            <tr align="top">
                                <th scope="row"><label for="recipe_press_hours_text"><?php _e('"Hours" Text', 'recipe-press'); ?></label></th>
                                <td>
                                    <input type="text" name="<?php echo $this->optionsName; ?>[hour-text]" id="recipe_press_hours_text" value="<?php echo $this->options['hour-text']; ?>" />
                                    <?php $this->help(__('This is the text that will be used in the ready time box if more than 60 minutes is required. Leave as a singular word.', 'recipe-press')); ?>
                                </td>
                            </tr>
                            <tr align="top">
                                <th scope="row"><label for="recipe_press_minute_text"><?php _e('"Minutes" Text', 'recipe-press'); ?></label></th>
                                <td>
                                    <input type="text" name="<?php echo $this->optionsName; ?>[minute-text]" id="recipe_press_minute_text" value="<?php echo $this->options['minute-text']; ?>" />
                                    <?php $this->help(__('This is the text that will be used in the ready time box if more than 60 minutes is required. Leave as a singular word.', 'recipe-press')); ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="postbox">
                <div class="table">
                    <h3 class="handl" style="margin:0;padding:3px;cursor:default;">
                        <?php _e('Recipe Form Settings', 'recipe-press'); ?>
                    </h3>
                    <table class="form-table">
                        <tbody>
                            <tr align="top">
                                <th scope="row"><label for="recipe_press_submit_page"><?php _e('User Submit Page', 'recipe-press'); ?></label></th>
                                <td>
                                    <?php wp_dropdown_pages(array('name'=>$this->optionsName . '[submit-page]', 'selected'=>$this->options['submit-page'], 'show_option_none'=>'None')); ?>
                                    <?php $this->help(__('Note: The user form will be added to this page automatically, no code needed.', 'recipe-press')); ?>
                                </td>
                            </tr>
                            <tr align="top">
                                <th scope="row"><label for="recipe_press_submit_location"><?php _e('Where to add the form', 'recipe-press'); ?></label></th>
                                <td>
                                    <select name="<?php echo $this->optionsName; ?>[submit-location]" id="recipe_press_submit_location">
                                        <option value="before" <?php selected($this->options['submit-location'], 'before'); ?>><?php _e('Before the Content', 'recipe-press'); ?></option>
                                        <option value="after" <?php selected($this->options['submit-location'], 'after'); ?>><?php _e('After the Content', 'recipe-press'); ?></option>
                                        <option value="replace" <?php selected($this->options['submit-location'], 'replace'); ?>><?php _e('Replace all Content with Form', 'recipe-press'); ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr align="top">
                                <th scope="row"><label for="recipe_press_submit_title"><?php _e('User Submit Link Title', 'recipe-press'); ?></label></th>
                                <td>
                                    <input type="text" name="<?php echo $this->optionsName; ?>[submit-title]" id="recipe_press_submit_title" value="<?php echo $this->options['submit-title']; ?>" />
                                    <?php $this->help(__('Used in the widgets when adding a link to the submit form.', 'recipe-press')); ?>
                                </td>
                            </tr>
                            <tr align="top">
                                <th scope="row"><label for="recipe_press_new_recipe_status"><?php _e('Status for User Recipes', 'recipe-press'); ?></label></th>
                                <td>
                                    <select name="<?php echo $this->optionsName; ?>[new-recipe-status]" id="recipe_press_new_recipe_status">
                                        <option value="draft" <?php selected($this->options['new-recipe-status'], 'draft'); ?> ><?php _e('Draft', 'recipe-press'); ?></option>
                                        <option value="pending" <?php selected($this->options['new-recipe-status'], 'pending'); ?> ><?php _e('Pending Review', 'recipe-press'); ?></option>
                                        <option value="active" <?php selected($this->options['new-recipe-status'], 'active'); ?> ><?php _e('Published', 'recipe-press'); ?></option>
                                    </select>
                                    <?php $this->help(__('Select the default recipe status for new user submitted recipes.', 'recipe-press')); ?>
                                </td>
                            </tr>
                            <tr align="top">
                                <th scope="row"><label for="recipe_press_new_recipe_status"><?php _e('Template for User Recipes', 'recipe-press'); ?></label></th>
                                <td>
                                    <?php $this->recipe_templates($this->options['new-recipe-template'], false, $this->optionsName.'[new-recipe-template]', 'new_recipe_template'); ?>
                                    <?php $this->help(__('Select the default recipe template to be used in the user submit form.', 'recipe-press')); ?>
                                </td>
                            </tr>
                            <tr align="top">
                                <th scope="row"><label for="recipe_press_form_type"><?php _e('Ingredient Form Type', 'recipe-press'); ?></label></th>
                                <td>
                                    <select name="<?php echo $this->optionsName; ?>[form-type]" id="recipe_press_form_type">
                                        <option value="textarea" <?php selected($this->options['form-type'], 'textarea'); ?> ><?php _e('Plain text', 'recipe-press'); ?></option>
                                        <option value="ingredients" <?php selected($this->options['form-type'], 'ingredients'); ?> ><?php _e('Individual Ingredients', 'recipe-press'); ?></option>
                                    </select>
                                    <?php $this->help(__('Select the default recipe status for new user submitted recipes.', 'recipe-press')); ?>
                                </td>
                            </tr>
                            <tr align="top">
                                <th scope="row"><label for="recipe_press_require_login"><?php _e('Require login for submit form?', 'recipe-press'); ?></label></th>
                                <td>
                                    <input type="checkbox" name="<?php echo $this->optionsName; ?>[require-login]" id="recipe_press_require_login" value="1" <?php $this->checked($this->options['require-login'], 1); ?> />
                                    <?php $this->help(__('If checked will require all users to be logged in before they can submit a recipe.', 'recipe-press')); ?>
                                </td>
                            </tr>
                            <tr align="top">
                                <th scope="row"><label for="recipe_press_ingredients_fields"><?php _e('Number of ingredients fields on blank form', 'recipe-press'); ?></label></th>
                                <td>
                                    <input type="input" name="<?php echo $this->optionsName; ?>[ingredients-fields]" id="recipe_press_ingredients_fields" value="<?php echo $this->options['ingredients-fields']; ?>" />
                                    <?php $this->help(__('How many initial ingredients input lines to display on a blank form.', 'recipe-press')); ?>
                                </td>
                            </tr>
                            <tr align="top">
                                <th scope="row"><?php _e('Required Form Fields', 'recipe-press'); ?></th>
                                <td>
                                    <table border="0">
                                        <tbody>
                                            <tr>
                                                <td><label><input type="checkbox" name="<?php echo $this->optionsName; ?>[required-fields][]" value="title" <?php $this->checked($this->options['required-fields'], 'title'); ?> /> <?php _e('Title', 'recipe-press'); ?></label></td>
                                                <td><label><input type="checkbox" name="<?php echo $this->optionsName; ?>[required-fields][]" value="notes" <?php $this->checked($this->options['required-fields'], 'notes'); ?> /> <?php _e('Notes', 'recipe-press'); ?></label></td>
                                                <td><label><input type="checkbox" name="<?php echo $this->optionsName; ?>[required-fields][]" value="category" <?php $this->checked($this->options['required-fields'], 'category'); ?> /> <?php _e('Category', 'recipe-press'); ?></label></td>
                                            </tr>
                                            <tr>
                                                <td><label><input type="checkbox" name="<?php echo $this->optionsName; ?>[required-fields][]" value="servings" <?php $this->checked($this->options['required-fields'], 'servings'); ?> /> <?php _e('Servings', 'recipe-press'); ?></label></td>
                                                <td><label><input type="checkbox" name="<?php echo $this->optionsName; ?>[required-fields][]" value="prep_time" <?php $this->checked($this->options['required-fields'], 'prep_time'); ?> /> <?php _e('Prep Time', 'recipe-press'); ?></label></td>
                                                <td><label><input type="checkbox" name="<?php echo $this->optionsName; ?>[required-fields][]" value="cook_time" <?php $this->checked($this->options['required-fields'], 'cook_time'); ?> /> <?php _e('Cook Time', 'recipe-press'); ?></label></td>
                                            </tr>
                                            <tr>
                                                <td><label><input type="checkbox" name="<?php echo $this->optionsName; ?>[required-fields][]" value="instructions" <?php $this->checked($this->options['required-fields'], 'instructions'); ?> /> <?php _e('Instructions', 'recipe-press'); ?></label></td>
                                                <td><label><input type="checkbox" name="<?php echo $this->optionsName; ?>[required-fields][]" value="submitter" <?php $this->checked($this->options['required-fields'], 'submitter'); ?> /> <?php _e('Name', 'recipe-press'); ?> <?php $this->help(__('Only displayed when a user is not logged in', 'recipe-press')); ?></label></td>
                                                <td><label><input type="checkbox" name="<?php echo $this->optionsName; ?>[required-fields][]" value="submitter_email" <?php $this->checked($this->options['required-fields'], 'submitter_email'); ?> /> <?php _e('Email', 'recipe-press'); ?> <?php $this->help(__('Only displayed when a user is not logged in', 'recipe-press')); ?></label></td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="postbox">
                <h3 class="handl" style="margin:0;padding:3px;cursor:default;">
                    <?php _e('Widget Settings', 'recipe-press'); ?>
                </h3>
                <table class="form-table">
                    <tr align="top">
                        <th scope="row"><label for="recipe_press_widget_items"><?php _e('Default Items to Display', 'recipe-press'); ?></label></th>
                        <td>
                            <select name="<?php echo $this->optionsName; ?>[widget-items]" id="recipe_press_widget_items">
                                <?php
                                for ( $i = 1; $i <= 20; ++$i )
                                    echo "<option value='$i' " . selected($this->options['widget-items'], $i) . ">$i</option>";
                                ?>
                            </select>
                            <?php $this->help(__('Default for new widgets.', 'recipe-press')); ?>
                        </td>
                    </tr>
                    <tr align="top">
                        <th scope="row"><label for="recipe_press_widget_type"><?php _e('Default List Widget Type', 'recipe-press'); ?></label></th>
                        <td>
                            <select name="<?php echo $this->optionsName; ?>[widget-type]" id="recipe_press_widget_type">
                                <option value="newest" <?php selected($this->options['widget-type'], 'newest'); ?> ><?php _e('Newest Recipes', 'recipe-press'); ?></option>
                                <option value="random" <?php selected($this->options['widget-type'], 'random'); ?> ><?php _e('Random Recipes', 'recipe-press'); ?></option>
                                <option value="popular" <?php selected($this->options['widget-type'], 'popular'); ?> ><?php _e('Most Popular', 'recipe-press'); ?></option>
                                <option value="featured" <?php selected($this->options['widget-type'], 'featured'); ?> ><?php _e('Featured', 'recipe-press'); ?></option>
                                <option value="updated" <?php selected($this->options['widget-type'], 'updated'); ?> ><?php _e('Redently Updated', 'recipe-press'); ?></option>
                            </select>
                            <?php $this->help(__('Default link target when adding a new widget.', 'recipe-press')); ?>
                        </td>
                    </tr>
                    <tr align="top">
                        <th scope="row"><label for="recipe_press_widget_sort"><?php _e('Default List Widget Sort', 'recipe-press'); ?></label></th>
                        <td>
                            <select name="<?php echo $this->optionsName; ?>[widget-sort]" id="recipe_press_widget_sort">
                                <option value="asc" <?php selected($this->options['widget-sort'], 'asc'); ?> ><?php _e('Ascending', 'recipe-press'); ?></option>
                                <option value="desc" <?php selected($this->options['widget-sort'], 'desc'); ?> ><?php _e('Descending', 'recipe-press'); ?></option>
                            </select>
                            <?php $this->help(__('Default link target when adding a new widget.', 'recipe-press')); ?>
                        </td>
                    </tr>

                    <tr align="top">
                        <th scope="row"><label for="recipe_press_widget_target"><?php _e('Default Link Target', 'recipe-press'); ?></label></th>
                        <td>
                            <select name="<?php echo $this->optionsName; ?>[widget-target]" id="recipe_press_widget_target">
                                <option value="0">None</option>
                                <option value="_blank" <?php selected($this->widgetTarget, '_blank') ; ?>>New Window</option>
                                <option value="_top" <?php selected($this->widgetTarget, '_top'); ?>>Top Window</option>
                            </select>
                            <?php $this->help(__('Default link target when adding a new widget.', 'recipe-press')); ?>
                        </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row"><label for="recipe_press_widget_show_icon"><?php _e('Show Icons?', 'recipe-press'); ?></label></th>
                        <td>
                            <input type="checkbox" name="<?php echo $this->optionsName; ?>[widget-show-icon]" id="recipe_press_widget_show_icon" value="1" <?php checked($this->options['widget-show-icon'], 1); ?> />
                            <?php $this->help(__('Check this option to show icons in widgets by default, can be turned of individually in each widget instance.', 'recipe-press')); ?>
                        </td>

                    </tr>
                    <tr align="top">
                        <th scope="row"><label for="recipe_press_widget_icon_size"><?php _e('Widget Icon Size', 'recipe-press'); ?></label></th>
                        <td>
                            <input type="input" name="<?php echo $this->optionsName; ?>[widget-icon-size]" id="recipe_press_widget_icon_size" value="<?php echo $this->options['widget-icon-size']; ?>" />
                            <?php $this->help(__('Default icon size for widgets, can be changed in each widget instance.', 'recipe-press')); ?>
                        </td>
                    </tr>

                </table>
            </div>

        </div>
        <div  style="width:49%; float:right">
            <div class="postbox">
                <h3 class="handl" style="margin:0;padding:3px;cursor:default;">
                    <?php _e('Management Settings', 'recipe-press'); ?>
                </h3>
                <div class="table">
                    <table class="form-table">
                        <tr align="top">
                            <th scope="row"><label for="recipe_press_default_category"><?php _e('Default Category', 'recipe-press'); ?></label></th>
                            <td>
                                <select name="<?php echo $this->optionsName; ?>[default-category]" id="recipe_press_default_category">
                                    <?php echo $this->listOptions('categories', $this->options['default-category']); ?>
                                </select>
                                <?php $this->help(__('Default category for adding recipes. Also, when deleting a category, all affected recipes will be moved to this category.', 'recipe-press')); ?>
                            </td>
                        </tr>
                        <tr align="top">
                            <th scope="row"><label for="recipe_press_ingredient_parent"><?php _e('New Ingredient Page Parent', 'recipe-press'); ?></label></th>
                            <td>
                                <?php wp_dropdown_pages(array('name'=>$this->optionsName.'[ingredient-parent]', 'selected'=>$this->options['ingredient-parent'], 'show_option_none'=>'None')); ?>
                                <?php $this->help(__('This page will be used as the parent page for any ingredient pages automatically created by the recipe editor.', 'recipe-press')); ?>
                            </td>
                        </tr>
                        <tr align="top">
                            <th scope="row"><label for="recipe_press_ingredient_parent_status"><?php _e('New page status', 'recipe-press'); ?></label></th>
                            <td>
                                <select name="<?php echo $this->optionsName; ?>[ingredient-parent-status]" id="recipe_press_ingredient_parent_status">
                                    <option value="draft" <?php selected($this->options['ingredient-parent-status'], 'draft'); ?> ><?php _e('Draft', 'recipe-press'); ?></option>
                                    <option value="pending" <?php selected($this->options['ingredient-parent-status'], 'pending'); ?> ><?php _e('Pending', 'recipe-press'); ?></option>
                                    <option value="publish" <?php selected($this->options['ingredient-parent-status'], 'publish'); ?> ><?php _e('Published', 'recipe-press'); ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr align="top">
                            <th scope="row"><label for="recipe_press_display_limit"><?php _e('Records to display', 'recipe-press'); ?></label></th>
                            <td>
                                <input type="text" name="<?php echo $this->optionsName; ?>[display-limit]" id="recipe_press_display_limit" value="<?php echo $this->options['display-limit']; ?>" />
                                <?php $this->help(__('This will set the total number of records to display on management pages.', 'recipe-press')); ?>
                            </td>
                        </tr>

                        <tr align="top">
                            <th scope="row"><label for="recipe_press_delete_data"><?php _e('Remove Data on Deactivate?', 'recipe-press'); ?></label></th>
                            <td>
                                <input type="checkbox" name="<?php echo $this->optionsName; ?>[delete-data]" id="recipe_press_delete_data" value="1"
                                       onclick="if (this.checked) this.checked=confirm('<?php echo esc_js(__('Warning: If you deactivate the plugin while this option is checked, all data and options will be removed from the database. Make sure you backup your data before you deactivate the plugin as there is no way to recover from this operation. Are you sure you want to do this?')); ?>')"
                                       <?php checked($this->options['delete-data'], 1); ?>
                                       />
                                       <?php $this->help(__('By checking this box, all data and options will be removed when the plugin is deactivated.', 'recipe-press')); ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="postbox">
                <h3 class="handl" style="margin:0;padding:3px;cursor:default;">
                    <?php _e('Access Settings', 'recipe-press'); ?>
                </h3>
                <div class="table">
                    <table class="form-table">
                        <tr align="top">
                            <th scope="row" colspan="2"><?php _e('These settings determine the minimum user level allowed to view different menus. Administrators and Editors see all items on menus, lower levels see only their own contributions.', 'recipe-press'); ?></th>
                        </tr>
                        <tr align="top">
                            <th scope="row"><label for="recipe_press_overview_role"><?php _e('Overview Menu', 'recipe-press'); ?></label></th>
                            <td>
                                <?php $this->list_roles('overview', $this->options['overview-role'], array('disabled')); ?>
                                <?php $this->help(__('This setting will default to the lowest level required for any of the menus below as it is required for creating other menus.', 'recipe-press')); ?>
                            </td>
                        </tr>
                        <tr align="top">
                            <th scope="row"><label for="recipe_press_recipes_role"><?php _e('Recipe List', 'recipe-press'); ?></label></th>
                            <td>
                                <?php $this->list_roles('recipes', $this->options['recipes-role']); ?>
                                <?php $this->help(__('User level required to view the Recipe List. Only Admins and Editors will see all recipes while lower levels see only their recipes.', 'recipe-press')); ?>
                            </td>
                        </tr>
                        <tr align="top">
                            <th scope="row"><label for="recipe_press_add-recipe_role"><?php _e('Add Recipe', 'recipe-press'); ?></label></th>
                            <td>
                                <?php $this->list_roles('add-recipe', $this->options['add-recipe-role']); ?>
                                <?php $this->help(__('User level required to view the add recipe page.', 'recipe-press')); ?>
                            </td>
                        </tr>
                        <tr align="top">
                            <th scope="row"><label for="recipe_press_ingredients_role"><?php _e('Ingredients Menu', 'recipe-press'); ?></label></th>
                            <td>
                                <?php $this->list_roles('ingredients', $this->options['ingredients-role']); ?>
                                <?php $this->help(__('User level required to view the add ingredients menu.', 'recipe-press')); ?>
                            </td>
                        </tr>
                        <tr align="top">
                            <th scope="row"><label for="recipe_press_comments_role"><?php _e('Comments Menu', 'recipe-press'); ?></label></th>
                            <td>
                                <?php $this->list_roles('comments', $this->options['comments-role']); ?>
                                <?php $this->help(__('User level required to view the comments menu.', 'recipe-press')); ?>
                            </td>
                        </tr>
                        <tr align="top">
                            <th scope="row"><label for="recipe_press_catgeories_role"><?php _e('Categories Menu', 'recipe-press'); ?></label></th>
                            <td>
                                <?php $this->list_roles('categories', $this->options['categories-role']); ?>
                                <?php $this->help(__('User level required to view the categories menu. <strong>Warning</strong>: Users will see all categories and can edit and delete all categories. This may change in a future version, but for now, only allow users who you want this access!', 'recipe-press')); ?>
                            </td>
                        </tr>
                        <tr align="top">
                            <th scope="row"><label for="recipe_press_tags_role"><?php _e('Tags Menu', 'recipe-press'); ?></label></th>
                            <td>
                                <?php $this->list_roles('tags', $this->options['tags-role']); ?>
                                <?php $this->help(__('User level required to view the tags menu. <strong>Warning</strong>: Users will see all tags and can edit and delete all tags. This may change in a future version, but for now, only allow users who you want this access!', 'recipe-press')); ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="postbox">
                <h3 class="handl" style="margin:0;padding:3px;cursor:default;">
                    <?php _e('reCaptcha Settings', 'recipe-press'); ?>
                </h3>
                <table class="form-table">
                    <tr>
                        <td colspan="2">
                            <?php _e('To use reCaptcha on the public form, enter your public and private keys here.', 'recipe-press'); ?>
                            <a href="<?php echo recaptcha_get_signup_url(eregi_replace('http://', '', get_option('siteurl')), 'Recipe Press for WordPress'); ?>" target="_blank"><?php _e('Get Keys', 'recipe-press'); ?></a>
                        </td>
                    </tr>
                    <tr align="top">
                        <th scope="row"><label for="recipe_press_force_recaptcha"><?php _e('Force for All Users?', 'recipe-press'); ?></label></th>
                        <td>
                            <input type="checkbox" name="<?php echo $this->optionsName; ?>[force-recaptcha]" id="recipe_press_force_recaptcha" value="1" <?php checked($this->options['force-recaptcha'], 1); ?> />
                            <?php $this->help(__('If checked, logged in users will see the reCaptcha.', 'recipe-press')); ?>
                        </td>
                    </tr>
                    <tr align="top">
                        <th scope="row"><label for="recipe_press_recaptcha_public"><?php _e('Public Key', 'recipe-press'); ?></label></th>
                        <td><input type="text" name="<?php echo $this->optionsName; ?>[recaptcha-public]" id="recipe_press_recaptcha_public" value="<?php echo $this->options['recaptcha-public']; ?>" /></td>
                    </tr>
                    <tr align="top">
                        <th scope="row"><label for="recipe_press_recaptcha_private"><?php _e('Private Key', 'recipe-press'); ?></label></th>
                        <td><input type="text" name="<?php echo $this->optionsName; ?>[recaptcha-private]" id="recipe_press_recaptcha_private" value="<?php echo $this->options['recaptcha-private']; ?>" /></td>
                    </tr>
                </table>
            </div>
            <div class="postbox">
                <h3 class="handl" style="margin:0;padding:3px;cursor:default;">
                    <?php _e('Comment Settings', 'recipe-press'); ?>
                </h3>
                <div class="table">
                    <table class="form-table">
                        <tr align="top">
                            <th scope="row"><label for="recipe_press_comment_form_title"><?php _e('Form Title', 'recipe-press'); ?></label></th>
                            <td>
                                <input type="text" name="<?php echo $this->optionsName; ?>[comment-form-title]" id="recipe_press_comment_form_title" value="<?php print$this->options['comment-form-title']; ?>" />
                            </td>
                        </tr>
                        <tr align="top">
                            <th scope="row"><label for="recipe_press_comments_login"><?php _e('Must be logged in to comment', 'recipe-press'); ?></label></th>
                            <td>
                                <input type="checkbox" name="<?php echo $this->optionsName; ?>[comments-login]" id="recipe_press_comments-login" value="1" <?php checked($this->options['comments-login'], 1); ?> />
                                <?php $this->help(__('By checking this box, visitors must log in before leaving a comment on a recipe.', 'recipe-press')); ?>
                            </td>
                        </tr>
                        <tr align="top">
                            <th scope="row"><label for="recipe_press_comments_gravatar"><?php _e('Show Gravatar?', 'recipe-press'); ?></label></th>
                            <td>
                                <input type="checkbox" name="<?php echo $this->optionsName; ?>[comments-gravatar]" id="recipe_press_comments-gravatar" value="1" <?php checked($this->options['comments-gravatar'], 1); ?> />
                                <?php $this->help(__('By checking this box, visitors Gravatar will be displayed along with their comment.', 'recipe-press')); ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div style="clear: both;">
            <p class="submit" align="center">
                <input type="hidden" name="action" value="update" />
                <?php if (function_exists('wpmu_create_blog')) : ?>
                <input type="hidden" name="option_page" value="<?php echo $this->optionsName; ?>" />
                <?php  else : ?>
                <input type="hidden" name="page_options" value="<?php echo $this->optionsName; ?>" />
                <?php endif; ?>
                <input type="submit" name="Submit" value="<?php _e('Save Settings', 'recipe-press'); ?>" />
            </p>
        </div>
    </form>
    <?php include "footer.php"; ?>
</div>
