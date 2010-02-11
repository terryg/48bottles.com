<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

/**
 * bp_Base.class.php - Class base for all other classes
 *
 * @package Beer Press
 * @subpackage classes
 * @author tgl@rideside.net
 * @copyright 2010
 * @access public
 */

class bp_Base {
    /* Plugin Settings */
    const menuName = 'beer-press-base';
    const pluginName = 'Beer Press';
    public $version = '0.0.1';
    var $optionsName	= 'beer-press-options';
    var $pagination = array();

    /* Private Variables */
    var $is_text		= array('title', 'name', 'slug', 'fermentables', 'hops', 'yeasts', 'miscs');

    /* Private Variables */
    protected $view = 'base';
    protected $tablenames   = array('recipes', 'recipesmeta', 'categories', 'options', 'comments', 'fermentables');
    protected $table_prefix = 'bp_';
    protected $tables 	    = array();
    protected $roles        = array(8 => 'administrator', 5 => 'editor', 2=> 'author', 1 => 'contributor', 0 => 'subscriber');
    protected $menus        = array('recipes','add-recipe','ingredients','comments','categories','tags','settings');

    /* Form Field Names */
    protected $formFieldNames = array();
    
    /**
     * Base Constructor
     */
    function __construct() {
        /* translators: The name of the plugin, should be a translation of "Beer Press" only! */
        $this->pluginName = __('Beer Press', 'beer-press');
        $this->viewsPath = WP_CONTENT_DIR . '/plugins/' . basename(dirname(dirname(__FILE__))) . '/views/';
        $this->templatesPath = WP_CONTENT_DIR . '/plugins/' . basename(dirname(dirname(__FILE__))) . '/templates/';
        $this->templatesURL = get_option('siteurl') . '/wp-content/plugins/beer-press/templates/';
        $this->pluginPath = WP_CONTENT_DIR . '/plugins/' . basename(dirname(dirname(__FILE__)));
        $this->pluginDir = get_option('siteurl') . '/wp-content/plugins/' . basename(dirname(dirname(__FILE__)));
        $this->questionMark = $this->pluginDir . '/icons/question.jpg';
        $this->remindMark = $this->pluginDir . '/icons/exclamation.gif';
        $this->loadSettings();

        /* Specify tables */
        global $wpdb;
        foreach ($this->tablenames as $table) {
            $this->tables[$table] = $wpdb->prefix . $this->table_prefix . $table;
        }
    }

    /**
     * Add items to the header of the web site.
     */
    public function addHeader() {
        global $wp_query;

        if ($this->options['custom-css']) {
            echo '<link rel="stylesheet" media="screen" type="text/css" href="' . $this->getTemplate('beer-press', '.css', 'url') .'" />' . "\n";
            echo '<link rel="stylesheet" media="screen" type="text/css" href="' . $this->getTemplate('beer-press-list', '.css', 'url') .'" />' . "\n";
        }

        /* Add the beer template style sheet */
        $this->beer = $this->getBeerFromSlug($wp_query->get('beer'), false);
        if ($this->beer->template) {
            $file = $this->getTemplate('beer-display/' . $this->beer->template, '.css', 'url');
            echo '<link rel="stylesheet" media="screen" type="text/css" href="' . $file .'" />' . "\n";
        }

        echo '<script type="text/javascript" src="' . $this->pluginDir . '/js/beer-form.js"></script>' . "\n";

    }

