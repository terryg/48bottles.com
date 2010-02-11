<!--
This template is used to display the each recipe in a list. You can copy this file to your
Theme folder and make changes if you wish. All information for a category is provided in an
object named $this->recipe.
-->

<div id="recipe-block-<?php $this->recipe_id(); ?>" class="recipe-list-block recipe-list-block-standard">
    <a href="<?php $this->recipeLink(); ?>"><?php $this->recipe_image(array(75,75)); ?></a>

    <h3 class="recipe-list-title recipe-list-title-standard"><a href="<?php $this->recipeLink(); ?>"><?php $this->recipe_title(); ?></a></h3>
    <h4 class="recipe-list-author recipe-list-author-standard">By: <?php $this->recipe_submitter(); ?></h4>

    <div id="recipe-notes-<?php $this->recipe_id(); ?>" class="recipe-list-notes recipe-list-notes-standard">
        <?php $this->recipe_notes( array('length'=>75) ); ?>
    </div>
    <div class="cleared"></div>
</div>
