<?php
/*
Plugin Name: Beer Press
Plugin URI: http://48bottles.com/beer-press
Description: Turn your Wordpress site into a full fledged homebrew recipe sharing system.
Author: TerryLorber
Version: 0.0.1
Author URI: http://www.rideside.net/~tgl
Acknowledgements: Based on Recipe Press 0.9.8 by GrandSlambert

**************************************************************************

Copyright (C) 2009-2010 GrandSlambert

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

**************************************************************************

*/

/* Load the classes */
require_once ('classes/bp_Base.class.php');
require_once ('classes/bp_Recipe_Base.class.php');
require_once ('classes/bp_Recipes.class.php');
require_once ('classes/bp_Categories.class.php');
require_once ('classes/bp_Fermentables.class.php');
require_once ('classes/bp_Hops.class.php');
require_once ('classes/bp_Yeasts.class.php');
require_once ('classes/bp_Miscs.class.php');
require_once ('classes/bp_Tags.class.php');
require_once ('classes/bp_Comments.class.php');
require_once ('classes/bp_Settings.class.php');
require_once ('classes/bp_Add_Recipe.class.php');
require_once ('classes/bp_Overview.class.php');
require_once ('classes/bp_Contributors.class.php');
require_once ('classes/bp_Activate.class.php');
require_once ('classes/bp_Deactivate.class.php');

/* Load the helpers */
require_once ('helpers/bp_inflector.php');
require_once ('helpers/bp_importers.php');
require_once ('helpers/bp_pagination.php');

/* Class Declaration */
class beerPress extends bp_Base {
    const menuName = 'beer-press-overview';

    /**
     * Class Constructor Method
     */
    public function __construct() {
        parent::__construct();

        /* Load Langague Files */
        $langDir = dirname( plugin_basename(__FILE__) ) . '/lang';
        load_plugin_textdomain( 'beer-press', false, $langDir, $langDir );

        /* Add Options Pages and Links */
        add_action('admin_menu', array(&$this, 'addAdminPages'));
        add_filter('plugin_action_links', array(&$this, 'addConfigureLink'), 10, 2);

        /* Add short codes. */
        add_shortcode('recipe-list', array(new bp_Recipe_Base, 'listShortcode') );
        add_shortcode('recipe-show', array(new bp_Recipe_Base, 'showRecipeShortcode') );
        add_shortcode('recipe-form', array(new bp_Recipe_Base, 'formShortcode') );
        add_shortcode('recipe-cats', array(new bp_Categories, 'shortcode') );
        add_shortcode('recipe-fermentables', array(new bp_Recipe_Base, 'fermentablesShortcode') );

        /* Add filters and hooks. */
        add_action('wp_head', array($this, 'addHeader') );
        add_action('admin_head', array($this, 'adminHeader') );
        add_action('wp_ajax_beer_press_recipe_title', array($this, 'ajaxLookupRecipeTitle'));
        add_action('wp_ajax_beer_press_recipe_slug', array($this, 'ajaxLookupRecipeslug'));
        add_action('wp_ajax_beer_press_comment_action', array(new bp_Comments, 'ajaxCommentAction'));
        add_filter('the_content', array(new bp_Add_Recipe, 'addSubmitForm') );
        add_filter('preprocess_comment', array(new bp_Comments, 'commentPostFilter'));
        add_action('delete_attachment', array(new bp_Base, 'onDeleteAttachment'));
        
        /* Watch for form submit */
        add_action('wp', array(new bp_Add_Recipe, 'submitFormCheck'));


        /* Set up the Rewrite Rules */
        if ($this->options['display-page']) {
            add_filter('rewrite_rules_array', array($this, 'insertRules') );
            add_filter('query_vars', array($this, 'queryVars') );
            add_filter('init', array($this, 'flushRules') );
        }

        /* Check if reCaptcha code is loaded and if WP-reCAPTCHA is active */
        $plugins = get_option('active_plugins');
        if (!defined('RECAPTCHA_API_SERVER') and !in_array('wp-recaptcha/wp-recaptcha.php', $plugins)) {
            require_once('classes/recaptchalib.php');
        }
    }

    /**
     * Create Rewrite Rules for Recipe Pages
     *
     * @global object $wp_rewrite
     * @return array
     */
    public function getRewriteRules() {
        global $wp_rewrite; /* Global WP_Rewrite class object */
        return $wp_rewrite->rewrite_rules();
    }

    /**
     * Flush Rewrite Rules
     *
     * @global object $wp_rewrite
     */
    public function flushRules() {
        global $wp_rewrite;
        $wp_rewrite->flush_rules();
    }