    /**
     * Load the plugin settings.
     */
    function loadSettings() {
        $this->formFieldNames = array(
            'title'         => __('Beer Name'),
            'notes'         => __('Beer Notes'),
            'category'      => __('Category'),
            'servings'      => __('Servings'),
            'prep_time'     => __('Prep Time'),
            'cook_time'     => __('Cook Time'),
            'ingredients'   => __('Ingredients'),
            'instructions'  => __('Instructions'),
            'recaptcha'     => __('Verify'),
            'submitter'     => __('Name'),
            'submitter_email'=> __('Email'),
        );


        $this->options = get_option($this->optionsName);

        /* Get the settings */

        if (!$this->options['submit-title']) {
            $this->options['submit-title'] = 'Submit A Beer';
        }

        if (!$this->options['default-category']) {
            $this->options['default-category'] = 1;
        }

        if (!$this->options['beer-template']) {
            $this->options['beer-template'] = 'standard';
        }

        if (!$this->options['submit-location']) {
            $this->options['submit-location'] = 'after';
        }

        if (!$this->options['widget-items']) {
            $this->options['widget-items'] = 10;
        }

        if (!$this->options['required-fields']) {
            $this->options['required-fields'] = array('title', 'ingredients','instructions', 'submitter', 'submitter_email');
        }

        if (!$this->options['comment-form-title']) {
            $this->options['comment-form-title'] = 'Share Your Thoughts';
        }

        if(!$this->options['hour-text']) {
            $this->options['hour-text'] = ' hour';
        }

        if (!$this->options['minute-text']) {
            $this->options['minute-text'] = ' min';
        }

        if (!$this->options['comments-date']) {
            $this->options['comments-date'] = 'F jS, Y';
        }

        if (!$this->options['ingredients-fields']) {
            $this->options['ingredients-fields'] = 5;
        }

        if (!$this->options['ingredient-parent-status']) {
            $this->options['ingredient-parent-status'] = 'draft';
        }

        if (!$this->options['category-template']) {
            $this->options['category-template'] = 'standard';
        }

        if (!$this->options['widget-icon-size']) {
            $this->options['widget-icon-size'] = 25;
        }

        if (!$this->options['beer-list-template']) {
            $this->options['beer-list-template'] = 'standard';
        }

        if (!$this->options['display-limit']) {
            $this->options['display-limit'] = 10;
        }

        if (!$this->options['new-beer-status']) {
            $this->options['new-beer-status'] = 'pending';
        }

        if (!$this->options['new-beer-template']) {
            $this->options['new-beer-template'] = 'standard';
        }

        if (!$this->options['form-type']) {
            $this->options['form-type'] = 'textarea';
        }

        /* Find the lowest level to display the menu to */
        $lowest = 10;
        foreach ($this->menus as $menu) {
            $rolename = $menu.'-role';
            if (!$this->options[$rolename]) {
                $this->options[$rolename] = $this->roles[8];
            }

            $key = array_search($this->options[$rolename], $this->roles);

            if ($key < $lowest) {
                $this->options['overview-role'] = $this->roles[$key];
                $lowest = $key;
            }
        }
    }

    /**
     * Process the incoming image and store it in the Media Library.
     *
     * @param string $slug
     * @return integer
     */
    public function processImage($slug = NULL, $type = 'beer') {
        $folder = wp_upload_dir();

        switch ($_FILES['image']['type']) {
            case 'image/gif':
                $ext = '.gif';
                break;
            case 'image/png':
                $ext = '.png';
                break;
            case 'image/jpeg':
                $ext = '.jpg';
                break;
            default:
                return false;
        }

        $name = $_FILES['image']['tmp_name'];
        $destbase = $dest = $folder['path'] . '/' . $slug . '-main';

        if ($_POST['delete_existing'] and $_POST['old_media_id']) {
            wp_delete_attachment($_POST['old_media_id']);
        }

        $ctr = 1;
        while (file_exists($dest . $ext)) {
            $dest = $destbase . '-' . $ctr;
            ++$ctr;
        }

        $dest.= $ext;

        if (move_uploaded_file($name, $dest) ) {

            switch ($type) {
                case 'category':
                    $title = $_POST['name'] . ' Category Image';
                    break;
                default:
                    $title = $_POST['title'] . ' Beer Image';
                    break;
            }

            $attachment = array(
                'post_title'    => $title,
                'post_content'  => $_POST['notes'],
                'post_mime_type'=> $_FILES['image']['type'],
            );

            $attach_id = wp_insert_attachment( $attachment, $dest, false );
            $attach_data = wp_generate_attachment_metadata( $attach_id, $dest );
            $results = wp_update_attachment_metadata( $attach_id,  $attach_data );

            return $attach_id;
        }

        return false;
    }

