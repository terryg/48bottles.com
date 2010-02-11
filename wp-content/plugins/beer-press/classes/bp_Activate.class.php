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
        error_log("bp_Activate::activate");

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
