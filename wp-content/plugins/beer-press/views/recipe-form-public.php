<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }
/**
 * recipe-form-public.php - View for the public recipe form.
 *
 * @package Recipe Press
 * @subpackage views
 * @author GrandSlambert
 * @copyright 2009-2010
 * @access public
 */
?>

<input type="hidden" name="user_id" id ="user_id" value="<?php echo get_user_option('ID'); ?>" />
<input type="hidden" name="status" value="pending" />
<?php if ( is_user_logged_in() ) : ?>
<input type="hidden" name="submitter" value="<?php echo get_user_option('display_name'); ?>" />
<input type="hidden" name="submitter_email" value="<?php echo get_user_option('user_email'); ?>" />
<?php endif; ?>
<input type="hidden" name="comments_open" value="1" />
<table class="recipe-press-submit">
    <tbody>
        <tr class="form-field form-required">
            <th valign="top" scope="row" class="recipe-press-title <?php $this->requiredField('title'); ?>">
                <label for="title"><?php _e('Recipe Name', 'recipe-press'); ?></label>
            </th>
            <td colspan="3">
                <input type="text" size="40" value="<?php echo stripslashes($_POST['title']); ?>" id="title" name="title"/>
                <?php if ($this->formErrors['title']) : ?>
                <br /><span class="recipe-press-error"><?php _e('Recipe Name is required', 'recipe-press'); ?></span>
                <?php endif; ?>
            </td>
        </tr>
        <tr class="form-field">
            <th valign="top" scope="row" class="recipe-press-notes <?php $this->requiredField('notes'); ?>">
                <label for=""notes"><?php _e('Notes', 'recipe-press'); ?></label>
            </th>
            <td colspan="3">
                <textarea class="rp_textarea rp_notes_field" id="notes" name="notes"><?php echo stripslashes($_POST['notes']); ?></textarea>
                <?php if ($this->formErrors['notes']) : ?>
                <br /><span class="recipe-press-error"><?php _e('Notes are required', 'recipe-press'); ?></span>
                <?php endif; ?>
            </td>
        </tr>
        <tr class="form-field">
            <th valign="top" scope="row" class="recipe-press-category <?php $this->requiredField('category'); ?>">
                <label for="category"><?php _e('Category', 'recipe-press'); ?></label>
            </th>
            <td colspan="3">
                <select name="category" id="category">
                    <?php echo $this->listOptions('categories', isset($_POST['category']) ? $_POST['category'] : $this->options['default-category']); ?>
                </select>
                <?php if ($this->formErrors['category']) : ?>
                <br /><span class="recipe-press-error"><?php _e('A category is required', 'recipe-press'); ?></span>
                <?php endif; ?>
            </td>
        </tr>
        <tr class="form-field">
            <th valign="top" scope="row" class="recipe-press-servings <?php $this->requiredField('servings'); ?>">
                <label for="servings"><?php _e('Servings', 'recipe-press'); ?></label>
            </th>
            <td colspan="3">
                <input name="servings" type="text" id="servings" value="<?php echo $_POST['servings']; ?>" style="width:25px" />
                <select name="servings_size" id="servings_size">
                    <?php echo $this->listOptions('options', $_POST['servings_size'], 'key', 'value', 'serving_size'); ?>
                </select>
                <?php if ($this->formErrors['servings']) : ?>
                <br /><span class="recipe-press-error"><?php _e('Servings are required', 'recipe-press'); ?></span>
                <?php endif; ?>
            </td>
        </tr>
        <tr class="form-field">
            <th valign="top" scope="row" class="recipe-press-prep <?php $this->requiredField('prep_time'); ?>">
                <label for="prep_time"><?php _e('Prep Time', 'recipe-press'); ?></label>
            </th>
            <td>
                <input name="prep_time" type="text" id="prep_time" value="<?php echo $_POST['prep_time']; ?>" style="width:25px" /> minutes
                <?php if ($this->formErrors['prep_time']) : ?>
                <br /><span class="recipe-press-error"><?php _e('Prep time is required', 'recipe-press'); ?></span>
                <?php endif; ?>
            </td>
            <th valign="top" scope="row" class="recipe-press-cook <?php $this->requiredField('cook_time'); ?>">
                <label for="cook_time"><?php _e('Cook Time', 'recipe-press'); ?></label>
            </th>
            <td>
                <input name="cook_time" type="text" id="cook_time" value="<?php echo $_POST['cook_time']; ?>" style="width:25px" /> minutes
                <?php if ($this->formErrors['cook_time']) : ?>
                <br /><span class="recipe-press-error"><?php _e('Cook time is required', 'recipe-press'); ?></span>
                <?php endif; ?>
            </td>
        </tr>
        <tr class="form-field">
            <th valign="top" scope="row" class="recipe-press-ingredients <?php $this->requiredField('ingredients'); ?>"
                ><label for="ingredients"><?php _e('Ingredients', 'recipe-press'); ?></label>
            </th>
            <td colspan="3">
                <table id="rp_ingredients" class="form-table editrecipe">
                    <thead>
                        <tr class="form-field">
                            <th><?php _e('Quantity', 'recipe-press'); ?></th>
                            <th><?php _e('Size', 'recipe-press'); ?></th>
                            <th><?php _e('Ingredient', 'recipe-press'); ?></th>
                            <th><?php _e('Notes', 'recipe-press'); ?></th>
                        </tr>
                    </thead>
                    <tbody id="rp_ingredients_body">
                        <tr style="display:none">
                            <td id="rp_drag_icon" style="display:none"></td>
                            <td id="rp_size_column">
                                <select name="ingredientsCOPY[NULL][size]">
                                    <option value="none">No Size</option>
                                    <?php echo $this->listOptions('options', ($ingredient['size']) ? $ingredient['size'] : NULL, 'key', 'value', 'ingredient_size'); ?>
                                </select>
                            </td>
                        </tr>
                        <?php foreach($_POST['ingredients'] as $id=>$ingredient) : ?>
                        <tr id="rp_ingredient_<?php echo $id; ?>">
                            <td>
                                    <?php if ($ingredient['size'] != 'divider' and $ingredient['size']) : ?>
                                <input type="text" name="ingredients[<?php echo $id; ?>][quantity]" value="<?php echo $ingredient['quantity']; ?>" style="width:60px;" /></td>
                                <?php endif; ?>
                            <td>
                                    <?php if ($ingredient['size'] != 'divider' and $ingredient['size']) : ?>

                                <select name="ingredients[<?php echo $id; ?>][size]">
                                    <option value="none">No Size</option>
                                            <?php echo $this->listOptions('options', ($ingredient['size']) ? $ingredient['size'] : NULL, 'key', 'value', 'ingredient_size'); ?>
                                </select>
                                    <?php else : ?>
                                <input type="text" name="ingredients[<?php echo $id; ?>][size]" value="divider" style="width:55px;" readonly="readonly" /></td>


                                <?php endif; ?>
                            </td>
                            <td>
                                <input type="text" name="ingredients[<?php echo $id; ?>][item]" value="<?php echo $ingredient['item']; ?>" />
                            </td>
                            <td>
                                <input type="text" name="ingredients[<?php echo $id; ?>][notes]" value="<?php echo $ingredient['notes']; ?>" />

                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <p id="rp_ingredient_actions"><a onclick="rp_add_ingredient()" style="cursor:pointer"><?php _e('Add Ingredient', 'recipe-press'); ?></a> | <a onclick="rp_add_divider()" style="curser:pointer"><?php _e('Add Divider', 'recipe-press'); ?></a></p>
                <?php if ($this->formErrors['ingredients']) : ?>
                <br /><span class="recipe-press-error"><?php _e('Ingredients are required', 'recipe-press'); ?></span>
                <?php endif; ?>
                <!-- This is the ingredients item line to copy -->
                <select name="sizeselect" id="sizeselect" style="display:none">
                    <?php echo $this->listOptions('options', NULL, 'key', 'value', 'ingredient_size'); ?>
                </select>
            </td>
        </tr>
        <tr class="form-field">
            <th valign="top" scope="row" class="recipe-press-instructions <?php $this->requiredField('instructions'); ?>">
                <label for="instructions"><?php _e('Instructions', 'recipe-press'); ?></label>
            </th>
            <td colspan="3">
                <textarea class="rp_textarea rp_instructions_field" id="instructions" name="instructions"><?php echo stripslashes($_POST['instructions']); ?></textarea>
                <?php if ($this->formErrors['instructions']) : ?>
                <br /><span class="recipe-press-error"><?php _e('Instructions are required', 'recipe-press'); ?></span>
                <?php endif; ?>
            </td>
        </tr>

        <?php if ($this->showCaptcha) : ?>
        <tr class="form-field">
            <th valign="top" scope="row"><label for="recaptcha_response_field">&nbsp;</label></th>
            <td colspan="3">
                    <?php echo recaptcha_get_html($this->options['recaptcha-public']); ?>
                <input type="hidden" name="check_captcha" value="1" readonly="readonly" />
                    <?php if ($this->formErrors['validate']) : ?>
                <br /><span class="recipe-press-error"><?php _e('Please enter the words above.', 'recipe-press'); ?></span>
                    <?php endif; ?>
            </td>
            <?php elseif ($this->formErrors['validate']->is_valid) : ?>
            <td colspan="3">
                    <?php _e('Thank you for entering valid captcha text.', 'recipe-press'); ?>
                <input type="hidden" name="check_captcha" value="0" readonly="readonly" />

            </td>

        </tr>
        <?php endif; ?>

        <?php if (!$this->options['require-login'] and !is_user_logged_in()) : ?>
        <tr class="form-field">
            <th value="top" scope="row" class="recipe-press-instructions <?php $this->requiredField('submitter'); ?>"><label for="submitter"><?php _e('Your Name', 'recipe-press'); ?></th>
            <td colspan="3">
                <input type="text" name="submitter" size="40" value="<?php echo $_POST['submitter']; ?>" />
                    <?php if ($this->formErrors['submitter']) : ?>
                <br /><span class="recipe-press-error"><?php _e('Please enter your name.', 'recipe-press'); ?></span>
                    <?php endif; ?>
            </td>
        </tr>
        <tr class="form-field">
            <th value="top" scope="row" class="recipe-press-instructions <?php $this->requiredField('submitter_email'); ?>"><label for="submitter_email"><?php _e('Your Email', 'recipe-press'); ?></th>
            <td colspan="3">
                <input type="text" name="submitter_email" size="40" value="<?php echo $_POST['submitter_email']; ?>" />
                    <?php if ($this->formErrors['submitter_email']) : ?>
                <br /><span class="recipe-press-error"><?php _e('Please enter your email address.', 'recipe-press'); ?></span>
                    <?php endif; ?>
            </td>
        </tr>
        <?php endif; ?>

    </tbody>
</table>