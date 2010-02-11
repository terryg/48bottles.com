<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

/**
 * bp_Recipe_Base.clsses.php - Class for recipe base
 *
 * @package Beer Press
 * @subpackage classes
 * @author tgl@rideside.net
 * @copyright 2010
 * @access public
 */

class bp_Recipe_Base extends bp_Base {
    /* Set Variables */
    const menuName = 'beer-press-recipes-base';
    protected $view = 'add-recipe';
    public $formErrors = array();

    /**
     * Constructor Method
     */
    function __construct() {
        parent::__construct();

        add_filter('admin_head', array($this, 'add_tinymce'));
        if (preg_match('/recipe-press/', $_SERVER['REQUEST_URI'])) {
            add_action( 'media_buttons_context' , array($this, 'media_buttons') );
        }
    }

    function add_tinymce() {
        wp_admin_css('thickbox');
        wp_print_scripts('jquery-ui-core');
        wp_print_scripts('jquery-ui-tabs');
        wp_print_scripts('post');
        wp_print_scripts('editor');
        add_thickbox();
        //wp_print_scripts('media-upload');
        if (function_exists('wp_tiny_mce')) wp_tiny_mce();
    }

    function media_buttons() {
        return;
    }

    /**
     * Get the recipe information from the POST variable.
     *
     * @return array
     */
    function input($object = false) {
        global $current_user;
        get_currentuserinfo();

        if (!$_POST['content']) {
            $_POST['content'] = $_POST['instructions'];
        }

        $slug = $this->slugify($_POST['slug'], $_POST['title']);

        /* Process the image */
        if ($_FILES['image'] and !$_FILES['image']['error']) {
            $media_id = $this->processImage($slug);
        } else {
            $media_id = $_POST['media_id'];
        }

        $ingredientArray = $_POST['ingredients'];

        if (is_array($ingredientArray)) {

            foreach ($_POST['ingredients'] as $id=>$ingredient) {

                if ($ingredient['item']) {
                    $ingredients[$id] = $ingredient;

                    if ($ingredient['create-page']) {

                        if (!$parent = $ingredients['page-link']) {
                            $parent = $this->options['ingredient-parent'];
                        }

                        $rp_new_post = array(
                            'post_title'    => ucwords($ingredient['item']),
                            'post_content'  => '[recipe-ingredients item=' . $this->slugify($ingredient['item'], NULL, 'ingredient') . ' /]',
                            'post_type'     => 'page',
                            'post_parent'   => $parent,
                            'post_status'   => $this->options['ingredient-parent-status'],
                            'post_author'   => $current_user->ID
                        );

                        $ingredients[$id]['page-link'] = wp_insert_post($rp_new_post);
                        $ingredients[$id]['create-page'] = NULL;
                    }
                }
            }
        } else {
            $ingredients = $this->convert_ingredients($ingredientArray);
        }

        if ($object) {
            include('sql/rp_recipes_create.php');

            foreach ($_POST as $field=>$value) {

                if (array_key_exists($field, $fields) ) {
                    $recipe->$field = $value;
                }
            }

            return $recipe;
        } else {
            return array(
            'title'                 => $_POST['title'],
            'slug'                  => $slug,
            'user_id'               => $_POST['user_id'],
            'media_id'              => $media_id,
            'category'              => $_POST['category'],
            'template'              => $_POST['template'],
            'notes'                 => $_POST['notes'],
            'prep_time'             => $_POST['prep_time'],
            'cook_time'             => $_POST['cook_time'],
            'ready_time'            => $this->readyTime(),
            'hide_ingredients_header' => $_POST['hide_ingredients_header'],
            'ingredients'           => serialize($ingredients),
            'instructions'          => $_POST['content'],
            'servings'              => $_POST['servings'],
            'servings_size'         => $_POST['servings_size'],
            'status'                => $_POST['status'],
            'featured'              => $_POST['featured'],
            'comments_open'         => $_POST['comments_open'],
            'submitter'             => $_POST['submitter'],
            'submitter_email'       => $_POST['submitter_email'],
            'updated'               => $this->now(),
            );
        }
    }