    /**
     * Show the beer comments box
     */
    function showComments() {
        if (!$this->hideComments) {
            $rp_Comments = new rp_Comments;
            $rp_Comments->showComments($this->beer->id);
        }
    }

    function get_beer_title() {
        return esc_attr(stripslashes($this->beer->title));
    }

    function beer_title() {
        echo $this->get_beer_title();
    }

    function get_beer_id() {
        return $this->beer->id;
    }

    function beer_id() {
        echo $this->get_beer_id();
    }

    /**
     * Count entries in a database table.
     *
     * @global object $wpdb
     * @param string $table     The name of the database table.
     * @param string $status    The status to display.
     * @param integer $category A category ID to restrict the count to.
     * @return <type>
     */
    function getCount($table = NULL, $status = 'all', $category = 0) {
        global $wpdb, $current_user;
        get_currentuserinfo();

        $query = "SELECT COUNT(*) FROM `" . $this->tables[$table] . '` where `id` > 0 ';

        if ($status != 'all') {
            $query.= ' and `status` = "' . $status . '"';
        }

        if ($category != 0) {
            $query.= ' and `category` = ' . $category;
        }

        if (is_admin() and !in_array('administrator', $current_user->roles) and !in_array('editor', $current_user->roles)) {
            $query.= ' and `' . $this->tables[$table] . '`.`user_id` = ' . $current_user->ID;
        }

        if ($status != 'trash') {
            $query.= 'and `' . $this->tables[$table] . '`.`status` != "trash"';
        }

        return $wpdb->get_var($query);
    }

    function showCount($table= NULL, $status = 'all', $category = 0) {
        echo $this->getCount($table, $status, $category);
    }

    /**
     * Default method for displaying a panel.
     */
    function subPanel() {
        include($this->viewsPath . $this->view . '.php');
    }

    /**
     * Displays the user name (display_name) of the selected user.
     *
     * @param integer $id   The ID of the user.
     * @return string       The display_name of the user.
     */
    function displayUser($id = NULL) {
        if ($this->beer->submitter) {
            echo $this->beer->submitter;
        }
        elseif (is_null($id)) {
            global $current_user;
            get_currentuserinfo();
            echo $current_user->display_name;
        }
        elseif ($id == 0) {
            /* translators: The name of the user when a beer is submitted by a non-member. */
            return __('Non Member', 'beer-press');
        } else {
            $user_info = get_userdata($id);
            return $user_info->display_name;
        }
    }

    function displayPage($id = NULL) {
        if (!$id) {
            return;
        }

        $page = get_page($id);
        return $page->post_title;

    }

    function get_list_roles($menu, $selected, $attrs = array()) {
        $output = '<select name="' . $this->optionsName . '[' . $menu . '-role]" id="beer_press_' . $menu . '_role" ';

        foreach ($attrs as $attr) {
            $output.= ' ' . $attr . '="' . $attr . '"';
        }

        $output.= '>';

        foreach ($this->roles as $role) {
            $output.= '<option value="' . $role . '" ';
            if ($role == $selected) $output.= ' selected="selected"';
            $output.= '>' . ucfirst($role) . '</option>';
        }

        $output.= '</select>';
        return $output;
    }

    function list_roles($menu, $selected, $attrs = array()) {
        echo $this->get_list_roles($menu, $selected, $attrs);
    }

    /**
     * Display the current version number for the plugin.
     *
     * @return string
     */
    function showVersion() {
        return $this->version;
    }

