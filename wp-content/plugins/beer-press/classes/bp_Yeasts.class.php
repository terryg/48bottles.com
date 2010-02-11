<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

/**
 * bp_Yeasts.classes.php - Class for yeast management
 *
 * @package Beer Press
 * @subpackage classes
 * @author tgl@rideside.net
 * @copyright 2010
 * @access public
 */
 
class bp_Yeasts extends bp_Base {
    /* Set Variables */
    const menuName = 'beer-press-yeasts';
    protected $view = 'yeasts';

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
                $yeast = $this->getOneRecord('yeasts', $_GET['id']);
                /* translators: Message when yeast is deleted - argument is the yeast name. */
                $this->message = sprintf(__('"%s" yeast successfully deleted.', 'beer-press'), $yeast->name);
                $wpdb->update($this->tables['yeasts'], yarray('status'=>'trash'), array('id'=>$yeast->id));
                break;
            case 'activate':
                $yeast = $this->getOneRecord('yeasts', $_GET['id']);
                /* translators: Message when yeast is activated - argument is the yeast name. */
                $this->message = sprintf(__('"%s" yeast successfully activated.', 'beer-press'), $yeast->name);
                $wpdb->update($this->tables['yeasts'], array('status'=>'active'), array('id'=>$yeast->id));
                break;
            case 'update':
                $results = $wpdb->update( $this->tables['yeasts'], $this->input(), array('id'=>$_POST['id']) );
                /* translators: Message when yeast is updated - argument is the recipe title. */
                $this->message = sprintf(__('"%s" yeast successfully updated.', 'beer-press'), $_POST['name']);
                break;
            case 'edit':
                $this->yeast = $this->getOneRecord('yeasts', $_GET['id']);
                $this->view ='edit_yeast';
                break;
        }

        include($this->viewsPath . $this->view . '.php');
    }

    /**
     * Process the form POST input for yeasts.
     *
     * @return array
     */
    function input() {
        global $current_user;
        get_currentuserinfo();

        $slug = $this->slugify($_POST['slug'], $_POST['name'], 'yeasts');

        if ($_POST['create-page']) {
            if (!$parent = $_POST['page']) {
                $parent = $this->options['yeast-parent'];
            }

            $bp_new_post = array(
                'post_title'    => ucwords($_POST['name']),
                'post_content'  => '[recipe-yeasts item=' . $slug . ' /]',
                'post_type'     => 'page',
                'post_parent'   => $parent,
                'post_status'   => $this->options['yeast-parent-status'],
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
    public function get_yeast_id() {
        return $this->yeast->id;
    }

    public function yeast_id() {
        echo $this->get_yeast_id();
    }

    public function get_yeast_name() {
        return $this->yeast->name;
    }

    public function yeast_name() {
        echo $this->get_yeast_name();
    }

    public function get_yeast_slug() {
        return $this->yeast->slug;
    }

    public function yeast_slug() {
        echo $this->get_yeast_slug();
    }

    public function get_yeast_page() {
        return $this->yeast->page;
    }

    public function yeast_page() {
        echo $this->get_yeast_page();
    }

    public function get_yeast_url($link = false, $target='_blank') {
        if ($link) {
            return '<a href="' . esc_url($this->yeast->url) . '" target="' . $target . '">' . $this->yeast->url . '</a>';
        }
        return $this->yeast->url;
    }

    public function yeast_url($link = false) {
        echo $this->get_yeast_url($link);
    }

    public function get_yeast_recipes($count = false) {
        if ($count) {
            return count(explode(',', $this->yeast->recipes));
        } else {
            return $this->yeast->recipes;
        }
    }

    public function yeast_recipes($count = false) {
        echo $this->get_yeast_recipes($count);
    }

    public function get_yeast_status() {
        return $this->yeast->status;
    }

    public function yeast_status() {
        echo $this->get_yeast_status();
    }

    public function get_yeast_modified($format = 'Y-m-d h:i:s') {
        return date($format, strtotime($this->yeast->modified));
    }

    public function yeast_modified($format = 'Y-m-d h:i:s') {
        echo $this->get_yeast_modified($format);
    }
}