    /**
     * Insert a Recipe
     *
     * @global object $wpdb
     * @return boolean
     */
    public function insertRecipe($recipe = NULL) {
        global $wpdb;

        if ( !is_array($recipe) ) {
            $recipe = $this->input();
        }

        $recipe['added'] = $this->now();
        $recipe['updated'] = $this->now();

        if ($recipe['status'] == 'active') {
            $recipe['published'] == $this->now();
        }

        $results = $wpdb->insert( $this->tables['recipes'], $recipe);
        return $this->getOneRecord('recipes', $wpdb->insert_id);
    }

    public function addSubmitForm($content) {
        global $post, $wp_query;

        if ($post->ID == $this->options['submit-page']) {

            if ($wp_query->get('recipe') == 'success') {
                $this->recipe = $this->getRecipe($wp_query->get('recipe-id'));
                $template = $this->getTemplate('recipe-thanks');
                ob_start();
                require($template);
                $content = ob_get_contents();
                ob_end_clean();
            } else {

                $form = $this->formCode();

                switch ($this->options['form-location']) {
                    case 'before':
                        $content = $form . $content;
                        break;
                    case 'replace':
                        $content = $form;
                        break;
                    default:
                        $content.= $form;
                        break;
                }
            }
        }
        return $content;
    }

    /**
     * Method for the Short code to display the form
     *
     * @global object $wp_query
     * @global object $wpdb
     * @return string
     */
    public function formCode() {
        global $wp_query,$wpdb;

        if ($this->options['recaptcha-public']) {
            $this->showCaptcha = true;
        } else {
            $this->showCaptcha = false;
        }

        if (is_user_logged_in() and !$this->options['force-recaptcha']) {
            $this->showCaptcha = false;
        }

        return $this->getSubmitForm();
    }

    protected function requiredField($field) {
        if (in_array($field, $this->options['required-fields'])) {
            echo ' recipe-press-required';
        }
    }

    protected function checkForm() {
        $isErrors = false;

        $this->recipe = $this->input(true);

        foreach ($this->options['required-fields'] as $field) {
            if (!$this->recipe->$field) {
                $this->recipe->errors[$field] = true;
                $isErrors = true;
            }
        }

        /* Check the reCaptcha */
        if ($this->options['recaptcha-public'] and $this->showCaptcha) {
            if ($_POST['check_captcha']) {
                $this->recipe->errors['validate'] = $this->checkReCaptcha();
            } else {
                $this->showCaptcha = false;
            }

            if ($this->recipe->errors['validate']->is_valid) {
                $this->showCaptcha = false;
            } else {
                $isErrors = true;
            }
        }

        return $isErrors;
    }

    protected function checkReCaptcha() {
        return recaptcha_check_answer (
        $this->options['recaptcha-private'],
        $_SERVER["REMOTE_ADDR"],
        $_POST["recaptcha_challenge_field"],
        $_POST["recaptcha_response_field"]
        );
    }

    /**
     * formShortCode depricated.
     *
     * @param array $atts
     * @return blank
     */
    public function formShortCode($atts) {
        return;
    }

    /**
     * Display the submit form.
     *
     * @return string
     */
    public function getSubmitForm() {
        $file = ($this->getTemplate('recipe-submit'));
        $public = true;

        if ($_POST) {
            $this->checkForm();
        }

        if (!get_option('permalink_structure') ) {
            $formaction = get_page_link($this->options['submit-page']) .'&amp;recipe=user-submission';
        }
        else {
            $formaction = get_page_link($this->options['submit-page']) .'?recipe=user-submission';
        }

        if (!$_POST['ingredients']) {
            $_POST['ingredients'] = array();
            for ($ctr=0; $ctr<$this->options['ingredients-fields']; ++$ctr) {
                array_push($_POST['ingredients'], array('size'=>'none'));
            }
        }

        ob_start();
        require($file);
        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }

