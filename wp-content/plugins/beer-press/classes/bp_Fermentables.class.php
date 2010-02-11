<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

/**
 * bp_Fermentables.classes.php - Class for fermentable management
 *
 * @package Beer Press
 * @subpackage classes
 * @author tgl@rideside.net
 * @copyright 2010
 * @access public
 */
 
class bp_Fermentables extends bp_Base {
    /* Set Variables */
    const menuName = 'beer-press-fermentables';
    protected $view = 'fermentables';

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
                $fermentable = $this->getOneRecord('fermentables', $_GET['id']);
                /* translators: Message when fermentable is deleted - argument is the fermentable name. */
                $this->message = sprintf(__('"%s" fermentable successfully deleted.', 'beer-press'), $fermentable->name);
                $wpdb->update($this->tables['fermentables'], array('status'=>'trash'), array('id'=>$fermentable->id));
                break;
            case 'activate':
                $fermentable = $this->getOneRecord('fermentables', $_GET['id']);
                /* translators: Message when fermentable is activated - argument is the fermentable name. */
                $this->message = sprintf(__('"%s" fermentable successfully activated.', 'beer-press'), $fermentable->name);
                $wpdb->update($this->tables['fermentables'], array('status'=>'active'), array('id'=>$fermentable->id));
                break;
            case 'update':
                $results = $wpdb->update( $this->tables['fermentables'], $this->input(), array('id'=>$_POST['id']) );
                /* translators: Message when fermentable is updated - argument is the recipe title. */
                $this->message = sprintf(__('"%s" fermentable successfully updated.', 'beer-press'), $_POST['name']);
                break;
            case 'edit':
                $this->fermentable = $this->getOneRecord('fermentables', $_GET['id']);
                $this->view ='edit_fermentable';
                break;
        }

        include($this->viewsPath . $this->view . '.php');
    }

    /**
     * Process the form POST input for fermentables.
     *
     * @return array
     */
    function input() {
        global $current_user;
        get_currentuserinfo();

        $slug = $this->slugify($_POST['slug'], $_POST['name'], 'fermentables');

        if ($_POST['create-page']) {
            if (!$parent = $_POST['page']) {
                $parent = $this->options['fermentable-parent'];
            }

            $bp_new_post = array(
                'post_title'    => ucwords($_POST['name']),
                'post_content'  => '[recipe-fermentables item=' . $slug . ' /]',
                'post_type'     => 'page',
                'post_parent'   => $parent,
                'post_status'   => $this->options['fermentable-parent-status'],
                'post_author'   => $current_user->ID
            );

            $_POST['page'] = wp_insert_post($bp_new_post);
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
    public function get_fermentable_id() {
        return $this->fermentable->id;
    }

    public function fermentable_id() {
        echo $this->get_fermentable_id();
    }

    public function get_fermentable_name() {
        return $this->fermentable->name;
    }

    public function fermentable_name() {
        echo $this->get_fermentable_name();
    }

    public function get_fermentable_slug() {
        return $this->fermentable->slug;
    }

    public function fermentable_slug() {
        echo $this->get_fermentable_slug();
    }

    public function get_fermentable_page() {
        return $this->fermentable->page;
    }

    public function fermentable_page() {
        echo $this->get_fermentable_page();
    }

    public function get_fermentable_url($link = false, $target='_blank') {
        if ($link) {
            return '<a href="' . esc_url($this->fermentable->url) . '" target="' . $target . '">' . $this->fermentable->url . '</a>';
        }
        return $this->fermentable->url;
    }

    public function fermentable_url($link = false) {
        echo $this->get_fermentable_url($link);
    }

    public function get_fermentable_recipes($count = false) {
        if ($count) {
            return count(explode(',', $this->fermentable->recipes));
        } else {
            return $this->fermentable->recipes;
        }
    }

    public function fermentable_recipes($count = false) {
        echo $this->get_fermentable_recipes($count);
    }

    public function get_fermentable_status() {
        return $this->fermentable->status;
    }

    public function fermentable_status() {
        echo $this->get_fermentable_status();
    }

    public function get_fermentable_modified($format = 'Y-m-d h:i:s') {
        return date($format, strtotime($this->fermentable->modified));
    }

    public function fermentable_modified($format = 'Y-m-d h:i:s') {
        echo $this->get_fermentable_modified($format);
    }
}
