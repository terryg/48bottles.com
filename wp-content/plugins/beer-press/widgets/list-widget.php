<?php
/* Beer List Widget for the Beer Press Plugin */

/* Class Declaration */
class bp_Widget_List_Recipes extends WP_Widget {
    var $options  = array();

    /**
     * Constructor
     */
    function bp_Widget_List_Recipes() {
        /* translators: The description of the Recpipe List widget on the Appearance->Widgets page. */
        $widget_ops = array('description' => __('List recipes on your sidebar. By TerryLorber.', 'beer-press') );
        /* translators: The title for the Recipe List widget. */
        $this->WP_Widget('beer_press_list_widget', __('Beer Press &raquo; List', 'beer-press'), $widget_ops);

        $this->pluginPath = WP_CONTENT_DIR . '/plugins/' . plugin_basename(dirname(__FILE__));
        $this->options = get_option('beer-press-options');
    }

    /**
     * Widget code
     */
    function widget($args, $instance) {
        global $BEERPRESSOBJ;

        if ( isset($instance['error']) && $instance['error'] ) {
            return;
        }

        extract($args, EXTR_SKIP);

        $options = array();
        $options['limit'] = $instance['items'];
        $options['type'] = $instance['type'];
        $options['sortby'] = $instance['sort_order'];
        $options['status'] = 'active';

        $title 		= $instance['title'];
        $submitlink	= $instance['submit_link'];

        if ($category = $instance['category']) {
            $options['category'] = $category;
        }

        $recipes = $RECIPEPRESSOBJ->getRecipes($options);

        if ($target = $instance['target']) {
            $options['target'] = $target;
        }

        $options['show-icon'] = $instance['show-icon'];
        $options['icon-size'] = $instance['icon-size'];
        $options['li_class'] = 'bp_widget_list_item';
        $page = get_page($this->options['submit-page']);

        print $before_widget;
        if ( $title ) {
            print $before_title . $title . $after_title;
        }

        print '<ul class="recipe-widget-list">';
        $recipesobj = new bp_Recipe_Base;
        print $recipesobj->listRecipes($recipes, $options);

        if ($submitlink)
            print '<li class="recipe-submit"><a href="' . get_page_link($page->ID) . '">' . $recipesobj->options['submit-title'] . '</a></li>';

        print '</ul>';
        print $after_widget;
    }

    /** @see WP_Widget::form */
    function form($instance) {
        global $RECIPEPRESSOBJ;
        
        if ($instance) {
            $title      = esc_attr($instance['title']);
            $items      = esc_attr($instance['items']);
            $type       = $instance['type'];
            $sort_order = $instance['sort_order'];
            $category 	= esc_attr($instance['category']);
            $submitlink	= esc_attr($instance['submit_link']);
            $linktarget = esc_attr($instance['target']);
            $showicon   = esc_attr($instance['show-icon']);
            $iconsize   = esc_attr($instance['icon-size']);
        } else {
            $items      = $this->options['widget-items'];
            $type       = $this->options['widget-type'];
            $sort_order = $this->options['widget-sort'];
            $linktarget = $this->options['widget-target'];
            $showicon   = $this->options['widget-show-icon'];
            $iconsize   = $this->options['widget-icon-size'];
        }

        if ( $items < 1 || 20 < $items ) $items  = $this->options['widget-items'];

        include( $this->pluginPath . '/list-form.php');
    }
}

add_action('widgets_init', create_function('', 'return register_widget("bp_Widget_List_Recipes");'));