<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

/**
 * bp_Comments.class.php - Class for comments moderation
 *
 * @package Recipe Press
 * @subpackage classes
 * @author GrandSlambert
 * @copyright 2009-2010
 * @access public
 */

class bp_Comments extends bp_Base {
    /* Set Variables */
    const menuName = 'recipe-press-comments';
    protected $view = 'comments';

    /**
     * Constructor Method
     */
    function __construct($id = NULL) {
        parent::__construct();

        add_action('admin_print_scripts', array($this, 'do_jslibs' ));
        add_action('admin_print_styles', array($this, 'do_css' ));
    }

    /**
     * Displays the subpanel and processes user actions.
     *
     * @global object $wpdb
     */
    function subPanel() {
        global $wpdb;

        switch ($_REQUEST['action']) {
            case 'trash':
                $comment = $this->getOneRecord('comments', $_GET['id']);
                $recipe = $this->getOneRecord('recipes', $comment->recipe_id);
                /* translators: Message when a comment is deleted. First argument is the comment author, second is the recipe title */
                $this->message = sprintf(__('Comment from %1$s for %2$s recipe successfully deleted', 'recipe-press'), $comment->author, $recipe->title);
                $wpdb->update($this->tables['comments'], array('status'=>'trash'), array('id'=>$comment->id));
                break;
            case 'update':
                $results = $wpdb->update( $this->tables['comments'], $this->input(), array('id'=>$_POST['id']) );
                $this->updateCommentCounts();

                if ($results) {
                    /* translators: Message when a comment is updated - argument is the comment author. */
                    $this->message = sprintf(__('Comment from "%s" successfully updated.', 'recipe-press'), $_POST['author']);
                } else {
                    /* translators: General error message when a comment could not be saved. */
                    $this->message = __('There was an error trying to save the comment. Perhaps you forgot to make any changes. Try again later, OK?', 'recipe-press');
                }

                break;
            case 'edit':
                $this->comment = $this->getOneRecord('comments', $_GET['id']);
                $this->recipe = $this->getRecipe($this->comment->recipe_id);
                $this->view ='edit_comment';
                break;
        }

        include($this->viewsPath . $this->view . '.php');
    }

    public function input($data = NULL, $prefix = NULL) {

        if (!$data) {
            $data = $_POST;
        }

        $results = array (
            'author'        => $data[$prefix . 'author'],
            'author_email'  => $data[$prefix . 'author_email'],
            'author_url'    => $data[$prefix . 'author_url'],
            'author_ip'     => $_SERVER['REMOTE_ADDR'],
            'content'       => $data[$prefix . 'content'],
            'status'        => $_POST[$prefix . 'status'],
            'recipe_id'     => $_POST[$prefix . 'recipe_id'],
            'user_id'       => $data['user_ID']
        );

        if ($data['aa']) {
            $results['date'] = $data['aa'].'-'.$data['mm'].'-'.$data['jj'].' '.$data['hh'].':'.$data['mn'].':'.$data['ss'];
        } else {
            $results['date'] = date ('Y-m-d H:i:s');
        }

        return $results;
    }

    public function rowClass($status = 'active') {
        switch ($status) {
            case 'spam':
                return 'spam';
                break;
            case 'pending':
                return 'unapproved';
                break;
            default:
                return 'approved';
                break;
        }
    }

    public function ajaxCommentAction() {
        global $wpdb, $current_user;
        get_currentuserinfo();

        $options['status'] = $_REQUEST['status'];

        switch ($options['status']) {
            case 'active':
                $options['approved_on'] = $this->now();
                $options['approved_by'] = $current_user->ID;
                break;
            default:
                $options['approved_on'] = false;
                $options['approved_by'] = false;
        }
        $wpdb->update($this->tables['comments'], $options, array('id'=>$_REQUEST['id']));
        echo ucfirst($_REQUEST['status']);
        die();
    }

    function do_css() {
        wp_enqueue_style('thickbox');
    }

