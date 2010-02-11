<!--
This template is used to display the recipe on a post or page. You can copy this file to your
Theme folder and make changes if you wish. All information for a recipe is provided in an
object named $this->recipe.
-->
<div class="recipe-header">
    <?php $this->recipe_image(); ?>
    <div class="recipe-about">
        <h3 class="recipe-title"><?php $this->recipe_title(); ?></h3>
        <h4 class="recipe-author">By: <?php $this->recipe_submitter(); ?></h4>

        <?php if ($this->get_recipe_notes()) : ?>
        <blockquote class="recipe-notes"><?php $this->recipe_notes(); ?></blockquote>
        <?php endif; ?>
    </div>
</div>

<div class="recipe-content">
    <div id="recipe-details-<?php $this->recipe_id(); ?>" class="recipe-section recipe-section-<?php $this->recipe_id(); ?>">
        <div class="recipe-section-title recipe-details">Details</div>

        <ul class="recipe-details">
            <?php $this->recipe_prep_time('double'); ?>
            <?php $this->recipe_cook_time('double'); ?>
            <?php $this->recipe_ready_time('double'); ?>
        </ul>
    </div>

    <?php if ($this->get_recipe_servings() ) : ?>
    <div id="recipe-servings-<?php $this->recipe_id(); ?>" class="recipe-section recipe-servings recipe-servings-<?php $this->recipe_id(); ?>">
        <div class="recipe-servings-detail"><?php $this->recipe_servings(); ?></div>
    </div>
    <?php endif; ?>

    <div id="recipe-ingredients-<?php $this->recipe_id(); ?>" class="recipe-section recipe-ingredients recipe-ingredients-<?php $this->recipe_id(); ?>">
        <?php $this->recipe_ingredients_header(); ?>
        <div class="recipe-block recipe-ingredients-block"><?php $this->recipe_ingredients(); ?></div>
    </div>

    <div id="recipe-instructions-<?php $this->recipe_id(); ?>" class="recipe-section recipe-instructions recipe-instructions-<?php $this->recipe_id(); ?>">
        <div class="recipe-section-title recipe-instructions-title">Directions</div>
        <div class="recipe-block recipe-instructions-block"><?php $this->recipe_instructions(); ?></div>
    </div>
</div>

<?php $this->showComments(); ?>