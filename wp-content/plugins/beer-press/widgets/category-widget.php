<?php
/* Widget for the Recipe Press Plugin */

/* Class Declaration */
class rp_Widget_Category extends WP_Widget {
    var $options    = array();

    /**
     * Constructor
     */
    function rp_Widget_Category() {
        /* translators: The description of the Category List widget on the Appearance->Widgets page. */
        $widget_ops = array('description' => __('List recipe categories on your sidebar. By GrandSlambert.', 'recipe-press') );
        /* translators: The title for the Category List widget. */
        $this->WP_Widget('recipe_press_category_widget', __('Recipe Press &raquo; Categories', 'recipe-press'), $widget_ops);

        $this->pluginPath = WP_CONTENT_DIR . '/plugins/' . plugin_basename(dirname(__FILE__));
        $this->options = get_option('recipe-press-options');
    }

    /**
     * Widget code
     */
    function widget($args, $instance) {
        global $RECIPEPRESSOBJ;

        if ( isset($instance['error']) && $instance['error'] ) {
            return;
        }

        extract($args, EXTR_SKIP);

        $options = array();
        $options['limit'] = $instance['items'];
        //$options['status'] = 'active';

        $title 		= $instance['title'];
        $submitlink	= $instance['submit_link'];

        if ($category = $instance['category']) {
            $options['category'] = $category;
        }

        switch ($instance['order-by']) {
            case 'random':
                $options['order-by'] = 'rand()';
                break;
            default:
                $options['order-by'] = 'name';
                break;
        }

        $categories = $RECIPEPRESSOBJ->getRecordsCascade('categories', $options);

        if ($target = $instance['target']) {
            $options['target'] = $target;
        }

        $options['show-icon'] = $instance['show-icon'];
        $options['icon-size'] = $instance['icon-size'];
        $options['li_class'] = 'rp_widget_category_list_item';
        $page = get_page($this->options['submit-page']);

        print $before_widget;
        if ( $title )
            print $before_title . $title . $after_title;

        print '<ul class="rp_widget_category_list">';
        $object = new rp_Categories;
        print $object->listCategories($categories, $options);

        if ($submitlink)
            print '<li class="recipe-submit"><a href="' . get_page_link($page->ID) . '">' . $this->options['submit-title'] . '</a></li>';

        print '</ul>';
        print $after_widget;
    }

    /** @see WP_Widget::form */
    function form($instance) {
        global $RECIPEPRESSOBJ;

        if ($instance) {
            $title 	= esc_attr($instance['title']);
            $items	= esc_attr($instance['items']);
            $linktarget = esc_attr($instance['target']);
            $orderby    = esc_attr($instance['order-by']);
            $showicon   = esc_attr($instance['show-icon']);
            $iconsize   = esc_attr($instance['icon-size']);
        } else {
            $linktarget = $this->options['widget-target'];
            $orderby    = $this->options['widget-orderby'];
            $showicon   = $this->options['widget-show-icon'];
            $iconsize   = $this->options['widget-icon-size'];
        }

        if ( $items < 1 || 20 < $items ) {
            $items = $this->options['widget-items'];
        }

        include( $this->pluginPath . '/category-form.php');
    }
}

add_action('widgets_init', create_function('', 'return register_widget("rp_Widget_Category");'));