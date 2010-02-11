<?php
/* Widget for the Beer Press Plugin */

/* Class Declaration */
class bp_Widget_Category extends WP_Widget {
    var $options    = array();

    /**
     * Constructor
     */
    function bp_Widget_Category() {
        /* translators: The description of the Category List widget on the Appearance->Widgets page. */
        $widget_ops = array('description' => __('List recipe categories on your sidebar. By TerryLorber.', 'beer-press') );
        /* translators: The title for the Category List widget. */
        $this->WP_Widget('beer_press_category_widget', __('Beer Press &raquo; Categories', 'beer-press'), $widget_ops);

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

        $categories = $BEERPRESSOBJ->getRecordsCascade('categories', $options);

        if ($target = $instance['target']) {
            $options['target'] = $target;
        }

        $options['show-icon'] = $instance['show-icon'];
        $options['icon-size'] = $instance['icon-size'];
        $options['li_class'] = 'bp_widget_category_list_item';
        $page = get_page($this->options['submit-page']);

        print $before_widget;
        if ( $title )
            print $before_title . $title . $after_title;

        print '<ul class="bp_widget_category_list">';
        $object = new bp_Categories;
        print $object->listCategories($categories, $options);

        if ($submitlink)
            print '<li class="recipe-submit"><a href="' . get_page_link($page->ID) . '">' . $this->options['submit-title'] . '</a></li>';

        print '</ul>';
        print $after_widget;
    }

    /** @see WP_Widget::form */
    function form($instance) {
        global $BEERPRESSOBJ;

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

add_action('widgets_init', create_function('', 'return register_widget("bp_Widget_Category");'));