<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

/**
 * rp_Activation.class.php - Class for plugin activation
 *
 * @package Recipe Press
 * @subpackage classes
 * @author GrandSlambert
 * @copyright 2009-2010
 * @access public
 */

class rp_Activate extends rp_Base {

/**
 * Activation function - creates required databases.
 */
    public function activate() {
        if ($this->version >= '0.8') {
            $this->upgrade08();
        }

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $this->makeTable('recipes');
        $this->makeTable('categories');
        $this->makeTable('ingredients');
        $this->makeTable('options');
        $this->makeTable('comments');

        if ($this->betaTest) {
            wp_die('Beta Testing Activation');
        }
    }

    /**
     * Method to create or update a table
     */
    public function makeTable($table) {
        global $wpdb;
        $tableName = $this->tables[$table];
        $file = $this->pluginPath . '/classes/sql/rp_' . $table . '_create.php';

        require_once($file);

        if ($wpdb->get_var("SHOW TABLES LIKE '$tableName'") != $tableName) {
            $results = dbDelta($tableSQL);

            if ($defaultSQL) {
                $wpdb->query($defaultSQL);
            }
        }
        else {
            $this->updateTable($tableName, $fields);
        }
    }



    public function upgrade08() {
        $options = array(
            'recipe_press_default_category'     => 'default-category',
            'recipe_press_display_page'         => 'display-page',
            'recipe_press_submit_page'          => 'submit-page',
            'recipe_press_submit_location'      => 'submit-location',
            'recipe_press_submit_title'         => 'submit-title',
            'recipe_press_required_fields'      => 'required-fields',
            'recipe_press_custom_css'           => 'custom-css',
            'recipe_press_require_login'        => 'require_login',
            'recipe_press_widget_target'        => 'widget-target',
            'recipe_press_widget_items'         => 'widget-items',
            'recipe_press_force_recaptcha'      => 'force-recaptcha',
            'recipe_press_recaptcha_public'     => 'recaptcha-public',
            'recipe_press_recaptcha_private'    => 'recaptcha-private'
        );

        foreach ($options as $old=>$new) {
            if ($this->options[$new] = get_option($old) ) {
                delete_option($old);
                $saveOptions = true;
            }
        }

        if ($saveOptions) {
            if ( !add_option($this->optionsName, $this->options) ) {
                update_option($this->optionsName, $this->options);
            }
        }
    }

    /**
     * Update a table when activating.
     *
     * @global global $wpdb
     * @param string $tableName
     * @param array $fields
     */
    public function updateTable($tableName, $fields) {
        global $wpdb;

        $sql = 'DESCRIBE `' . $tableName . '`';
        $table = $wpdb->get_col($sql);

        foreach ($fields as $field=>$type) {
            if ( in_array($field, $table) ) {
                $wpdb->query('ALTER TABLE `' . $tableName . '` CHANGE `' . $field . '` ' . $type);
            }
            else {
                $wpdb->query('ALTER TABLE `' . $tableName . '` ADD ' . $type . ' AFTER `' . $lastfield . '`'   );
            }

            $lastfield = $field;
        }
    }
}   /* End of class declaration */