    /**
     * Add the new rewrite rules
     *
     * @global object $wp_rewrite
     * @param array $rules
     * @return array
     */
    public function insertRules($rules) {
        global $wp_rewrite;

        $displayPage = get_page_link($this->options['display-page']);
        $homePage = get_option('home');

        $pageLink = str_replace($homePage, '', $displayPage );
        $pageLink = trim($pageLink, '/');

        $newrules = array();

        if ($homePage == $displayPage) {
            $categories = $this->getRecords('categories');
            foreach ($categories as $category) {
                $newrules['('.$category->slug.')/view$'] = 'index.php?pagename=recipes' . $pageLink . '&category=$matches[1]';
                $newrules['('.$category->slug.')/(.*)$'] = 'index.php?pagename=recipes' . $pageLink . '&category=$matches[1]&recipe=$matches[2]';
            }
        } else {
            $newrules['(' . $pageLink . ')/(.*)/view$'] = 'index.php?pagename=' . $pageLink . '&category=$matches[2]';
            $newrules['(' . $pageLink . ')/(.*)/(.*)$'] = 'index.php?pagename=' . $pageLink . '&category=$matches[2]&recipe=$matches[3]';
        }

        //bp_Base::debug($newrules);

        return $newrules + $rules;
    }

    /**
     * Add query vars for the Rewrite Rules
     *
     * @param array $vars
     * @return array
     */
    public function queryVars($vars) {
        array_push($vars, 'category');
        array_push($vars, 'recipe');
        array_push($vars, 'recipe-id');
        return $vars;
    }

    /**
     * Add items to the administration header.
     */
    public function adminHeader() {
        if (preg_match('/recipe-press/', $_SERVER['REQUEST_URI'])) {
            $file = $this->getTemplate('recipe-press-admin', '.css', 'url');
            print '<link rel="stylesheet" media="screen" type="text/css" href="' . $file .'" />' . "\n";
            print '<script type="text/javascript" src="' . $this->pluginDir . '/js/jquery-autocomplete/jquery.autocomplete.js"></script>' . "\n";
            print '<script type="text/javascript" src="' . $this->pluginDir . '/js/scripts.js"></script>' . "\n";
            print '<script type="text/javascript" src="' . $this->pluginDir . '/js/recipe-form.js"></script>' . "\n";
            print '<script type="text/javascript" src="' . $this->pluginDir . '/js/overlib/overlib.js"></script>' . "\n";
        }
    }

    /**
     * Adds the top level and submenus.
     */
    public function addAdminPages() {
        global $wp_version;

        if (function_exists('add_object_page')) {
            add_object_page($this->pluginName, $this->pluginName, array_search($this->options['overview-role'], $this->roles), recipePress::menuName, array(new bp_Overview, 'subPanel'), $this->pluginDir . '/icons/small_icon.gif' );
        }
        else {
            add_menu_page($this->pluginName, $this->pluginName, array_search($this->options['overview-role'], $this->roles), recipePress::menuName, array(new bp_Overview, 'subPanel'), $this->pluginDir . '/icons/menu_icon.gif' );
        }

        add_submenu_page(recipePress::menuName, $this->pluginName . ' &raquo; Recipes', 'Recipes ' . $this->showPending('recipes'), array_search($this->options['recipes-role'], $this->roles), bp_Recipes::menuName, array(new bp_Recipes, 'subPanel'));
        add_submenu_page(recipePress::menuName, $this->pluginName . ' &raquo; Add Recipe', 'Add Recipe', array_search($this->options['add-recipe-role'], $this->roles), bp_Add_Recipe::menuName, array(new bp_Add_Recipe, 'subPanel'));
        add_submenu_page(recipePress::menuName, $this->pluginName . ' &raquo; Ingredients', 'Ingredients', array_search($this->options['ingredients-role'], $this->roles), bp_Ingredients::menuName, array(new bp_Ingredients, 'subPanel'));
        add_submenu_page(recipePress::menuName, $this->pluginName . ' &raquo; Comments', 'Comments' . $this->showPending('comments'), array_search($this->options['comments-role'], $this->roles), bp_Comments::menuName, array(new bp_Comments, 'subPanel'));
        add_submenu_page(recipePress::menuName, $this->pluginName . ' &raquo; Categories', 'Categories', array_search($this->options['categories-role'], $this->roles), bp_Categories::menuName, array(new bp_Categories, 'subPanel'));
        add_submenu_page(recipePress::menuName, $this->pluginName . ' &raquo; Tags', 'Tags', array_search($this->options['tags-role'], $this->roles), bp_Tags::menuName, array(new bp_Tags, 'subPanel'));
        add_submenu_page(recipePress::menuName, $this->pluginName . ' &raquo; Settings', 'Settings', 8, bp_Settings::menuName, array(new bp_Settings, 'subPanel'));
        add_submenu_page(recipePress::menuName, $this->pluginName . ' &raquo; Contributors', 'Contributors', array_search($this->options['overview-role'], $this->roles), bp_Contributors::menuName, array(new bp_Contributors, 'subPanel'));

        /* Use the bundled jquery library if we are running WP 2.5 or above */
        if (version_compare($wp_version, '2.5', '>=')) {
            wp_enqueue_script('jquery', false, false, '1.2.3');
        }
    }

