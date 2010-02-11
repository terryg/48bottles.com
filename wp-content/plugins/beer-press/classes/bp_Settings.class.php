<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

/**
 * bp_Settings.class.php - Class for settings management
 *
 * @package Recipe Press
 * @subpackage classes
 * @author GrandSlambert
 * @copyright 2009-2010
 * @access public
 */

class bp_Settings extends bp_Base {
    /* Set Variables */
    const menuName = 'recipe-press-settings';
    protected $view = 'settings';

    /**
     * Method Constructor
     */
    public function __construct() {
        parent::__construct();

        add_filter('whitelist_options', array(&$this, 'whitelistOptions'));
        add_action('admin_init', array(&$this, 'registerOptions'));
    }

    /**
     * Add whitelist options to save options.
     *
     * @param array $whitelist
     * @return array
     */
    function whitelistOptions($whitelist) {
        if (is_array($whitelist)) {
            $option_array = array($this->pluginName=>$this->optionsName);
            $whitelist = array_merge($whitelist, $option_array);
        }

        return $whitelist;
    }

    function requireLoginCheck() {
        global $ID;

        $page = get_page($this->options['submit-page']);

        if (!$this->options['require-login'] or is_user_logged_in() or preg_match('/login/', $_SERVER['REQUEST_URI'])) {
            return;
        } elseif ( preg_match('/'.$page->post_name.'/', $_SERVER['REQUEST_URI'])) {

            if (function_exists(wp_login_url))
                $url = wp_login_url($_SERVER['REQUEST_URI']);
            else {
                $url = get_option('siteurl').'/wp-login.php?redirect_to=' . $_SERVER['REQUEST_URI'];
            }

            wp_safe_redirect($url);
        }
    }

    function settingsVarList() {
        foreach ($this->settingsVars as $var) {
            echo $prefix . $var;
            $prefix = ',';
        }
    }

    /**
     * Register the options for Wordpress MU Support
     */
    function registerOptions() {
        register_setting( $this->optionsName, $this->optionsName);
    }
}
