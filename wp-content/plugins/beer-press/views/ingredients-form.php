
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

        <?php
        if (!$this->recipe) {
            $this->recipe->comments_open = true;
            $this->recipe->hide_ingredients_header = false;
            $this->recipe->ingredients = array();

            for ($ctr=0; $ctr < $this->options['ingredients-fields']; ++$ctr) {
                array_push($this->recipe->ingredients, array('size'=>'bag'));
            }
        }
        ?>

        <?php foreach($this->recipe->ingredients as $id=>$ingredient) : ?>
        <tr id="rp_ingredient_<?php echo $id; ?>" class="<?php $this->class_name('row', 'ingredients'); ?>">

            <td class="<?php $this->class_name('td', 'ingredient-quantity'); ?>">
                    <?php if ($ingredient['size'] != 'divider' and $ingredient['size']) : ?>
                <input type="text" name="ingredients[<?php echo $id; ?>][quantity]" class="<?php $this->class_name('text', 'ingredient-quantity'); ?>" value="<?php echo $ingredient['quantity']; ?>" style="width:60px;" />
            </td>
                <?php endif; ?>

            <td class="<?php $this->class_name('td', 'ingredient-unit'); ?>">
                    <?php if ($ingredient['size'] != 'divider' and $ingredient['size']) : ?>

                <select name="ingredients[<?php echo $id; ?>][size]" class="<?php $this->class_name('select', 'ingredient-unit'); ?>">
                    <option value="none">No Size</option>
                    <?php echo $this->listOptions('options', $this->ingredient->size, 'key', 'value', 'ingredient_size'); ?>
                </select>
                    <?php else : ?>
                <input type="text" name="ingredients[<?php echo $id; ?>][size]" value="divider" style="width:55px;" readonly="readonly" />


                    <?php endif; ?>
            </td>

            <td class="<?php $this->class_name('td', 'ingredient-item'); ?>">
                <input type="text" name="ingredients[<?php echo $id; ?>][item]" class="<?php $this->class_name('text', 'ingredient-item'); ?>" value="<?php echo $ingredient['item']; ?>" />
            </td>

            <td class="<?php $this->class_name('td', 'ingredient-notes'); ?>">
                <?php if ($ingredient['size'] != 'divider' and $ingredient['size']) : ?>
                <input type="text" name="ingredients[<?php echo $id; ?>][notes]" class="<?php $this->class_name('text', 'ingredient-notes'); ?>" value="<?php echo $ingredient['notes']; ?>" />
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<p id="rp_ingredient_actions"><a onclick="rp_add_ingredient()" style="cursor:pointer"><?php _e('Add Ingredient', 'recipe-press'); ?></a> | <a onclick="rp_add_divider()" style="curser:pointer"><?php _e('Add Divider', 'recipe-press'); ?></a></p>
