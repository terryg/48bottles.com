<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }
/**
 * recipe-form.php - View for the back end recipe form.
 *
 * @package Recipe Press
 * @subpackage views
 * @author GrandSlambert
 * @copyright 2009-2010
 * @access public
 */
?>

<div class="wrap">
    <div id="poststuff" class="metabox-holder has-right-sidebar">
        <div id="side-info-column" class="inner-sidebar">
            <div id="imagediv" class="stuffbox">
                <h3><label for="name"><?php _e( 'Recipe Image' , 'recipe-press') ?></label></h3>
                <div class="inside">
                    <table class="form-table editrecipe">
                        <tbody>
                            <tr class="form-field">
                                <td colspan="2">
                                    <?php if ($this->get_recipe_media_id()) : ?>
                                    <input type="hidden" name="media_id" value="<?php $this->recipe_media_id(); ?>" id="media_id" />
                                        <?php echo wp_get_attachment_image( $this->get_recipe_media_id() ); ?>
                                    <?php endif; ?>
                                </td>
                            </tr>

                            <tr class="form-field">
                                <th colspan="2"><label for="comments_open"><?php _e('Upload a New Image', 'recipe-press'); ?></label></td>
                            </tr>
                            <tr class="form-field">
                                <td colspan="2"><input type="file" name="image" value="" /></td>
                            </tr>
                            <tr class="form-field">
                                <td valign="top"><label for="delete-existing"><?php _e('Delete existing image?', 'recipe-press'); ?></label></td>
                                <td>
                                    <input type="checkbox" name="delete_existing" value="1" <?php if ($this->get_recipe_media_id() > 0) echo 'checked="checked"'; ?> />
                                    <input type="hidden" name="old_media_id" value="<?php $this->recipe_media_id(); ?>" />
                                </td>
                        </tbody>
                    </table>
                    <br />
                </div>
            </div>
            <div id="detailsdiv" class="stuffbox">
                <h3><label for="name"><?php _e( 'Details' , 'recipe-press') ?></label></h3>
                <div class="inside">
                    <table class="form-table editrecipe">
                        <tbody>
                            <tr class="form-field">
                                <td valign="top" scope="row"><label for="category"><?php _e('Category', 'recipe-press'); ?></label></td>
                                <td>
                                    <select name="category" id="category" tabindex="9">
                                        <?php echo $this->listOptions('categories', isset($this->recipe->category) ? $this->recipe->category : $this->options['default-category']); ?>
                                    </select>
                                </td>
                            </tr>
                            <tr class="form-field">
                                <td valign="top" scope="row"><label for="servings"><?php _e('Servings', 'recipe-press'); ?></label></td>
                                <td nowrap><input name="servings" type="text" tabindex="10" id="servings" value="<?php echo $this->recipe->servings; ?>" style="width:25px" />
                                    <select name="servings_size" id="servings_size" tabindex="11">
                                        <?php echo $this->listOptions('options', $this->recipe->servings_size, 'key', 'value', 'serving_size'); ?>
                                    </select></td>
                            </tr>
                            <tr class="form-field">
                                <td valign="top" scope="row"><label for="prep_time"><?php _e('Prep Time', 'recipe-press'); ?></label></td>
                                <td><input name="prep_time" type="text" id="prep_time" tabindex="12" value="<?php echo $this->recipe->prep_time; ?>" style="width:25px" />
				minutes</td>
                            </tr>
                            <tr class="form-field">
                                <td><label for="cook_time"><?php _e('Cook Time', 'recipe-press'); ?></label></td>
                                <td><input name="cook_time" type="text" id="cook_time" tabindex="13" value="<?php echo $this->recipe->cook_time; ?>" style="width:25px" />
                                    minutes</td>
                            </tr>
                            <tr class="form-field">
                                <td><label for="comments_open"><?php _e('Allow Comments', 'recipe-press'); ?></label></td>
                                <td><input type="checkbox" class="checkbox"name="comments_open" tabindex="14" value="1" <?php checked($this->recipe->comments_open, 1); ?> /></td>
                            </tr>
                            <tr class="form-field">
                                <td><label for="comments_open"><?php _e('Featured?', 'recipe-press'); ?></label></td>
                                <td><input type="checkbox" class="checkbox" name="featured" tabindex="14" value="1" <?php checked($this->recipe->featured, 1); ?> /></td>
                            </tr>
                        </tbody>
                    </table>
                    <br />
                </div>
            </div>
            <div id="submitdiv" class="stuffbox" >
                <h3><span class='hndle'><?php _e('Status', 'recipe-press') ?></span></h3>
                <div class="inside">
                    <div class="submitbox" id="submitcomment">
                        <div id="minor-publishing">

                            <!-- Removed until recipe display is fixed.
                            <div id="minor-publishing-actions">
                                <div id="preview-action">
                                    <a class="preview button" href="<?php echo get_comment_link(); ?>" target="_blank"><?php _e('View Comment', 'recipe-press'); ?></a>
                                </div>
                                <div class="clear"></div>
                            </div>
                            -->

                            <div id="misc-publishing-actions">

                                <div class="misc-pub-section" id="comment-status-radio">
                                    <?php
                                    if (!$this->recipe->status) {
                                        if ($this->superUser()) {
                                            $this->recipe->status = 'active';
                                        } else {
                                            $this->recipe->status = 'pending';
                                        }
                                    }
                                    ?>
                                    <label class="draft">
                                        <input type="radio" <?php checked( $this->recipe->status, 'draft' ); ?> tabindex="15" name="status" value="draft" <?php if (!$this->superUser()) echo "disabled"; ?> /><?php /* translators: comment type radio button */ echo _x('Draft', 'adjective') ?>
                                    </label><br />
                                    <label class="waiting">
                                        <input type="radio" <?php checked( $this->recipe->status, 'pending' ); ?> tabindex="16" name="status" value="pending" <?php if (!$this->superUser()) echo "disabled"; ?> /><?php /* translators: comment type radio button */ echo _x('Pending', 'adjective') ?>
                                    </label><br />
                                    <label class="approved">
                                        <input type="radio" <?php checked( $this->recipe->status, 'active' ); ?> tabindex="17" name="status" value="active" <?php if (!$this->superUser()) echo "disabled"; ?> /><?php /* translators: comment type radio button */ echo _x('Approved', 'adjective') ?>
                                    </label><br />

                                    <?php if (!$this->superUser()) : ?>
                                    <input type="hidden" name="status" id="status" value="<?php echo $this->recipe->status; ?>" />
                                    <?php endif; ?>
                                </div>

                                <div class="misc-pub-section curtime misc-pub-section-last">
                                    <?php
                                    /* translators: Publish box date formt, see http://php.net/date */
                                    $datef = __( 'M j, Y @ G:i' , 'recipe-press');
                                    /* translators: Displays the date submitted - argument is formatted date. */
                                    $stamp = __('Submitted on: <strong>%1$s</strong>', 'recipe-press');
                                    /* translators: Format of who submitted the recipe - argument is the name of the author. */
                                    $submitter = __('Submitted by: <strong>%1$s</strong>', 'recipe-press');
                                    $date = date_i18n( $datef, strtotime( $this->recipe->added ) );
                                    ?>
                                    <span id="timestamp"><?php printf($stamp, $date); ?></span>
                                    <!--&nbsp;<a href="#edit_timestamp" class="edit-timestamp hide-if-no-js" tabindex='4'><?php _e('Edit', 'recipe-press') ?></a>-->
                                    <div id='timestampdiv' class='hide-if-js'><?php touch_time(('editcomment' == $action), $this->recipe->id, 5); ?></div>
                                    <?php if ($this->recipe->submitter) : ?>
                                    <span id="submitter">
                                            <?php printf($submitter, '<a href="mailto:' . $this->recipe->submitter_email . '">' . $this->recipe->submitter. '</a>'); ?>
                                        <input type="hidden" name="submitter" id="submitter" value="<?php echo $this->recipe->submitter; ?>" />
                                        <input type="hidden" name="submitter_email" id="submitter" value="<?php echo $this->recipe->submitter_email; ?>" />
                                    </span>
                                    <?php endif; ?>
                                </div>
                            </div> <!-- misc actions -->
                            <div class="clear"></div>
                        </div>

                        <div id="major-publishing-actions">
                            <div id="delete-action">
                                <a class="submitdelete deletion" href="<?php $this->adminURL(rp_Recipes::menuName, array('action' => 'trash', 'id' => $this->recipe->id)); ?>" onclick="if ( confirm('<?php echo esc_js(__("You are about to delete this recipe. \n 'Cancel' to stop, 'OK' to delete.", 'recipe-press')); ?>')) {return true;} return false;"><?php _e('Trash', 'recipe-press'); ?></a>
                            </div>
                            <div id="publishing-action">
                                <input type="submit" name="save" value="<?php _e('Save Recipe', 'recipe-press'); ?>" tabindex="20" class="button-primary" />
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
                    <h3><label for="name"><?php _e( 'Information' , 'recipe-press') ?></label></h3>
                    <div class="inside">
                        <table class="form-table editrecipe">
                            <tbody>
                                <tr valign="top">
                                    <td class="first"><?php _e( 'Title:' , 'recipe-press'); ?></td>
                                    <td><input type="text" name="title" size="30" value="<?php echo $this->recipe_title(); ?>" tabindex="1" id="title" /></td>
                                </tr>
                                <tr valign="top">
                                    <td class="first">
                                    <?php _e( 'Slug:' , 'recipe-press'); ?></td>
                                    <td>
                                        <input type="text" name="slug" size="30" value="<?php echo $this->recipe_slug(); ?>" tabindex="2" id="slug" <?php if (!$this->superUser()) echo 'disabled'; ?> />
                                        <br/><?php _e('The "slug" is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens. Leave this blank for an auto generated slug based on the title', 'recipe-press'); ?>
                                    </td>
                                </tr>
                                <?php if ($this->superUser() ) : ?>
                                <tr valign="top">
                                    <td class="first"><?php _e( 'Author:' , 'recipe-press'); ?></td>
                                    <td><?php wp_dropdown_users(array('selected'=>($this->get_recipe_user_id()) ? $this->get_recipe_user_id() : $current_user->ID, 'name'=>'user_id', 'tableindex'=>3)); ?></td>
                                </tr>
                                <?php else : ?>
                            <input type="hidden" name="user_id" value="<?php echo ($this->get_recipe_user_id()) ? $this->get_recipe_user_id() : $current_user->ID; ?>" />
                            <?php endif; ?>

                            <?php if ($this->options['show-template'] or $this->superUser()) : ?>
                            <tr valign="top">
                                <td class="first"><?php _e( 'Template: ', 'recipe-press'); ?></td>
                                <td><?php $this->recipe_templates($this->recipe->template, false); ?></td>
                            </tr>
                            <?php else : ?>
                            <input type="hidden" name="template" value="<?php $this->recipe_template(); ?>" />

                            <?php endif; ?>

                            <tr>
                                <td class="first"><?php _e('Notes', 'recipe-press'); ?></td>
                                <td>
                                    <textarea style="width: 97%;" cols="50" rows="5" id="notes" tabindex="7" name="notes"><?php  $this->recipe_notes(); ?></textarea>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="namediv" class="stuffbox">
                    <h3><?php _e( 'Ingredients' , 'recipe-press') ?></h3>
                    <div class="inside">
                        <label for="hide_ingredients_header">
                            <?php _e('Check this box to turn off the default header for the Ingredients display: '); ?>
                            <input type="checkbox" name="hide_ingredients_header" value="1" <?php checked($this->recipe->hide_ingredients_header, '1'); ?> />
                        </label>

                        <table id="rp_ingredients" class="form-table editrecipe">
                            <thead>
                                <tr class="form-field">
                                    <th style="width:30px;">&nbsp;</th>
                                    <th style="width:60px;"><?php _e('Quantity', 'recipe-press'); ?></th>
                                    <th><?php _e('Size', 'recipe-press'); ?></th>
                                    <th><?php _e('Ingredient', 'recipe-press'); ?></th>
                                    <th><?php _e('Link to page', 'recipe-press'); ?></th>
                                </tr>
                            </thead>
                            <tbody id="rp_ingredients_body">
                                <tr id="rp_ingredient_null" style="display:none">
                                    <th id="rp_drag_icon">
                                        <img alt="<?php _e('Drag Ingredient'); ?>" src="<?php echo $this->pluginDir . '/icons/drag-icon.png'; ?>" style="cursor:pointer" />
                                        <img alt="<?php _e('Delete Ingredient'); ?>" src="<?php echo $this->pluginDir . '/icons/delete.gif'; ?>" style="cursor:pointer" onclick="rp_delete_row('rp_ingredient_NULL');" />
                                    </th>
                                    <td id="rp_size_column">
                                        <select name="ingredientsCOPY[NULL][size]">
                                            <option value="none">No Size</option>
                                            <?php echo $this->listOptions('options', NULL, 'key', 'value', 'ingredient_size'); ?>
                                        </select><br />
                                        <div style="text-align:right; font-size:smaller"><?php _e('Ingredient Notes:'); ?></div>
                                    </td>
                                    <td id="rp_page_column">
                                        <?php wp_dropdown_pages(array('name'=>'ingredientsCOPY[NULL][page-link]', 'show_option_none'=>'None')); ?><br />
                                        <label><input type="checkbox" name="ingredientsCOPY[NULL][create-page]" value="1" onclick="if (this.checked) this.checked=confirm('<?php echo esc_js(__('Checking this box will create a new page for this ingredient. Are you sure you want to do this?')); ?>')" /> <?php _e('Create a new page?'); ?></label>
                                    </td>
                                </tr>
                                <?php $rowCTR = 1; foreach($this->get_recipe_ingredients(true) as $id=>$ingredient) : ?>
                                <tr id="rp_ingredient_<?php echo $rowCTR; ?>" class="rp_size_type_<?php echo $ingredient['size']; ?>" valign="top">
                                    <th style="width:60px;">
                                        <img alt="<?php _e('Drag Ingredient'); ?>" src="<?php echo $this->pluginDir . '/icons/drag-icon.png'; ?>" style="cursor:pointer" />
                                        <img alt="<?php _e('Delete Ingredient'); ?>" src="<?php echo $this->pluginDir . '/icons/delete.gif'; ?>" style="cursor:pointer" onclick="rp_delete_row('rp_ingredient_<?php echo $rowCTR; ?>');" />
                                    </th>
                                    <td style="width:60px;">
                                            <?php if ($ingredient['size'] != 'divider' and $ingredient['size']) : ?>
                                        <input type="text" name="ingredients[<?php echo $rowCTR; ?>][quantity]" value="<?php echo $ingredient['quantity']; ?>" style="width:60px;" /></td>
                                        <?php endif; ?>
                                    <td>
                                            <?php if ($ingredient['size'] != 'divider' and $ingredient['size']) : ?>

                                        <select name="ingredients[<?php echo $rowCTR; ?>][size]">
                                            <option value="none">No Size</option>
                                                    <?php echo $this->listOptions('options', $ingredient['size'], 'key', 'value', 'ingredient_size'); ?>
                                        </select><br />
                                        <div style="text-align:right; font-size:smaller"><?php _e('Ingredient Notes:'); ?></div>
                                            <?php else : ?>
                                        <input type="text" name="ingredients[<?php echo $rowCTR; ?>][size]" value="divider" style="width:55px;" readonly="readonly" />

                                            <?php endif; ?>
                                    </td>
                                    <td>
                                        <input type="text" name="ingredients[<?php echo $rowCTR; ?>][item]" value="<?php echo stripslashes_deep(trim($ingredient['item'])); ?>" /><br />
                                            <?php if ($ingredient['size'] != 'divider' and $ingredient['size']) : ?>
                                        <input style="font-size:smaller" type="text" name="ingredients[<?php echo $rowCTR; ?>][notes]" value="<?php echo stripslashes_deep(trim($ingredient['notes'])); ?>" />
                                            <?php endif; ?>
                                    </td>

                                    <td>
                                            <?php if ($ingredient['size'] != 'divider') : ?>
                                                <?php wp_dropdown_pages(array('name'=>'ingredients[' . $rowCTR . '][page-link]', 'selected'=>$ingredient['page-link'], 'show_option_none'=>'None')); ?><br />
                                        <label><input type="checkbox" name="ingredients[<?php echo $rowCTR; ?>][create-page]" value="1" onclick="if (this.checked) this.checked=confirm('<?php echo esc_js(__('Checking this box will create a new page for this ingredient. Are you sure you want to do this?')); ?>')" /> <?php _e('Create a new page?'); ?>
                                                <?php endif; ?>
                                    </td>
                                </tr>
                                    <?php ++$rowCTR; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <p><a onclick="rp_add_ingredient('admin')" style="cursor:pointer"><?php _e('Add Ingredient', 'recipe-press'); ?></a> | <a onclick="rp_add_divider('admin')" style="cursor:pointer"><?php _e('Add Divider', 'recipe-press'); ?></a></p>
                    </div>
                </div>


                <h3><label for="name"><?php _e( 'Instructions' , 'recipe-press') ?></label></h3>
                <div id="poststuff">
                    <div id="<?php echo user_can_richedit() ? 'postdivrich' : 'postdiv'; ?>" class="postarea">
                        <?php wp_tiny_mce( false , array("editor_selector" => "instructions") ); ?>
                        <textarea class="instructions" id="instructions" name="instructions"><?php $this->recipe_instructions(); ?></textarea>

                    </div>
                </div>

                <?php do_meta_boxes('recipe', 'normal', $this->recipe); ?>

                <input type="hidden" name="c" value="<?php echo esc_attr($this->recipe->id) ?>" />
                <input name="referredby" type="hidden" id="referredby" value="<?php echo esc_url(esc_attr(wp_get_referer())); ?>" />
                <?php wp_original_referer_field(true, 'previous'); ?>
                <input type="hidden" name="noredir" value="1" />

            </div>
        </div>
    </div>
</div>