    /**
     * Create option tags for any table in the database.
     *
     * @global object $wpdb
     * @param string $table         The table to retrieve from.
     * @param string $selected      The selected item, based on the $key.
     * @param string $key           The name of the table field to use for the value of the option tag.
     * @param string $value         The name of the table field to display in the drop down list.
     * @param string $keymatch      A field to limit the table to, used in the options table.
     * @return string               HTML of all options from the table.
     */
    public function listOptions($table = 'options', $selected = NULL, $key = 'id', $value = 'name', $keymatch = NULL) {
        global $wpdb;

        if ($table == 'categories') {
            $options = $this->getRecordsCascade($table, array('order-by'=>'name'));
        }
        else {
            $query = 'select * from `' . $this->tables[$table] . '`';
            if (!is_null($keymatch))
                $query.= ' where `' . $key . '` = "' . $keymatch . '"';

            if ($table == 'options') {
                $query.= ' order by `sort_order` ASC, `value` ASC';
                $key = 'value';
            }

            $options = $wpdb->get_results($query);
        }

        foreach ($options as $option) {
            $output.= '<option value="' . $option->$key . '"';
            if ($option->$key == $selected) $output.= ' selected="selected"';
            $output.= '>';

            if (isset($option->level)) {
                for ($ctr = 1; $ctr < $option->level; ++$ctr) {
                    $output.= '&mdash;';
                }
            }

            $output.= ucfirst($option->$value) . '</option>';
        }

        return $output;
    }

    /**
     * Get one item from the options table by ID
     *
     * @global object $wpdb
     * @param integer $id       The ID of the option to retrieve.
     * @return object           Data object.
     */
    public function getOptionByID($id) {
        global $wpdb;

        return $wpdb->get_var('select `value` from `' . $this->tables['options'] . '` where `id` = ' . $id);

    }

    /**
     * Get one item fro mthe options table by value
     *
     * @global object $wpdb
     * @param string $value     Text to search for in the Value field.
     * @param sgtring $key      The key value to use to limit the search.
     * @param string $field     The name of the table field to return.
     * @return string           The value of the $field for the matched row.
     */
    public function getOptionIDByValue($value, $key = 'status_types', $field = 'id') {
        global $wpdb;

        $query = 'select `' . $field . '` from `' . $this->tables['options'] . '` where `key` = "' . $key . '" and `value` ="' . $value .'"';

        return $wpdb->get_var($query);
    }

    /**
     * Display errors in $wpdb
     *
     * @global object $wpdb
     * @param boolean $dump     True to dump the object or false to show the error and SQL
     * @return NULL
     */
    protected function wpdbError($dump = false) {
        global $wpdb;

        if ($dump) {
            var_dump($wpdb);
            return;
        }

        echo 'Last error was: ' . $wpdb->last_error . '<br />';
        echo 'Produced by query: ' . $wpdb->last_query . '<br />';
    }

    /**
     * Calculate the ready time for a beer.
     *
     * @param integer $prep     The prep time.
     * @param integer $cook     The cook time.
     * @return string           Formatted cooking time.
     */
    protected function readyTime($prep = NULL, $cook = NULL) {
        if ( !isset($prep) ) $prep = $_POST['prep_time'];
        if ( !isset($cook) ) $cook = $_POST['cook_time'];

        $total = $prep + $cook;

        if ($total > 60) {
            $hours = floor($total / 60);

            if ($hours > 1 and $this->options['plural-times'])
                $hplural = 's';
            else
                $mplural = '';

            $hours =  $hours . ' ' . $this->options['hour-text'] . $hplural . ', ';
        }

        $mins = $total -( $hours * 60);

        if ($mins > 1 and $this->options['plural-times'])
            $mplural = 's';
        else
            $mplural = '';

        return $hours . $mins . ' ' . $this->options['minute-text'] . $mplural;
    }

    /**
     * Create a URL slug from any text.
     *
     * @param string $slug          The text to use to create the slug.
     * @param string $alternate     Text to use if slug is blank or NULL.
     * @return string               A formatted slug.
     */
    function slugify($slug, $alternate, $table = 'beers', $allowduplicate = false) {
        global $wpdb;

        if (!$slug) {
            $slug = trim($alternate);
        }

        $slug = str_replace( ' ', '-', preg_replace( '/[^a-z0-9- ]/', '', strtolower( $slug ) ) );
        $finalSlug = $slug;
        $ctr = 1;

        if (!$allowduplicate and $_POST['id']) {
            while ($wpdb->get_results('select * from `' . $this->tables[$table] . '` where `id` != ' . $_POST['id'] . ' and `slug` = "' . $finalSlug . '"')) {
                $finalSlug = $slug . '-' . $ctr;
                ++$ctr;
            }
        }

        return $finalSlug;
    }

