<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

/**
 * rp_Categories.class.php - Class for category management
 *
 * @package Recipe Press
 * @subpackage classes
 * @author GrandSlambert
 * @copyright 2009-2010
 * @access public
 */

class rp_Categories extends rp_Base {
    /* Set Variables */
    const menuName = 'recipe-press-categories';
    protected $view = 'categories';

    /**
     * Outputs the categories panel and processes user input.
     *
     * @global object $wpdb
     */
    function subPanel() {
        global $wpdb;

        switch ($_REQUEST['action']) {
            case 'delete':
                $moved = $wpdb->query('update ' . $this->tables['recipes'] . ' set `category` = ' . $this->options['default-category'] . ' where `category` = ' . $_GET['id']);
                $deleted = $wpdb->query('delete from `' . $this->tables['categories'] . '` where `id` = ' . $_GET['id'] . ' limit 1');
                /* translators: Message when a category is deleted - argument is the number of recipes moved to default category. */
                $this->message = sprintf(__('Category deleted. %d recipes were moved to the default category.', 'recipe-press'), $moved);
                break;
            case 'update':
                $data = $this->input();
                //$this->debug($data,true);
                $results = $wpdb->update( $this->tables['categories'], $data, array('id'=> $_POST['id']) );

                if ($results) {
                    /* translators: Message when a category is updated - argument is the category name */
                    $this->message = sprintf(__('Category "%s" updated.', 'recipe-press'), $data['name']);
                } else {
                    /* translators: General error when a category cannot be saved */
                    $this->message = __('There was an error trying to save the category. Perhaps you forgot to make any changes. Try again later, OK?', 'recipe-press');
                }

                break;
            case 'add':
                $data = $this->input();

                if ($data['name'] == '') {
                    /* translators: Error when a category name field is left blank on add category. */
                    $this->message = __('Category name field was left blank, category could not be created.', 'recipe-press');
                }
                else {
                    $data['created'] =$this->now();
                    $results = $wpdb->insert( $this->tables['categories'], $data);

                    if ($results) {
                        /* translators: Message when a category is created - argument is the category name. */
                        $this->message = sprintf(__('Category "%s" created.', 'recipe-press'), $data['name']);
                    } else {
                        /* translators: General error when a category cannot be created. */
                        $this->message = __('There was an error trying to create the category. Try again later, OK?', 'recipe-press');
                    }
                }
                break;
            case 'edit':
                $this->category = $wpdb->get_row('select * from ' . $this->tables['categories'] . ' where `id` = ' . $_GET['id']);
                $this->view = 'edit_category';
                break;
            default:
        }

        include($this->viewsPath . $this->view . '.php');
    }

    /**
     * Process the form POST input for categories.
     *
     * @return array
     */
    function input() {
        $slug = $this->slugify($_POST['slug'], $_POST['name'], 'categories');

        /* Process the image */
        if ($_FILES['image']  and !$_FILES['image']['error']) {
            $media_id = $this->processImage($slug, 'category');
        } else {
            $media_id = $_POST['media_id'];
        }

        return array(
        'media_id'    => $media_id,
        'parent'      => $_POST['parent'],
        'name'        => $_POST['name'],
        'slug'        => $slug,
        'description' => $_POST['description'],
        'status'      => $_POST['status'],
        );

    }

    /**
     * Method for the recipe-cats shortcode
     *
     * @param array $atts
     * @return string
     */
    function shortcode($atts) {
        extract(shortcode_atts(array(
            // Query Attributes
            'parent'        => NULL,
            'sort_column'   => 'name',
            'sort_order'    => 'asc',
            'limit'         => NULL,

            // Display Attributes
            'title'	=> NULL,
            'ul_class'	=> 'recipe-cat-list',
            'li_class'	=> 'recipe-cat-item',
            'show_count'=> false,
            'count_pre' => ' - ',
            'count_suf' => NULL
            ), $atts));

        $options = array('order-by' => 'name');

        if ($parent) {
            $options['parent'] = $parent;
        }

        $categories = $this->getRecordsCascade('categories', $options);

        if ($title)
            $output.= "\n<h3>" . $title . "</h3>\n";

        $output.= '<ul class="' . $ul_class . '">';
        $output.= $this->listCategories($categories, array('li_class'=>$li_class, 'show_count'=>$show_count, 'count_pre'=>$count_pre, 'count_suf'=>$count_suf));
        $output.= '</ul>';

        return $output;
    }

