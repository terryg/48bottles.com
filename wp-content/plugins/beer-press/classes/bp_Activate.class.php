<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

/**
 * bp_Activate.class.php - Class for plugin activation
 *
 * @package Beer Press
 * @subpackage classes
 * @author TerryLorber
 * @copyright 2010
 * @access public
 */

class bp_Activate extends bp_Base {

/**
 * Activation function - creates required databases.
 */
    public function activate() {

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $this->makeTable('recipes');
        $this->makeTable('categories');
        $this->makeTable('fermentables');
        $this->makeTable('yeasts');
        $this->makeTable('hops');
        $this->makeTable('miscs');
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
        $file = $this->pluginPath . '/classes/sql/bp_' . $table . '_create.php';

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
            'beer_press_default_category'     => 'default-category',
            'beer_press_display_page'         => 'display-page',
            'beer_press_submit_page'          => 'submit-page',
            'beer_press_submit_location'      => 'submit-location',
            'beer_press_submit_title'         => 'submit-title',
            'beer_press_required_fields'      => 'required-fields',
            'beer_press_custom_css'           => 'custom-css',
            'beer_press_require_login'        => 'require_login',
            'beer_press_widget_target'        => 'widget-target',
            'beer_press_widget_items'         => 'widget-items',
            'beer_press_force_recaptcha'      => 'force-recaptcha',
            'beer_press_recaptcha_public'     => 'recaptcha-public',
            'beer_press_recaptcha_private'    => 'recaptcha-private'
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