    /**
     * Retrieve a beer from the database using the slug.
     *
     * @global object $wpdb
     * @param string $slug          The slug for the beer.
     * @param boolean $countview    True to count the read as a view.
     * @return object
     */
    function getBeerFromSlug ($slug, $countview = false) {
        global $wpdb;

        if ( is_numeric($slug) )
            $where = $this->tables['beers'] . '`.`id` = "' . $slug . '"';
        else
            $where = $this->tables['beers'] . '`.`slug` = "' . $slug . '"';


        $query = '
            select
                    `' . $this->tables['beers'] . '`.*,
                    `' . $this->tables['categories'] . '`.`name` as `category_name`,
                    `' . $this->tables['categories'] . '`.`slug` as `category_slug`
            from `' . $this->tables['beers'] . '`
            left join `' . $this->tables['categories'] . '` on `' . $this->tables['categories'] . '`.`id` = `' . $this->tables['recipes'] . '`.`category`
            where `' . $where;

        $recipe = $wpdb->get_row($query);

        if ($countview)
            $wpdb->update($this->tables['recipes'], array('views_total' => $recipe->views_total + 1), array('id'=>$recipe->id) );

        return $recipe;
    }

    public function getRecipe($id) {
        $recipe = $this->getRecipes(array('id'=>$id));
        return $recipe[0];
    }

    /**
     * Get a list of recipes.
     *
     * @global object $wpdb
     * @param array $options    Options to to generate the SQL statement for retrieving records.
     * @return array            Contains one object for each retrieved recipe.
     */
    function getRecipes($options = array()) {
        global $wpdb, $current_user;
        get_currentuserinfo();

        extract($options);

        if ($id) {
            $where = '`.`id` = ' . $options['id'] . ' ';
        } else {
            $where = '`.`id` > 0 ';
        }

        switch ($type) {
            case 'random':
                $orderby = 'rand()';
                break;
            case 'newest':
                $orderby = '`published`';
                break;
            case 'popular':
                $orderby = '`views_total`';
                break;
            case 'featured':
                $where.= ' and `' . $this->tables['recipes'] . '`.`featured` = 1';
                $orderby = '`title`';
                break;
            case 'updated':
                $orderby = 'updated';
                $sortby = 'desc';
                break;
        }

        if (!$orderby) {
            $orderby = '`title`';
        }

        if (!$sortby) {
            $sortby = 'asc';
        }

        if ( isset($recipe) and $recipe != '' ) {
            $where.= ' and `' . $this->tables['recipes'] . '`.`title` like "%' . $recipe . '%"';
        }

        if ( isset($slug) and $slug != '' ) {
            $where.= ' and `' . $this->tables['recipes'] . '`.`slug` like "%' . $slug . '%"';
        }

        if ( isset($category) and $category != 'all') {
            $where.= ' and `' . $this->tables['recipes'] . '`.`category` = ' . $category;
        }

        if ( isset($user) and $user > 0) {
            $where.= ' and `' . $this->tables['recipes'] . '`.`user_id` = ' . $user;
        }

        if ( isset($status) and $status != 'all') {
            $where.= ' and `' . $this->tables['recipes'] . '`.`status` = "' . $status . '"';
        }

        if (is_admin() and !$this->superUser()) {
            $where.= ' and `' . $this->tables['recipes'] . '`.`user_id` = ' . $current_user->ID;
        }

        if ($status != 'trash') {
            $where.= ' and `' . $this->tables['recipes'] . '`.`status` != "trash"';
        }

        if (is_object($pagination)) {
            if ($pagination->offset) {
                $limit = ' limit ' . $pagination->offset . ',' . $pagination->limit;
            } else {
                $limit = ' limit ' . $pagination->limit;
            }
        }
        elseif (isset($limit) ) {
            $limit = ' limit ' . $limit;
        } else {
            $limit = ' limit ' . $this->options['display-limit'];
        }

