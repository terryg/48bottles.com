<!--
This template is used to display the recipe on a post or page. You can copy this file to your
Theme folder and make changes if you wish. All information for a recipe is provided in an
object named $this->recipe.
-->
<h3 class="recipe-title"><?php $this->recipe_title(); ?></h3>
<h4 class="recipe-author">Submitted by: <?php $this->recipe_submitter(); ?></h4>

<?php if ($this->recipe_notes()) : ?>
<blockquote class="recipe-notes"><?php $this->recipe_notes(); ?></blockquote>
<?php endif; ?>

<dl class="recipe-list">
    <dt class="recipe-section">Details</dt>

    <dd class="recipe-times">
        <ul>
            <?php $this->recipe_prep_time(); ?>
            <?php $this->recipe_cook_time(); ?>
            <?php $this->recipe_ready_time(); ?>
            <?php $this->recipe_servings(); ?>
        </ul>
    </dd>

    <dt class="recipe-section">Ingredients</dt>
    <dd class="recipe-ingredients"><?php $this->recipe_ingredients(); ?></dd>
    <dt class="recipe-section">Directions</dt>
    <dd class="recipe-instructions"><?php $this->recipe_instructions(); ?></dd>
</dl>

<?php $this->showComments(); ?>