    /**
     * Method for the show-recipe shortcode
     *
     * @global object $wpdb
     * @param array $atts
     * @return string
     */
    public function showRecipeShortcode($atts) {
        global $wpdb;

        extract(shortcode_atts(array(
            // Query Attributes
            'recipe'	=> NULL,
            ), $atts));

        $this->recipe = $this->getRecipeFromSlug($recipe, true);

        if ( isset($this->recipe) and $this->get_recipe_title() ) {
            $file = $this->getTemplate('recipe-display/' . $this->recipe->template);
            $this->hideComments = true;

            ob_start();
            require ($file);
            $output = ob_get_contents();
            ob_end_clean();

            return $output;
        }
        else {
            return 'Sorry, we could not find the ' . $recipe . ' recipe in our records.';
        }
    }

    function ingredientsShortcode($atts) {
        global $wpdb;

        extract(shortcode_atts(array(
            'item'          => NULL,

            // Display Attributes
            'title'         => 'Recipes containing ',
            'ul_class'      => 'recipe-list',
            'li_class'      => 'recipe-item',
            'group_cat'     => false,
            'show_author'   => false,
            ), $atts));

        $ingredient = $this->getOneRecord('ingredients', NULL, array('slug'=>$item));

        $query = '
            select
                    `' . $this->tables['recipes'] . '`.*,
                    `' . $this->tables['categories'] . '`.`name` as `category_name`,
                    `' . $this->tables['categories'] . '`.`slug` as `category_slug`
            from `' . $this->tables['recipes'] . '`
            left join `' . $this->tables['categories'] . '` on `' . $this->tables['categories'] . '`.`id` = `' . $this->tables['recipes'] . '`.`category`
            where `' . $this->tables['recipes'] . '`.`status` = "active" and `' . $this->tables['recipes'] . '`.`id` in (' . $ingredient->recipes .')
        ';

        $recipes = $wpdb->get_results($query);

        if (!$group_cat) {
            $output.= '<h3 class="recipe-title">' . $title . $ingredient->name . '</h3>';
            $output.= '<ul class="' . $ul_class . '">';
        }

        $output.= $this->listRecipes($recipes, array('group_cat'=>$group_cat, 'li_class'=>$li_class) );
        $output.= '</ul>';

        return $output;

    }

    /**
     * Method for the recipe-list shortcode
     *
     * @global object $wpdb
     * @global object $wp_query
     * @global object $current_user
     * @param array $atts
     * @return string
     */
    function listShortcode($atts) {
        global $wpdb, $wp_query;

        $category = $wp_query->get('category');
        $recipe = $wp_query->get('recipe');

        $output = '';

        $submitPage = get_page($this->options['submit-page']);

        if ( $recipe ) {
            if ($recipe == 'submitted') {
                $file = $this->getTemplate('recipe-thanks');
            }
            else {
                if ( isset($recipe) ) {
                    $this->recipe = $this->getRecipeFromSlug($recipe, true);

                    if ( isset($this->recipe) and $this->get_recipe_title() ) {
                        $file = $this->getTemplate('recipe-display/' . $this->recipe->template);
                        ob_start();
                        require($file);
                        $output = ob_get_contents();
                        ob_end_clean();

                        return $output;
                    }

                    else {
                        return 'Sorry, we could not find the ' . $recipe . ' recipe in our records.';
                    }
                }

            }
        }
        elseif ( isset($category) and $category != '' ) {
            $category = $this->getOneRecord('categories', NULL, array('slug'=>$category));
            $atts['category_parent'] = $category->id;

            if ( isset($category->description) ) {
                $output.= '<p>' . $category->description . '</p>';
            }
        }

        extract(shortcode_atts(array(
            // Query Attributes
            'category'      => NULL,
            'category_parent' => NULL,
            'tag'           => NULL,
            'sort_column'   => 'title',
            'sort_order'    => 'asc',
            'limit'         => NULL,

            // Display Attributes
            'style'         => 'list',
            'template'      => $this->options['recipe-list-template'],
            'title'         => 'Recipes',
            'ul_class'      => 'recipe-list',
            'li_class'      => 'recipe-item',
            'group_cat'     => false,
            'show_author'   => false,
            ), $atts));

        $query = '
            select
                    `' . $this->tables['recipes'] . '`.*,
                    `' . $this->tables['categories'] . '`.`name` as `category_name`,
                    `' . $this->tables['categories'] . '`.`slug` as `category_slug`
            from `' . $this->tables['recipes'] . '`
            left join `' . $this->tables['categories'] . '` on `' . $this->tables['categories'] . '`.`id` = `' . $this->tables['recipes'] . '`.`category`
            where `' . $this->tables['recipes'] . '`.`status` = "active"
        ';

        if ( isset($category) ) {
            $query.= ' and `' . $this->tables['categories'] . '`.`slug` = "' . $category . '"';
        }

        if ( isset($category_parent) ) {
            $query.= ' and (`' . $this->tables['categories'] . '`.`id` = "' . $category_parent . '" or `' . $this->tables['categories'] . '`.`parent` = "' . $category_parent . '")';
        }

        if ($group_cat) {
            $orderPrefix =  ' `category_name` ' . $group_cat . ', ';
        }

        $query.= ' order by ' . $orderPrefix . '`' . $sort_column . '` ' . $sort_order;

        if ( isset($limit) )
            $query.= ' limit ' . $limit;

        $recipes = $wpdb->get_results($query);

        if (!$group_cat) {
            $output.= '<h3 class="recipe-title">' . $title . '</h3>';
            $output.= '<ul class="' . $ul_class . '">';
        }

        $output.= $this->listRecipes($recipes, array('group_cat'=>$group_cat, 'li_class'=>$li_class, 'style'=>$style, 'template'=>$template) );
        $output.= '</ul>';

        return $output;
    }

