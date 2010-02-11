<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

/**
 * rp_Ingredients.classes.php - Class for ingedient management
 *
 * @package Recipe Press
 * @subpackage classes
 * @author GrandSlambert
 * @copyright 2009-2010
 * @access public
 */
 
class rp_Ingredients extends rp_Base {
    /* Set Variables */
    const menuName = 'recipe-press-ingredients';
    protected $view = 'ingredients';

    /**
     * Displays the subpanel and processes user actions.
     *
     * @global object $wpdb
     */
    function subPanel() {
        global $wpdb, $current_user;
        get_currentuserinfo();

        switch ($_REQUEST['action']) {
            case 'trash':
                $ingredient = $this->getOneRecord('ingredients', $_GET['id']);
                /* translators: Message when ingredient is deleted - argument is the ingredient name. */
                $this->message = sprintf(__('"%s" ingredient successfully deleted.', 'recipe-press'), $ingredient->name);
                $wpdb->update($this->tables['ingredients'], array('status'=>'trash'), array('id'=>$ingredient->id));
                break;
            case 'activate':
                $ingredient = $this->getOneRecord('ingredients', $_GET['id']);
                /* translators: Message when ingredient is activated - argument is the ingredient name. */
                $this->message = sprintf(__('"%s" ingredient successfully activated.', 'recipe-press'), $ingredient->name);
                $wpdb->update($this->tables['ingredients'], array('status'=>'active'), array('id'=>$ingredient->id));
                break;
            case 'update':
                $results = $wpdb->update( $this->tables['ingredients'], $this->input(), array('id'=>$_POST['id']) );
                /* translators: Message when ingredient is updated - argument is the recipe title. */
                $this->message = sprintf(__('"%s" ingredient successfully updated.', 'recipe-press'), $_POST['name']);
                break;
            case 'edit':
                $this->ingredient = $this->getOneRecord('ingredients', $_GET['id']);
                $this->view ='edit_ingredient';
                break;
        }

        include($this->viewsPath . $this->view . '.php');
    }

    /**
     * Process the form POST input for ingredients.
     *
     * @return array
     */
    function input() {
        global $current_user;
        get_currentuserinfo();

        $slug = $this->slugify($_POST['slug'], $_POST['name'], 'ingredients');

        if ($_POST['create-page']) {
            if (!$parent = $_POST['page']) {
                $parent = $this->options['ingredient-parent'];
            }

            $rp_new_post = array(
                'post_title'    => ucwords($_POST['name']),
                'post_content'  => '[recipe-ingredients item=' . $slug . ' /]',
                'post_type'     => 'page',
                'post_parent'   => $parent,
                'post_status'   => $this->options['ingredient-parent-status'],
                'post_author'   => $current_user->ID
            );

            $_POST['page'] = wp_insert_post($rp_new_post);
        }

        return array(
        'name'      => $_POST['name'],
        'slug'      => $slug,
        'page'      => $_POST['page'],
        'url'       => $_POST['url'],
        'recipes'   => $_POST['recipes'],
        );

    }

    /* Template Tags */
    public function get_ingredient_id() {
        return $this->ingredient->id;
    }

    public function ingredient_id() {
        echo $this->get_ingredient_id();
    }

    public function get_ingredient_name() {
        return $this->ingredient->name;
    }

    public function ingredient_name() {
        echo $this->get_ingredient_name();
    }

    public function get_ingredient_slug() {
        return $this->ingredient->slug;
    }

    public function ingredient_slug() {
        echo $this->get_ingredient_slug();
    }

    public function get_ingredient_page() {
        return $this->ingredient->page;
    }

    public function ingredient_page() {
        echo $this->get_ingredient_page();
    }

    public function get_ingredient_url($link = false, $target='_blank') {
        if ($link) {
            return '<a href="' . esc_url($this->ingredient->url) . '" target="' . $target . '">' . $this->ingredient->url . '</a>';
        }
        return $this->ingredient->url;
    }

    public function ingredient_url($link = false) {
        echo $this->get_ingredient_url($link);
    }

    public function get_ingredient_recipes($count = false) {
        if ($count) {
            return count(explode(',', $this->ingredient->recipes));
        } else {
            return $this->ingredient->recipes;
        }
    }

    public function ingredient_recipes($count = false) {
        echo $this->get_ingredient_recipes($count);
    }

    public function get_ingredient_status() {
        return $this->ingredient->status;
    }

    public function ingredient_status() {
        echo $this->get_ingredient_status();
    }

    public function get_ingredient_modified($format = 'Y-m-d h:i:s') {
        return date($format, strtotime($this->ingredient->modified));
    }

    public function ingredient_modified($format = 'Y-m-d h:i:s') {
        echo $this->get_ingredient_modified($format);
    }
}