    function do_jslibs() {
        wp_enqueue_script('editor');
        wp_enqueue_script('thickbox');
        add_action( 'admin_head', 'wp_tiny_mce' );
    }

    public function commentPostFilter($attrs) {
        if (!$_POST['comment_recipe_id']) {
            return $attrs;
        }
        global $wpdb;

        $data = $this->input($attrs, 'comment_');
        $this->recipe = $this->getOneRecord('recipes', $data['recipe_id']);
        $id = $wpdb->insert($this->tables['comments'], $data);
        $this->comment = $this->getOneRecord('comments', $wpdb->insert_id);
        $this->updateCommentCounts();
        wp_safe_redirect($this->get_comment_link(false));
        return 'pending';
    }

    public function getComments($options = array()) {
        global $wpdb;

        extract($options);

        if (!$orderby) {
            $orderby = '`' . $this->tables['comments'] . '`.`author`';
        }

        if (!$sortby) {
            $sortby = 'asc';
        }

        if ( isset($author) and $author != '' ) {
            $where.= ' and `' . $this->tables['comments'] . '`.`author` like "%' . $author . '%"';
        }

        if ( isset($comment) and $comment != '' ) {
            $where.= ' and `' . $this->tables['comments'] . '`.`content` like "%' . $comment . '%"';
        }

        if ( isset($recipe) and $recipe != 'all') {
            $where.= ' and `' . $this->tables['comments'] . '`.`recipe_id` = ' . $recipe;
        }

        if ( isset($user) and $user != 'all') {
            $where.= ' and `' . $this->tables['comments'] . '`.`user_id` = ' . $user;
        }

        if ( isset($status) and $status != 'all') {
            $where.= ' and `' . $this->tables['comments'] . '`.`status` = "' . $status . '"';
        } elseif (!$status) {
            $where.= ' and `' . $this->tables['comments'] . '`.`status` != "trash"';
        }

        if (isset($include_user) and $include_user > 0) {
            $where.= ' or (`' . $this->tables['comments'] . '`.`user_id` = ' . $include_user . ') and `' . $this->tables['comments'] . '`.`recipe_id` = ' . $recipe;
        }

        if ( isset($limit) ) {
            $limit = ' limit ' . $limit;
        } elseif (is_object($pagination) ) {
            if ($pagination->offset) {
                $limit = ' limit ' . $pagination->offset . ',' . $pagination->limit;
            } else {
                $limit = ' limit ' . $pagination->limit;
            }
        } else {
            $limit = ' limit ' . $this->options['display-limit'];
        }

        $query = '
            select SQL_CALC_FOUND_ROWS 
                `' . $this->tables['comments'] . '`.*,
                `' . $this->tables['recipes'] . '`.`title` as `recipe_title`,
                `' . $this->tables['recipes'] . '`.`slug` as `recipe_slug`
            from `' . $this->tables['comments'] . '`
            left join `' . $this->tables['recipes'] . '` on `' . $this->tables['recipes'] . '`.`id` = `' . $this->tables['comments'] . '`.`recipe_id`
            where `' . $this->tables['comments'] . '`.`id` > 0 ' . $where . '
            order by ' . $orderby . ' ' . $sortby . $limit . '
            ';

        $results = $wpdb->get_results($query);

        if (is_object($pagination) ) {
            $pagination->set_found_rows($wpdb->get_var('select found_rows()'));
        }

        return $results;
    }

    /* Template Tags */
    public function get_comment_status() {
        switch ($this->comment->status) {
            case 'pending':
                /* translators: Message displayed with comment when it is awaiting moderation. */
                $output = '<span class="rp-comment-status">' . __('Your comment is currently awaiting moderation.', 'recipe-press') . '</span>';
                $output.= '<div class="clear"></div>';
                break;
            case 'spam':
                /* translators: Message displayed with comment when it has been marked as spam. */
                $output = '<span class="rp-comment-spam">' . __('Your comment has been marked as spam.', 'recipe-press') . '</span>';
                $output.= '<div class="clear"></div>';
                break;
        }

        return $output;
    }