    /**
     * Method to list categories for an HTML list.
     *
     * @param array $categories     Array containing one object for each category.
     * @param array $options        Array containing options for display of categories.
     * @return string
     */
    function listCategories($categories = NULL, $options = NULL) {
        if ( !is_array($categories) )
            return;

        $displayPage = $this->options['display-page'];
        $frontPage = get_option('page_on_front');
        $permalinktype = get_option('permalink_structure');

        if (!$permalinktype ) {
            $linkpre = get_page_link($this->options['display-page']);

            if ( $displayPage == $frontPage ) {
                $linkpre.= '?page_id=' . $displayPage . '&amp;';
            } else {
                $linkpre.= '&amp;';
            }
        }
        else {
            $linkpre = get_page_link($this->options['display-page']);

            if ($displayPage == $frontPage) {
                $linkpre.= '/';
            }
        }

        if (!$options['li_class']) {
            $options['li_class'] = 'rp_category_list_item';
        }

        if ($options['target']) {
            $target = 'target="' . $options['target'] . '"';
        } else {
            unset ($target);
        }

        if ($options['icon-size']) {
            $size = array($options['icon-size'], $options['icon-size']);
        } else {
            $size = array($this->options['widget-icon-size'], $this->options['widget-icon-size']);
        }

        $lipre = "\n";

        foreach ($categories as $category) {
            if ($category->level > 1 and $category->level > $lastlevel) {
                ++$levels;
                $output.= "\n" . '<ul class="sub-group">' . "\n";
                $lipre = '';
            }

            if ($category->level < $lastlevel) {
                --$levels;
                $output.= "</li>\n</ul>\n";
            }

            if ($options['show-icon'] and $category->media_id) {
               $icon = wp_get_attachment_image( $category->media_id, $size, false );
            } else {
                $icon = '';
            }

            if (!$permalinktype) {
                $output.= $lipre . '<li class="' . $options['li_class'] . '">' . $icon . ' <a href="' . $linkpre . 'category=' . $category->slug . '" '. $target . '>' . esc_attr(stripslashes_deep($category->name)) . '</a> ';
            }
            else {
                $output.= $lipre . '<li class="' . $options['li_class'] . '">' . $icon . ' <a href="' . $linkpre . $category->slug . '/view/" '. $target . '>' . esc_attr(stripslashes_deep($category->name)) . '</a> ';
            }

            if ($options['show_count']) {
                $output.= $options['count_pre'] . $this->getCount('recipes', 'active', $category->id) . $options['count_suf'];
            }

            $lastlevel = $category->level;
            $lipre = "</li>\n";
        }

        $output.= "</li>\n";

        for ($ctr = $levels; $ctr > 0; --$ctr) {
            $output.= "\n</ul>";
        }

        return $output;
    }

    /* Template Tags */

    public function get_category_id() {
        return $this->category->id;
    }

    public function category_id() {
        echo $this->get_category_id();
    }

    public function get_category_media_id() {
        return $this->category->media_id;
    }

    public function category_media_id() {
        echo $this->get_category_media_id();
    }

    public function get_category_parent() {
        return $this->category->parent;
    }

    public function category_parent() {
        echo $this->get_category_parent();
    }

    public function get_category_name() {
        return stripslashes_deep($this->category->name);
    }

    public function category_name() {
        echo $this->get_category_name();
    }

    public function get_category_slug() {
        return $this->category->slug;
    }

    public function category_slug() {
        echo $this->get_category_slug();
    }

    public function get_category_description() {
        return stripslashes_deep($this->category->description);
    }

    public function category_description() {
        echo $this->get_category_description();
    }

    public function get_category_status() {
        return $this->category->status;
    }

    public function category_status() {
        echo $this->get_category_status();
    }

    public function get_category_created($format = 'Y-m-d h:i:s') {
        return date($format, strtotime($this->category->created));
    }

    public function category_created($format = 'Y-m-d h:i:s') {
        echo $this->get_category_created();
    }

    public function get_category_modified($format = 'Y-m-d h:i:s') {
        return date($format, strtotime($this->category->modified));
    }

    public function category_modified($format = 'Y-m-d h:i:s') {
        echo $this->get_category_modified($format);
    }
}