    /**
     * Method to create a list from the recipes.
     *
     * @param array $recipes        Array containing one object per recipe.
     * @param array $options        Options for displaying recipes.
     * @return string
     */
    function listRecipes($recipes = NULL, $options = NULL) {
        if ( !is_array($recipes) )
            return;

        if ($options['target']) {
            $target = 'target="' . $options['target'] . '"';
        } else {
            unset ($target);
        }

        if (!$options['li_class']) {
            $options['li_class'] = 'rp_recipes_list_item';
        }

        if ($options['icon-size']) {
            $size = array($options['icon-size'], $options['icon-size']);
        } else {
            $size = array($this->options['widget-icon-size'], $this->options['widget-icon-size']);
        }

        foreach ($recipes as $this->recipe) {
            switch ($options['style']) {
                case 'template':
                    if ($options['group_cat'] and $lastCat != $this->recipe->category_name) {
                        $output.= '<h3 class="recipe-title">' . stripslashes_deep($this->recipe->category_name) . '</h3>';
                    }

                    $file = $this->getTemplate('recipe-list/' . $this->get_recipe_list_template($options['template']));

                    ob_start();
                    include ($file);
                    $contents = ob_get_contents();
                    ob_end_clean();

                    $output.= $contents;
                    break;
                default:
                    if ($options['group_cat'] and $lastCat != $this->recipe->category_name) {

                        if ($started) {
                            $output.= '</ul>';
                        }

                        $output.= '<h3 class="recipe-title">' . stripslashes_deep($this->recipe->category_name) . '</h3>';
                        $output.= '<ul class="' . $ul_class . '" id="recipes-' . $this->recipe->category_slug . '">';
                        $started = true;
                    }

                    if ($options['show-icon'] and $this->recipe->media_id ) {
                        $icon = wp_get_attachment_image( $this->recipe->media_id, $size, false );
                    } else {
                        $icon = '';
                    }

                    $output.= '<li class="' . $options['li_class'] . '">' . $icon . ' <a href="' . $this->getRecipeLink() . '" '. $target . '>' . $this->get_recipe_title() . '</a></li>';
                    break;
            }


            $lastCat = $this->recipe->category_name;
        }

        return $output;
    }

    /* Template Tags */
    public function get_recipe_user_id() {
        return $this->recipe->user_id;
    }

