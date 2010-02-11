<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

/**
 * rp_Deactivate.class.php - Class for plugin de-activation
 *
 * @package Recipe Press
 * @subpackage classes
 * @author GrandSlambert
 * @copyright 2009-2010
 * @access public
 */

class rp_Deactivate extends rp_Base {

    /**
     * Deactivation function - creates required databases.
     */
    public function deactivate() {
        global $wpdb;

        if ($this->options['delete-data']) {
            foreach ($this->tables as $table) {
                $wpdb->query('drop table `'. $table . '`');
            }

            delete_option($this->optionsName);
        }

        return true;
    }
} /* End of class declaration */