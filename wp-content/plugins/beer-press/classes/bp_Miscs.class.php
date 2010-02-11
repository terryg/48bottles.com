<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

/**
 * bp_Miscs.classes.php - Class for misc management
 *
 * @package Beer Press
 * @subpackage classes
 * @author tgl@rideside.net
 * @copyright 2010
 * @access public
 */
 
class bp_Miscs extends bp_Base {
    /* Set Variables */
    const menuName = 'beer-press-miscs';
    protected $view = 'miscs';

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
                $misc = $this->getOneRecord('miscs', $_GET['id']);
                /* translators: Message when misc is deleted - argument is the misc name. */
                $this->message = sprintf(__('"%s" misc successfully deleted.', 'beer-press'), $misc->name);
                $wpdb->update($this->tables['miscs'], array('status'=>'trash'), array('id'=>$misc->id));
                break;
            case 'activate':
                $misc = $this->getOneRecord('miscs', $_GET['id']);
                /* translators: Message when misc is activated - argument is the misc name. */
                $this->message = sprintf(__('"%s" misc successfully activated.', 'beer-press'), $misc->name);
                $wpdb->update($this->tables['miscs'], array('status'=>'active'), array('id'=>$misc->id));
                break;
            case 'update':
                $results = $wpdb->update( $this->tables['miscs'], $this->input(), array('id'=>$_POST['id']) );
                /* translators: Message when misc is updated - argument is the recipe title. */
                $this->message = sprintf(__('"%s" misc successfully updated.', 'beer-press'), $_POST['name']);
                break;
            case 'edit':
                $this->misc = $this->getOneRecord('miscs', $_GET['id']);
                $this->view ='edit_misc';
                break;
        }

        include($this->viewsPath . $this->view . '.php');
    }

    /**
     * Process the form POST input for miscs.
     *
     * @return array
     */
    function input() {
        global $current_user;
        get_currentuserinfo();

        $slug = $this->slugify($_POST['slug'], $_POST['name'], 'miscs');

        if ($_POST['create-page']) {
            if (!$parent = $_POST['page']) {
                $parent = $this->options['misc-parent'];
            }

            $bp_new_post = array(
                'post_title'    => ucwords($_POST['name']),
                'post_content'  => '[recipe-miscs item=' . $slug . ' /]',
                'post_type'     => 'page',
                'post_parent'   => $parent,
                'post_status'   => $this->options['misc-parent-status'],
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
    public function get_misc_id() {
        return $this->misc->id;
    }

    public function misc_id() {
        echo $this->get_misc_id();
    }

    public function get_misc_name() {
        return $this->misc->name;
    }

    public function misc_name() {
        echo $this->get_misc_name();
    }

    public function get_misc_slug() {
        return $this->misc->slug;
    }

    public function misc_slug() {
        echo $this->get_misc_slug();
    }

    public function get_misc_page() {
        return $this->misc->page;
    }

    public function misc_page() {
        echo $this->get_misc_page();
    }

    public function get_misc_url($link = false, $target='_blank') {
        if ($link) {
            return '<a href="' . esc_url($this->misc->url) . '" target="' . $target . '">' . $this->misc->url . '</a>';
        }
        return $this->misc->url;
    }

    public function misc_url($link = false) {
        echo $this->get_misc_url($link);
    }

    public function get_misc_recipes($count = false) {
        if ($count) {
            return count(explode(',', $this->misc->recipes));
        } else {
            return $this->misc->recipes;
        }
    }

    public function misc_recipes($count = false) {
        echo $this->get_misc_recipes($count);
    }

    public function get_misc_status() {
        return $this->misc->status;
    }

    public function misc_status() {
        echo $this->get_misc_status();
    }

    public function get_misc_modified($format = 'Y-m-d h:i:s') {
        return date($format, strtotime($this->misc->modified));
    }

    public function misc_modified($format = 'Y-m-d h:i:s') {
        echo $this->get_misc_modified($format);
    }
}