    public function recipe_user_id() {
        echo $this->get_recipe_user_id();
    }

    public function get_recipe_media_id() {
        return $this->recipe->media_id;
    }

    public function recipe_media_id() {
        echo $this->get_recipe_media_id();
    }

    public function get_recipe_image($size = 'thumbnail', $icon = false, $align = 'left') {
        $output = '<div id="recipe-press-image-' . $this->recipe->id . '" class="recipe-press-align-'.$align.' recipe-press-image recipe-press-image-' . $this->recipe->id . '">';
        $output.= wp_get_attachment_image( $this->recipe->media_id, $size, $icon );
        $output.= '</div>';

        return $output;
    }

    public function recipe_image($size = 'thumbnail', $icon = false) {
        echo $this->get_recipe_image($size, $icon);
    }

    public function get_recipe_submitter() {
        if ($this->recipe->submitter) {
            return stripslashes_deep($this->recipe->submitter);
        }
        elseif (is_null($this->recipe->user_id)) {
            global $current_user;
            get_currentuserinfo();
            return $current_user->display_name;
        }
        elseif ($this->recipe->user_id == 0) {
            /* translators: Displayed when a recipe is submitted by a non-member. */
            return __('Non Member', 'recipe-press');
        } else {
            $user_info = get_userdata($this->recipe->user_id);
            return $user_info->display_name;
        }
    }

    public function recipe_submitter() {
        echo $this->get_recipe_submitter();
    }

    public function get_recipe_slug() {
        return $this->recipe->slug;
    }

    public function recipe_slug() {
        echo $this->get_recipe_slug();
    }

    public function get_recipe_notes($attrs = array()) {
        $notes = $this->recipe->notes;

        if ($attrs['length']) {
            $notes = rp_inflector::trim_excerpt($notes, $attrs['length'], $attrs['allowed_tags']);
        }

        return stripslashes_deep($notes);
    }

    public function recipe_notes($attrs = array() ) {
        echo $this->get_recipe_notes($attrs);
    }

    public function get_recipe_prep_time($type = 'single') {
        if ($this->recipe->prep_time) {
            $output.= '<li id="recipe-prep-' . $this->recipe->id . '" class="recipe-prep">';
            /* translators: Title of the "prep time" field on recipe display pages. */
            $output.= '<span class="details-header details-header-prep">' . __('Prep Time', 'recipe-press') . '</span>';

            switch ($type) {
                case 'double':
                    $output.= ':<br>' . $this->recipe->prep_time. $this->options['minute-text'];
                    break;
                default:
                    $output.= ': ' . $this->recipe->prep_time. $this->options['minute-text'];
                    break;
            }

            $output.= '</li>';

            return $output;
        }
    }

    public function recipe_prep_time($type = 'single') {
        echo $this->get_recipe_prep_time($type);
    }

    public function get_recipe_cook_time($type = 'single') {
        if ($this->recipe->cook_time) {
            $output.= '<li id="recipe-cook-' . $this->recipe->id . '" class="recipe-cook">';
            /* translators: Title of the "cook time" on recipe display pages. */
            $output.= '<span class="details-header details-header-cook">' . __('Cook Time', 'recipe-press') . '</span>';

            switch ($type) {
                case 'double':
                    $output.=':<br>' . $this->recipe->cook_time. $this->options['minute-text'];
                    break;
                default:
                    $output.= ': ' . $this->recipe->cook_time. $this->options['minute-text'];
                    break;
            }

            $output.= '</li>';

            return $output;
        }
    }

    public function recipe_cook_time($type = 'single') {
        echo $this->get_recipe_cook_time($type);
    }

    public function get_recipe_ready_time($type = 'single') {
        if ($this->recipe->prep_time or $this->recipe->cook_time) {
            $output.= '<li id="recipe-ready-' . $this->recipe->id . '" class="recipe-ready">';
            /* translators: Title of the "ready time" field on recipe display pages. */
            $output.= '<span class="details-header details-header-cook">' . __('Ready In', 'recipe-press') . '</span>';

            switch ($type) {
                case 'double':
                    $output.= ':<br>' . $this->recipe->ready_time;
                    break;
                default:
                    $output.= ': ' . $this->recipe->ready_time;
                    break;
            }

            $output.= '</li>';

            return $output;
        }
    }