    /**
     * Addes a number to the menu item if pending recipes exist.
     *
     * @return $string
     */
    public function showPending($table = 'recipes') {
        $pending = $this->getCount($table, 'pending');

        if ($pending > 0) {
            $output = '<span id="awaiting-mod" class="count-1">
                <span class="pending-count">' . $pending .'</span>
                </span>';
            return $output;
        }
    }

    /**
     * Adds a settings link next to Login Configurator on the plugins page
     * @staticvar <type> $this_plugin
     * @param array $links
     * @param string $file
     * @return array
     */
    public function addConfigureLink($links, $file) {
        static $this_plugin;

        if (!$this_plugin) {
            $this_plugin = plugin_basename(__FILE__);
        }

        if ($file == $this_plugin) {
            $settings_link = '<a href="' . $this->getAdminURL(bp_Settings::menuName) . '">' . __('Settings', 'recipe-press') . '</a>';
            array_unshift($links, $settings_link);
            $overview_link = '<a href="' . $this->getAdminURL(bp_Overview::menuName) . '">' . __('Overview', 'recipe-press') . '</a>';
            array_unshift($links, $overview_link);
        }

        return $links;
    }

    /**
     * Recipe Title Lookup AJAX Method
     */
    public function ajaxLookupRecipeTitle() {
        $recipes = $this->getRecipes(array('recipe' => mysql_escape_string($_GET['q'])));

        foreach ($recipes as $recipe) {
            print $recipe->title . '|' . $recipe->id . "\n";
        }

        die();  /* Ajax functions must die or they return an extra 0 */
    }

    /**
     * Recipe Slug Lookup AJAX Method
     */
    public function ajaxLookupRecipeSlug() {
        $recipes = $this->getRecipes(array('slug' => mysql_escape_string($_GET['q'])));

        foreach ($recipes as $recipe) {
            print $recipe->title . '|' . $recipe->id . "\n";
        }

        die();  /* Ajax functions must die or they return an extra 0 */
    }

    /**
     * Method to add buttuns for shortcodes to the editor.
     *
     * Not functional in this verison.
     */
    public function beer_press_addbuttons() {
    /* Don't bother doing this stuff if the current user lacks permissions */
        if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
            return;

        /* Add only in Rich Editor mode */
        if ( get_user_option('rich_editing') == 'true') {
            add_filter("mce_external_plugins", array($this, "add_beer_press_tinymce_plugin") );
            add_filter('mce_buttons', array($this, 'register_beer_press_button') );
        }
    }

    /**
     * Register a new button for the editor.
     */
    public function register_beer_press_button($buttons) {
        array_push($buttons, "separator", "beerpress");
        return $buttons;
    }

    /**
     * Load the TinyMCE plugin : editor_plugin.js (wp2.5)
     * @param array $plugin_array
     * @return array
     */
    public function add_beer_press_tinymce_plugin($plugin_array) {
        print "Adding plugin";
        $plugin_array['beer_press'] = $this->pluginPath.'/js/editor_plugin.js';
        return $plugin_array;
    }
} /* End Class Definition */

/* Pre 2.6 Compatibility */
if ( !defined('WP_CONTENT_DIR') )
    define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );

/* Instantiate the Plugin */
$RECIPEPRESSOBJ = new recipePress;

register_activation_hook(__FILE__, array(new bp_Activate, 'activate') );
register_deactivation_hook(__FILE__, array(new bp_Deactivate, 'deactivate') );

/* Login Requirement Check */
if ($RECIPEPRESSOBJ->options['require-login']) {
    add_action('wp', array(new bp_Settings, 'requireLoginCheck'));
}

/* Include Widgets */
include_once('widgets/list-widget.php');
include_once('widgets/category-widget.php');