        $query = '
            select SQL_CALC_FOUND_ROWS
                `' . $this->tables['recipes'] . '`.*,
                `' . $this->tables['categories'] . '`.`name` as `category_name`,
                `' . $this->tables['categories'] . '`.`slug` as `category_slug`
            from `' . $this->tables['recipes'] . '`
            left join `' . $this->tables['categories'] . '` on `' . $this->tables['categories'] . '`.`id` = `' . $this->tables['recipes'] . '`.`category`
            where `' . $this->tables['recipes'] . $where . '
            order by ' . $orderby . ' ' . $sortby . $limit . '
        ';

        $results = $wpdb->get_results($query);

        if (is_object($pagination) ) {
            $pagination->set_found_rows($wpdb->get_var('select found_rows()'));
        }

        return $results;
    }

    /**
     * Determins if the user is a super user - Admin or Editor
     *
     * @global object $current_user
     * @return boolean
     */
    public function superUser() {
        global $current_user;
        get_currentuserinfo();
        return (in_array('administrator', $current_user->roles) or in_array('editor', $current_user->roles) );
    }

    /**
     * Determines if a user has access to a given capability.
     *
     * @global object $current_user
     * @param string $menu
     * @return boolean
     */
    public function userCan($menu = 'overview') {
        global $current_user;
        get_currentuserinfo();

        foreach ($current_user->roles as $key=>$role) {
            if ( array_search($role, $this->roles) >= array_search($this->options[$menu.'-role'], $this->roles) ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get one record from the database.
     *
     * @global object $wpdb
     * @param string $table     The name of the table to retrieve the record from.
     * @param integer $id       The id of the record to retrieve.
     * @param array $attrs      Options to add to the query statement
     * @return object
     */
    public function getOneRecord($table, $id = NULL, $attrs = array()) {
        global $wpdb;

        $sql = 'select * from `' . $this->tables[$table] . '` where ';

        if ($id) {
            $sql.= '`id` = ' . $id;
            $wherePre = ' and ';
        }

        foreach ($attrs as $key=>$value) {
            if ($value and $value != 'all') {
                if ( in_array($key, $this->is_text) )
                    $sql.= $wherePre . "`$key` like \"%$value%\" ";
                else
                    $sql.= $wherePre . "`$key`=\"$value\" ";
            }
            $wherePre = ' and ';
        }

        return $wpdb->get_row($sql);
    }

    /**
     * Get multiple records from any database table.
     *
     * @global object $wpdb
     * @param string $table     The name of the table to read.
     * @param array $attrs      Options to add to the query.
     * @return array            Array containg one object for each record.
     */
    public function getRecords($table, $attrs = array()) {
        global $wpdb;

        if (!is_array($attrs))
            $attrs = array($attrs);

        if (isset($attrs['pagination']) ) {
            $pagination = &$attrs['pagination'];
            unset ($attrs['pagination']);
        }

        if ( isset($attrs['limit']) ) {
            $limit = ' limit ' . $attrs['limit'];
            unset ($attrs['limit']);
        } elseif (is_object($pagination) ) {
            if ($pagination->offset) {
                $limit = ' limit ' . $pagination->offset . ',' . $pagination->limit;
            } else {
                $limit = ' limit ' . $pagination->limit;
            }
        } else {
            $limit = ' limit ' . $this->options['display-limit'];
        }

        if (isset($attrs['order-by'])) {
            $orderby = 'order by ' . $attrs['order-by'];
            unset ($attrs['order-by']);
        }

        if (!isset($attrs['status'])) {
            $extraWhere = ' and `' . $this->tables[$table] . '`.`status` != "trash" ';
        }

        $sql = 'select SQL_CALC_FOUND_ROWS * from `' . $this->tables[$table] . '` where `id` > 0';

        foreach ($attrs as $key=>$value) {
            if ( isset($value) and $value != '' and $value != 'all') {
                if ( in_array($key, $this->is_text) ) {
                    $sql.= " and `$key` like \"%$value%\" ";
                } else {
                    $sql.= " and `$key`=\"$value\" ";
                }
            }
        }

        $sql = $sql . $extraWhere . $orderby . $limit;
        $results = $wpdb->get_results($sql);

        if (is_object($pagination) ) {
            $pagination->set_found_rows($wpdb->get_var('select found_rows()'));
        }

        return $results;
    }

    /**
     * Get multiple records from any database table.
     *
     * @global object $wpdb
     * @param string $table     The name of the table to read.
     * @param array $attrs      Options to add to the query.
     * @return array            Array containg one object for each record.
     */
    public function getRecordsCascade($table, $attrs = array(), $level = 1) {
        global $wpdb;

        $records = array();

        if (!isset($attrs['parent'])) {
            $attrs['parent'] = '0';
        }

        $results = $this->getRecords($table, $attrs);

        foreach ($results as $result) {
            $result->level = $level;
            array_push($records, $result);
            $attrs['parent'] = $result->id;
            $records = array_merge($records, $this->getRecordsCascade($table, $attrs, $level + 1));
        }

        return $records;
    }

    /**
     * Retrieve a template file from either the theme or the plugin directory.
     *
     * @param string $template      The name of the template.
     * @return string               The full path to the template file.
     */
    function getTemplate($template = NULL, $ext = '.php', $type = 'path') {
        if ($template == NULL)
            return false;
        $themeFile =  get_theme_root() . '/' . get_template() . '/' . $template . $ext;

        if (file_exists( $themeFile ) ) {
            if ($type == 'url') {
                $file = get_option('siteurl') . '/wp-content/themes/' . get_template() . '/' . $template . $ext;
            } else {
                $file = get_theme_root() . '/' . get_template() . '/' . $template . $ext;
            }
        }
        elseif ($type == 'url') {
            $file = $this->templatesURL . $template . $ext;
        }
        else {
            $file = $this->templatesPath . $template . $ext;
        }

        return $file;
    }

    /**
     * Get list of recipe templates.
     *
     * @param string $selected
     * @param boolean $default
     * @return string
     */
    function get_recipe_templates($selected, $default = false, $name = 'template', $id = 'template') {
        if ($default) {
            $templates = array('default');
        }
        else {
            $templates = array();
        }

        if ($handle = opendir($this->pluginPath . '/templates/recipe-display')) {

            /* Loop through directory to get templates. */
            while (false !== ($file = readdir($handle))) {
                if ( !is_dir($file) and preg_match('/php/',$file) ) {
                    array_push($templates,$file);
                }
            }

            closedir($handle);
        }

        $output = '<select name="' . $name . '" id="' . $id . '">';
        
        foreach ($templates as $template) {
            $value = eregi_replace('.php', '', $template);
            $name = eregi_replace("-", " ", $value);

            $output.= '<option value="' . $value  . '" ' . selected($template, $selected .'.php', false) . '>' . ucfirst($name) . '</option>';
        }

        $output.= '</select>';
        return $output;
    }

    public function recipe_templates($selected, $default = true, $name = 'template', $id = 'template') {
        echo $this->get_recipe_templates($selected, $default, $name, $id);
    }

    public function getRecipeLink($recipe = NULL) {

        if (is_object($recipe)) {
            $this->recipe = $recipe;
        }

        $displayPage = $this->options['display-page'];
        $frontPage = get_option('page_on_front');

        if (!$this->recipe) {
            return get_permalink($displayPage);
        }

        $category = $this->getOneRecord('categories', $this->recipe->category);

        $permalinktype = get_option('permalink_structure');

        if (! $permalinktype) {
            $linkpre = get_page_link($displayPage);

            if ( $displayPage == $frontPage ) {
                $linkpre.= '?page_id=' . $displayPage . '&amp;';
            } else {
                $linkpre.= '&amp;';
            }
        }
        else {
            $linkpre = get_page_link($this->options['display-page']);

            if (substr($linkpre, strlen($linkpre) -1, 1) != '/') {
                $linkpre.= '/';
            }
        }

        if (!$permalinktype) {
            $output.= $linkpre . 'category=' . $category->slug . '&recipe=' .  $this->recipe->slug;
        }
        else {
            $output.= $linkpre .  $category->slug . '/' .  $this->recipe->slug;
        }

        return $output;
    }

    public function recipeLink($recipe = NULL) {
        echo $this->getRecipeLink($recipe);
    }

    public function buildURL($menu, $options = array()) {
        $link = get_option('siteurl') . '/wp-admin/admin.php?page=' . $menu;

        foreach ($options as $option=>$value) {
            if ( isset($value) ) {
                $link.= '&' . $option . '=' . $value;
            }
        }

        return wp_nonce_url($link);
    }

    public function adminURL($menu, $options = array()) {
        echo $this->buildURL($menu, $options);
    }

    public function getAdminURL($menu, $options = array()) {
        return $this->buildURL($menu, $options);
    }

    public function help($text) {
        echo '<img src="' . $this->questionMark . '" align="absmiddle"onmouseover="return overlib(\'' . $text . '\');" onmouseout="return nd();" />';
    }

    public function remind($text) {
        echo '<img src="' . $this->remindMark . '" align="absmiddle"onmouseover="return overlib(\'' . $text . '\');" onmouseout="return nd();" />';
    }

    public function checked($data, $value) {
        if (
        (is_array($data) and in_array($value, $data) )
            or $data == $value
        ) {
            echo 'checked="checked"';
        }
    }

    public function updateCommentCounts() {
        global $wpdb;

        return $wpdb->query('
            UPDATE `' . $this->tables['recipes'] . '`
            SET `comment_total` =
                (select count(*) from `' . $this->tables['comments'] .'`
                WHERE `' . $this->tables['comments'] . '`.`recipe_id` = `' . $this->tables['recipes'] . '`.`id`)'
        );
    }

    /**
     * Updates all the ingredients for all recipes
     *
     * @global object $wpdb
     */
    public function updateIngredients() {
        global $wpdb;

        $recipes = $this->getRecords('recipes');
        $ingredients = array();

        foreach ($recipes as $this->recipe) {
            foreach (rp_Recipe_Base::get_recipe_ingredients() as $ingredient) {
                if ($ingredient['size'] and $ingredient['size'] != 'divider') {
                    $slug = $this->slugify(trim($ingredient['item']), NULL, 'ingredients', true);

                    if (!is_array($ingredients[$slug])) {
                        $ingredients[$slug] = array();

                        $results = $this->getOneRecord('ingredients', NULL, array('slug'=>$slug));

                        if ($results) {
                            $ingredients[$slug]['id'] = $results->id;
                            $ingredients[$slug]['name'] = $results->name;

                            if ($results->page) {
                                $ingredients[$slug]['page'] = $results->page;
                            }
                        } else {
                            $ingredients[$slug]['name'] = ucwords($ingredient['item']);
                        }

                    }

                    if ($ingredients[$slug]['recipes']) {
                        $ingredients[$slug]['recipes'].= ',' . $this->recipe->id;
                    } else {
                        $ingredients[$slug]['recipes'] = $this->recipe->id;
                    }

                    if (!$ingredients[$slug]['page'] and $ingredient['page-link']) {
                        $ingredients[$slug]['page'] = $ingredient['page-link'];
                    }
                }
            }
        }

        foreach ($ingredients as $slug=>$ingredient) {
            $ingredient['slug'] = $slug;
            if ($ingredient['id']) {
                $wpdb->update($this->tables['ingredients'], $ingredient, $ingredient['id']);
            } else {
                $wpdb->insert($this->tables['ingredients'], $ingredient);
            }
        }
    }

    public static function debug($data = NULL, $echo = true) {
        $output = '<textarea style="width:95%; height:200px">';
        $output.= print_r($data, true);
        $output.= '</textarea>';

        if ($echo) {
            echo $output;
        } else {
            return $output;
        }
    }

    public function onDeleteAttachment($id) {
        global $wpdb;

        $wpdb->query('update `' . $this->tables['recipes'] . '` set `media_id` = 0 where `media_id` = ' . $id);

        return $id;
    }

    public function now() {
        return date("Y-m-d h:i:s");
    }

    public function getLimit($limit = NULL) {
        if (!$limit) {
            return $this->options['display-limit'];
        } else {
            return $limit;
        }
    }
}   /* End of class declaration */