    public function recipe_ready_time($type = 'single') {
        echo $this->get_recipe_ready_time($type);
    }

    public function get_recipe_servings() {
        if ($this->recipe->servings) {
            /* translators: Displayed before serving information on recipe display pages. */
            return '<div class="reipe-servings">' . __('Servings', 'recipe-press') . ': ' . $this->recipe->servings . ' ' . $this->recipe->servings_size . '</div>';
        }
    }

    public function recipe_servings() {
        echo $this->get_recipe_servings();
    }

    public function convert_ingredients($ingredients = NULL) {

        if (!$ingredients) {
            $ingredients = $this->recipe->ingredients;
        }

        $ingredients = split("\n", $ingredients);
        $this->recipe->ingredients = array();

        foreach ($ingredients as $ingredient) {
            unset ($item);
            
            /* Get the quantity */
            preg_match('([0-9 /]*)', $ingredient, $result);
            preg_match('([0-9/]*)', $result[0], $test);
            $item['quantity'] = $result[0];
            $ingredient = str_replace($result[0], '', $ingredient);

            /* Get the size and item */
            preg_match('([a-z]*)', $ingredient, $result);
            $item['item'] = chop(str_replace($result[0], '', $ingredient));

            if ($item['item'] == '') {
                $item['item'] = $result[0];
                $item['size'] = 'none';
            } elseif ($this->getOptionIDByValue(rp_inflector::singular($result[0]), 'ingredient_size', 'value') == rp_inflector::singular($result[0])) {
                $item['size'] = rp_inflector::singular($result[0]);
            } else {
                $item['notes'] = $item['item'];
                $item['item'] = $result[0];
                $item['size'] = 'none';
            }

            array_push($this->recipe->ingredients, $item);
        }

        return $this->recipe->ingredients;
    }

    public function get_recipe_ingredients_header() {
        if (!$this->recipe->hide_ingredients_header) {
            return '<div class="recipe-section-title recipe-ingredients-title">' . __('Ingredients', 'recipe-press') . '</div>';
        } else {
            return false;
        }
    }

    public function recipe_ingredients_header() {
        echo $this->get_recipe_ingredients_header();
    }


    public function get_recipe_ingredients($admin = false) {
        if (!is_array($this->recipe->ingredients)) {
            $ingredients = unserialize($this->recipe->ingredients);

            if (count($ingredients) < 1) {
                if ($admin) {
                    /* translators: This message is displayed when an old version of a recipe is being converted to the format added in version 0.9 of the plugin. */
                    echo "<p>". _e('<strong>NOTICE:</strong> This recipe is being converted. Please verify the settings below match the older list.', 'recipe-press') . nl2br($this->recipe->ingredients). '</p>';
                }
                $this->convert_ingredients();
            } else {
                $this->recipe->ingredients = $ingredients;
            }
        }

        return $this->recipe->ingredients;
    }

    public function calculateIngredientSize($ingredient) {
        $ingredientSplit = preg_split("/[\s,]+/", $ingredient['quantity']);

        foreach ($ingredientSplit as $ingredientPart) {
            if (preg_match("/[\/]+/", $ingredientPart)) {
                $args = preg_split("/[\/]+/", $ingredientPart);
                $results = $args[0] / $args[1];
            } else {
                $results = $ingredientPart;
            }

            $total += $results;
        }

        return $total;
    }