    public function comment_status() {
        echo $this->get_comment_status();
    }

    public function showComments($id) {
        global $wpdb, $current_user;
        get_currentuserinfo();

        $this->recipe = $this->getRecipe($id);
        $this->comments = $this->getComments(array('recipe'=>$this->recipe->id, 'status'=>'active', 'include_user' => $current_user->ID));
        $this->recipe = $this->getRecipe($this->recipe->id);
        $template = $this->getTemplate('recipe-comments');
        include($template);
    }

    public function have_comments() {
        return count($this->comments);
    }

    public function get_comments_number($none = 'No Responses', $one = 'One Response', $many = '% Responses') {
        switch(count($this->comments)) {
            case 0:
                echo $none;
                break;
            case 1:
                echo $one;
                break;
            default:
                echo str_replace('%', count($this->comments), $many);
                break;
        }
    }

    public function comments_number($none = 'No Responses', $one = 'One Response', $many = '% Responses') {
        echo $this->get_comments_number($none, $one, $many);
    }

    public function previous_comments_link() {
        return false;
    }

    public function next_comments_link() {
        return false;
    }

    public function get_list_comments() {
        $template = $this->getTemplate('recipe-comment');
        $output= '<ol class="rp-comment-list">';

        foreach ($this->comments as $this->comment) {
            ob_start();
            include ($template);
            $output.= ob_get_contents();
            ob_end_clean();
        }

        $output.= '</ol>';

        return $output;
    }

    public function list_comments() {
        echo $this->get_list_comments();
    }

    public function get_comment_author() {
        if ($this->comment->user_id) {
            $author = get_userdata($this->comment->user_id);

            if ($author->user_url) {
                $output = '<a class="url url" rel="external nofollow" href="' . $author->user_url . '" target="_blank">' . stripslashes($author->display_name) . '</a>';
            } else {
                $output = $author->display_name;
            }
        } elseif ($this->comment->author_url) {
            $output = '<a class="url url" rel="external nofollow" href="' . $this->comment->author_url . '" target="_blank">' . stripslashes($this->comment->author) . '</a>';
        } else {
            $output = stripslashes($this->comment->author);
        }

        return $output;
    }

    public function comment_author() {
        echo $this->get_comment_author();
    }

    public function get_comment_id() {
        return $this->comment->id;
    }

    public function comment_id() {
        echo $this->get_comment_id();
    }

    public function get_comment_date() {
        return date($this->options['comments-date'], strtotime($this->comment->date));
    }

    public function comment_date() {
        echo $this->get_comment_date();
    }

    public function get_comment_link($full = true) {
        $link = $this->getRecipeLink() . '#comment-' . $this->comment->id;

        if ($full) {
            return '<a title="Direct link to this comment" href="' . $link . '" class="bp_comment-permalink">Permalink</a>';
        } else {
            return $link;
        }
    }

    public function comment_link($full = true) {
        echo $this->get_comment_link($full);
    }

    public function get_comment_gravatar() {
        if (!$this->options['comments-gravatar']) {
            return;
        }

        if ($this->comment->user_id) {
            $author = get_userdata($this->comment->user_id);
            $email = $author->user_email;
        } else {
            $email = $this->comment->author_email;
        }
        return '<div class="rp-avatar"><img width="40" height="40" class="rp-photo" src="http://www.gravatar.com/avatar/' . md5($email) . '" alt=""/></div>';
    }

    public function comment_gravatar() {
        echo $this->get_comment_gravatar();
    }

    public function get_comment_content() {
        return nl2br(esc_attr(stripslashes($this->comment->content)));
    }

    public function comment_content() {
        echo $this->get_comment_content();
    }

    public function comments_open() {
        if ($this->recipe->comments_open) {
            return true;
        } else {
            return false;
        }
    }
}
