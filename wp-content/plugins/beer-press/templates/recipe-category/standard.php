<!--
This template is used to display the recipe categories on a post or page. You can copy this file to your
Theme folder and make changes if you wish. All information for a category is provided in an
object named $this->category with recipes in $this->recipes.
-->

<div id="category_<?php echo $this->category_id(); ?>" class="rp_category_header">
    <p id="category_text_<?php echo $this->category_id(); ?>"><?php echo $this->category_name(); ?></p>
</div>