    public function recipe_ingredients($admin = false) {
        $ingredients = $this->get_recipe_ingredients($admin);

        echo '<ul class="rp_ingredients">';

        foreach ($ingredients as $ingredient) {
            if ($ingredient['page-link']) {
                $link =  get_page_link($ingredient['page-link']);
                $target = "_top";
            } else {
                $slug = $this->slugify($ingredient['item'], NULL, 'ingredients', true);
                $ing = $this->getOneRecord('ingredients', NULL, array('slug'=>$slug));
                if ($ing->url) {
                    $link = $ing->url;
                    $target = '_blank';

                } else {
                    unset ($link);
                }
            }

            if ($ingredient['notes']) {
                $notes = ' <small>' . stripslashes_deep($ingredient['notes']) . '</small>';
            } else {
                unset ($notes);
            }

            if ($ingredient['size'] == 'none') {
                $ingredient['size'] = '';
            } else {
                $ingredient['total'] = $this->calculateIngredientSize($ingredient);

                if ($ingredient['total'] <= 1) {
                    $ingredient['size'] = rp_inflector::singular($ingredient['size']);
                } else {
                    $ingredient['size'] = rp_inflector::plural($ingredient['size']);
                }
            }

            if ($ingredient['size'] == 'divider') {
                echo '</ul><h4 class="recipe-section-title">' . $ingredient['item'] . '</h3><ul class="rp_ingredients">';
            } elseif ($link) {
                echo '<li class="rp_ingredient">' . $ingredient['quantity'] . ' ' . $ingredient['size'] . ' <a href="' . $link . '" target="' . $target . '">' . stripslashes_deep($ingredient['item']) . '</a> ' . $notes . '</li>';
            } else {
                echo '<li class="rp_ingredient">' . $ingredient['quantity'] . ' ' . $ingredient['size'] . ' ' . stripslashes_deep($ingredient['item']) . $notes. '</li>';
            }
        }

        echo '</ul>';
    }

    public function get_recipe_instructions() {
        return stripslashes_deep($this->recipe->instructions);
    }

    public function recipe_instructions() {
        echo $this->get_recipe_instructions();
    }

    public function get_recipe_template() {
        if ($this->recipe->template) {
            return $this->recipe->template;
        } else {
            return $this->options['recipe-template'];
        }
    }

    public function recipe_template() {
        echo $this->get_recipe_template();
    }

    public function get_recipe_list_template($template = NULL) {
        if ($template) {
            return $template;
        }
        elseif ($this->recipe->list_template) {
            return $this->recipe->list_template;
        } else {
            return $this->options['recipe-list-template'];
        }
    }

    public function recipe_list_template($template = NULL) {
        echo $this->get_recipe_list_template();
    }

    public function submitFormCheck() {
        global $wp_query, $post;

        $displayPage = $this->options['display-page'];
        $frontPage = get_option('page_on_front');

        if ($post->ID == $this->options['submit-page'] and $wp_query->get('recipe') == 'user-submission') {
            if (!$this->checkForm()) {
                $recipe = $this->insertRecipe();
                $link = get_page_link($this->options['submit-page']);

                if (get_option('permalink_structure') ) {
                    wp_safe_redirect($link . '?recipe=success&recipe-id=' . $recipe->id);
                } else {
                    wp_safe_redirect($link . '&recipe=success&recipe-id=' . $recipe->id);
                }
                die();
            }
        }
    }

    /* Submit Form Tags */
    public function fields($field, $text = NULL) {
        if (isset($text)) {
            return $text;
        } else {
            return $this->formFieldNames[$field];
        }
    }

    public function get_hidden_fields($name = '_wpnonce', $referer = true) {
        if (function_exists('wpmu_create_blog')) {
            $output = wp_nonce_field('recipe-press-options', $name, $referer, 'true');
        } else {
            $output = wp_nonce_field('update-options', $name, $referer, 'true');
        }

        $output.= '<input type="hidden" value="user-submit" name="action"/>';
        $output.= '<input type="hidden" name="user_id" id ="user_id" value="' . get_user_option('ID') . '" />';
        $output.= '<input type="hidden" name="status" value="' . $this->options['new-recipe-status'] . '" />';
        $output.= '<input type="hidden" name="template" value="' . $this->options['new-recipe-template'] . '" />';

        if ( is_user_logged_in() ) {
            $output.= '<input type="hidden" name="submitter" value="' . get_user_option('display_name') . '" />';
            $output.= '<input type="hidden" name="submitter_email" value="' . get_user_option('user_email') . '" />';
        }

        return $output;
    }

