<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

/**
 * bp_Add_Recipe.class.php - Class for adding recipes on the back end.
 *
 * @package Recipe Press
 * @subpackage classes
 * @author GrandSlambert
 * @copyright 2009-2010
 * @access public
 */

class bp_Add_Recipe extends bp_Recipe_Base {
    /* Set Variables */
    const menuName = 'recipe-press-add-recipe';
    protected $view = 'add-recipe';

    /**
     * Method to display the Add Recipe form and save form submissions.
     *
     * @global object $wpdb
     */
    function subPanel() {
        global $wpdb, $current_user;
        get_currentuserinfo();

        switch ($_REQUEST['form-action']) {

            case 'add':
                $data = $this->input();
                if ($data['title'] == '') {
                    /* translators: This is the error message when the recipe title field is left blank in the form. */
                    $this->message = __('The Recipe Name field was left blank, could not create a blank recipe', 'recipe-press');
                    $this->recipe = $this->input(true);
                }
                else {
                    $data['added'] = $this->now();
                    $results = $wpdb->insert( $this->tables['recipes'], $data);

                    if ($results) {
                        /* translators: Recipe added on the back end, argument is the recipe title. */
                        $this->message = sprintf(__('"%s" recipe successfully added.', 'recipe-press'), $_POST['title']);
                        $this->recipe = $this->getOneRecord('recipes', $wpdb->insert_id);
                        $this->view = 'edit_recipe';
                    } else {
                        /* translators: General error when trying to save a recipe */
                        $this->message = __('There was an error trying to save the recipe. Perhaps you forgot to make any changes. Try again later, OK?', 'recipe-press');
                    }
                }
                break;
        }

        if (!$this->recipe) {
            $this->recipe->comments_open = true;
            $this->recipe->hide_ingredients_header = false;
            $this->recipe->ingredients = array();

            for ($ctr=0; $ctr < $this->options['ingredients-fields']; ++$ctr) {
                if (is_array($this->recipe->ingredients) ) {
                    array_push($this->recipe->ingredients, array('size'=>'none'));
                } else {
                    $this->recipe->ingredients[] = array('size'=>'none');
                }
            }
        }

        include($this->viewsPath . $this->view . '.php');
    }
}
