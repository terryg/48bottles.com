<p>
    <label for="<?php echo $this->get_field_id('title'); ?>">
        <?php _e('Widget Title (optional):', 'recipe-press'); ?>
    </label>
    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
</p>
<p>
    <label for="rss-items-4">How many items would you like to display?</label>
    <select name="<?php echo $this->get_field_name('items'); ?>" id="<?php echo $this->get_field_id('items'); ?>">
        <?php
        for ( $i = 1; $i <= 20; ++$i )
            echo "<option value='$i' " . ( $items == $i ? "selected='selected'" : '' ) . ">$i</option>";
        ?>
    </select>
</p>
<p>
    <label for="<?php echo $this->get_field_id('type'); ?>">
        <?php _e('Display Type:', 'recipe-press'); ?>
    </label>
    <select name="<?php echo $this->get_field_name('type'); ?>" id="<?php echo $this->get_field_id('type'); ?>">
        <option value="newest" <?php selected($type, 'newest'); ?> ><?php _e('Newest Recipes', 'recipe-press'); ?></option>
        <option value="random" <?php selected($type, 'random'); ?> ><?php _e('Random Recipes', 'recipe-press'); ?></option>
        <option value="popular" <?php selected($type, 'popular'); ?> ><?php _e('Most Popular', 'recipe-press'); ?></option>
        <option value="featured" <?php selected($type, 'featured'); ?> ><?php _e('Featured', 'recipe-press'); ?></option>
        <option value="updated" <?php selected($type, 'updated'); ?> ><?php _e('Recently Updated', 'recipe-press'); ?></option>
    </select>
</p>
<p>
    <label for="<?php echo $this->get_field_id('sort_order'); ?>">
        <?php _e('Sort Order:', 'recipe-press'); ?>
    </label>
    <select name="<?php echo $this->get_field_name('sort_order'); ?>" id="<?php echo $this->get_field_id('type'); ?>">
        <option value="asc" <?php selected($sort_order, 'asc'); ?> ><?php _e('Ascending', 'recipe-press'); ?></option>
        <option value="desc" <?php selected($sort_order, 'desc'); ?>><?php _e('Descending', 'recipe-press'); ?></option>
    </select>
</p>
<p>
    <label for="<?php echo $this->get_field_id('category'); ?>">
        <?php _e('Recipe Category:', 'recipe-press'); ?>
    </label>
    <select name="<?php echo $this->get_field_name('category'); ?>" id="<?php echo $this->get_field_id('category'); ?>">
        <option value="all"><?php _e('Show All Categories', 'recipe-press'); ?></option>
        <?php print $RECIPEPRESSOBJ->listOptions('categories', $category); ?>
    </select>
</p>
<p>
    <label for="<?php echo $this->get_field_id('show-icon'); ?>">
        <input type="checkbox" name="<?php echo $this->get_field_name('show-icon'); ?>" id ="<?php echo $this->get_field_id('show-icon'); ?>" <?php checked($showicon, 1); ?> value="1" />
        <?php _e('Show Icon?', 'recipe-press'); ?>
    </label>
</p>
<p>
    <label for="<?php echo $this->get_field_id('icon-size'); ?>">
        <?php _e('Icon Size (square):', 'recipe-press'); ?>
    </label>
    <input class="widefat" id="<?php echo $this->get_field_id('icon-size'); ?>" name="<?php echo $this->get_field_name('icon-size'); ?>" type="text" value="<?php echo $iconsize; ?>" style="width:50px" />
</p>
<p>
    <label for="<?php echo $this->get_field_id('target'); ?>">
        <?php _e('Link Target:', 'recipe-press'); ?>
    </label>
    <select name="<?php echo $this->get_field_name('target'); ?>" id="<?php echo $this->get_field_id('target'); ?>">
        <option value="0"><?php _e('None', 'recipe-press'); ?></option>
        <option value="_blank" <?php if ($linktarget == '_blank') echo 'selected'; ?>><?php _e('New Window', 'recipe-press'); ?></option>
        <option value="_top" <?php if ($linktarget == '_top') echo 'selected'; ?>><?php _e('Top Window', 'recipe-press'); ?></option>
    </select>
</p>
<p>
    <label for="<?php echo $this->get_field_id('submit_link'); ?>"><?php _e('Add link to Submit form?', 'recipe-press'); ?></label>
    <input name="<?php echo $this->get_field_name('submit_link'); ?>" type="checkbox" id="<?php echo $this->get_field_id('submit_link'); ?>" value="Y" <?php if ($submitlink == 'Y') print 'checked="checked"'; ?> />
</p>