    public function hidden_fields($name = '_wpnonce', $referer = true) {
        echo $this->get_hidden_fields($name, $referer);
    }

    public function get_class_name($type = 'table', $field = NULL, $class = NULL) {
        $output = 'recipe-press-' . $type;

        if ($field) {
            $output.= ' recipe-press-' . $type . '-' . $field;
        }

        if ($class) {
            $output.= ' ' . $class;
        }

        return $output;
    }

    public function class_name($type = 'table', $field = NULL, $class = NULL) {
        echo $this->get_class_name($type, $field, $class);
    }

    public function get_form_label($field = NULL, $text = NULL, $class = NULL) {
        return '<label for="' . $field . '" class="recipe-press-label recipe-press-label-' . $field . ' ' . $class . '">' . $this->fields($field, $text) . '</label>';
    }

    public function form_label ($field = NULL, $text = NULL, $class = NULL) {
        echo $this->get_form_label($field, $text, $class);
    }

    public function get_form_field($field = NULL, $type = 'text', $value = NULL, $class = NULL) {
        if ( !isset($value) ) {
            $value = $this->recipe->$field;
        }

        switch ($type) {
            case 'ingredients':
                $file = $this->pluginPath.'/views/ingredients-form.php';
                ob_start();
                require($file);
                $output = ob_get_contents();
                ob_end_clean();
                break;
            case 'select':
                $output = '<select class="' . $this->get_class_name($type, $field, $class) . '" id="' . $field . '" name="' . $field . '">';

                switch ($field) {
                    case 'category':
                        $output.= $this->listOptions(rp_inflector::plural($field), isset($this->recipe->category) ? $this->recipe->category : $this->options['default-category']);
                        break;
                    default:
                        if ($field == 'servings_size') {
                            $keyName = 'serving_size';
                        } else {
                            $keyName = inflector::underscore($field);
                        }

                        $output.= $this->listOptions('options', $this->recipe->$field, 'key', 'value', $keyName);
                        break;
                }
                $output.= '</select>';
                break;
            case 'textarea':
                $output = '<textarea class="' . $this->get_class_name($type, $field, $class) . '" id="' . $field . '" name="' . $field . '">' . $value . '</textarea>';
                break;
                            default:
                $output = '<input type="text" class="' . $this->get_class_name($type, $field, $class) . '" id="' . $field . '" name="' . $field . '" value="' . $value . '">';
                                break;
        }

        if ($this->recipe->errors[$field]) {
                        $output.= '<br /><span class="recipe-press-error">' . sprintf(__('Missing required field: %1$s'), $this->fields($field, $text)) . '</span>';
        }
                        return $output;
    }

    public function form_field($field = NULL, $type = 'text', $value = NULL, $class = NULL) {
        echo $this->get_form_field($field, $type, $value, $class);
    }

    public function get_recaptcha_field() {
        if ($this->showCaptcha) {
            $output = recaptcha_get_html($this->options['recaptcha-public']);
            $output.= '<input type="hidden" name="check_captcha" value="1" readonly="readonly" />';

            if ($this->recipe->errors['validate']->is_valid) {
                $output.= '<br /><span class="recipe-press-error">' . sprintf(__('Please enter the words above.')) . '</span>';
            }

        } elseif ($this->recipe->errors['validate']->is_valid) {
            $output = __('Thank you for entering valid captcha text.', 'recipe-press');
        }

        return $output;
    }

    public function recaptcha_field() {
        echo $this->get_recaptcha_field();
    }

    public function get_submit_button($title = NULL) {
        if ($title == NULL) {
            $title = __('Submit Recipe');
        }
        return '
            <p class="submit">
                <input type="submit" value="' . $title . '" name="submit" class="button-primary"/>
            </p>
            ';
    }

    public function submit_button($title = NULL) {
        echo $this->get_submit_button();
    }
}