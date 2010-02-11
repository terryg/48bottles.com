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
 
class bp_Hops extends bp_Base {
    /* Set Variables */
    const menuName = 'beer-press-hops';
    protected $view = 'hops';

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
                $hop = $this->getOneRecord('hops', $_GET['id']);
                /* translators: Message when hop is deleted - argument is the hop name. */
                $this->message = sprintf(__('"%s" hop successfully deleted.', 'beer-press'), $hop->name);
                $wpdb->update($this->tables['hops'], array('status'=>'trash'), array('id'=>$hop->id));
                break;
            case 'activate':
                $hop = $this->getOneRecord('hops', $_GET['id']);
                /* translators: Message when hop is activated - argument is the hop name. */
                $this->message = sprintf(__('"%s" hop successfully activated.', 'beer-press'), $hop->name);
                $wpdb->update($this->tables['hops'], array('status'=>'active'), array('id'=>$hop->id));
                break;
            case 'update':
                $results = $wpdb->update( $this->tables['hops'], $this->input(), array('id'=>$_POST['id']) );
                /* translators: Message when hop is updated - argument is the recipe title. */
                $this->message = sprintf(__('"%s" hop successfully updated.', 'beer-press'), $_POST['name']);
                break;
            case 'edit':
                $this->hop = $this->getOneRecord('hops', $_GET['id']);
                $this->view ='edit_hop';
                break;
        }

        include($this->viewsPath . $this->view . '.php');
    }

    /**
     * Process the form POST input for hops.
     *
     * @return array
     */
    function input() {
        global $current_user;
        get_currentuserinfo();

        $slug = $this->slugify($_POST['slug'], $_POST['name'], 'hops');

        if ($_POST['create-page']) {
            if (!$parent = $_POST['page']) {
                $parent = $this->options['hop-parent'];
            }

            $bp_new_post = array(
                'post_title'    => ucwords($_POST['name']),
                'post_content'  => '[recipe-hops item=' . $slug . ' /]',
                'post_type'     => 'page',
                'post_parent'   => $parent,
                'post_status'   => $this->options['hop-parent-status'],
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
    public function get_hop_id() {
        return $this->hop->id;
    }

    public function hop_id() {
        echo $this->get_hop_id();
    }

    public function get_hop_name() {
        return $this->hop->name;
    }

    public function hop_name() {
        echo $this->get_hop_name();
    }

    public function get_hop_slug() {
        return $this->hop->slug;
    }

    public function hop_slug() {
        echo $this->get_hop_slug();
    }

    public function get_hop_page() {
        return $this->hop->page;
    }

    public function hop_page() {
        echo $this->get_hop_page();
    }

    public function get_hop_url($link = false, $target='_blank') {
        if ($link) {
            return '<a href="' . esc_url($this->hop->url) . '" target="' . $target . '">' . $this->hop->url . '</a>';
        }
        return $this->hop->url;
    }

    public function hop_url($link = false) {
        echo $this->get_hop_url($link);
    }

    public function get_hop_recipes($count = false) {
        if ($count) {
            return count(explode(',', $this->hop->recipes));
        } else {
            return $this->hop->recipes;
        }
    }

    public function hop_recipes($count = false) {
        echo $this->get_hop_recipes($count);
    }

    public function get_hop_status() {
        return $this->hop->status;
    }

    public function hop_status() {
        echo $this->get_hop_status();
    }

    public function get_hop_modified($format = 'Y-m-d h:i:s') {
        return date($format, strtotime($this->hop->modified));
    }

    public function hop_modified($format = 'Y-m-d h:i:s') {
        echo $this->get_hop_modified($format);
    }
}
