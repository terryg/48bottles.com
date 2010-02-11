<p>
    <label for="<?php echo $this->get_field_id('title'); ?>">
        <?php _e('Widget Title (optional):', 'recipe-press'); ?>
    </label>
    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
</p>
<p>
    <label for="rss-items-4"><?php _e('How many categories would you like to display?', 'recipe-press'); ?></label>
    <select name="<?php echo $this->get_field_name('items'); ?>" id="<?php echo $this->get_field_id('items'); ?>">
        <?php
        for ( $i = 1; $i <= 20; ++$i )
            echo "<option value='$i' " . ( $items == $i ? "selected='selected'" : '' ) . ">$i</option>";
        ?>
    </select>
</p>
<p>
    <label for="<?php echo $this->get_field_id('order-by'); ?>"><?php _e('Sort by:', 'recipe-press'); ?></label>
    <select name="<?php echo $this->get_field_name('order-by'); ?>" id="<?php echo $this->get_field_id('order-by'); ?>">
        <option value="name" <?php selected($orderby, 'name'); ?> ><?php _e('Category Name', 'recipe-press'); ?></option>
        <option value="random" <?php selected($orderby, 'random'); ?> ><?php _e('Random Order', 'recipe-press'); ?></option>
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
