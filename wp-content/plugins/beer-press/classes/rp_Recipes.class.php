<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

/**
 * rp_Recipes.classes.php - Class for recipes management
 *
 * @package Recipe Press
 * @subpackage classes
 * @author GrandSlambert
 * @copyright 2009-2010
 * @access public
 */

class rp_Recipes extends rp_Recipe_Base {
    /* Set Variables */
    const menuName = 'recipe-press-recipes';
    protected $view = 'recipes';

    /**
     * Displays the subpanel and processes user actions.
     *
     * @global object $wpdb
     */
    function subPanel() {
        global $wpdb, $current_user;
        get_currentuserinfo();

        switch ($_REQUEST['action']) {
            case 'import':
                $this->view = 'import-recipe';
                break;
            case 'import-action':
                $recipes = rp_importers::recipeml($_FILES['import-file'], $_POST['import-category'], $this->tables['categories']);

                foreach ($recipes as $recipe) {
                    $recipe['user_id'] = $current_user->ID;
                    $recipe['template'] = 'standard';
                    $recipe['slug'] = $this->slugify($recipe['title'], $recipe['title']);
                    $recipe['status'] = 'pending';
                    $recipe['comments_open'] = true;
                    if ($this->insertRecipe($recipe)) {
                        ++$inserted;
                    }
                }

                $this->message = sprintf(__('%d recipes successfully imported.', 'recipe-press'), $inserted);

                break;
            case 'trash':
                $recipe = $this->getRecipeFromSlug($_GET['id'], false);
                /* translators: Message when recipe is deleted - argument is the recipe title. */
                $this->message = sprintf(__('"%s" recipe successfully trashed.', 'recipe-press'), $recipe->title);
                $wpdb->update($this->tables['recipes'], array('status'=>'trash'), array('id'=>$recipe->id));
                break;
            case 'update':
                $data = $this->input();
                $recipe = $this->getOneRecord('recipes', $data['id']);

                if ($data['status'] == 'active' and !$recipe->published) {
                    $data['published'] = $this->now();
                }

                $results = $wpdb->update( $this->tables['recipes'], $data, array('id'=>$_POST['id']) );
                $this->updateCommentCounts();
                $this->updateIngredients();
                /* translators: Message when recipe is updated - argument is the recipe title. */
                $this->message = sprintf(__('"%s" recipe successfully updated.', 'recipe-press'), $_POST['title']);
                $this->recipe = $this->getOneRecord('recipes', $_POST['id']);
                $this->view = 'edit_recipe';
                break;
            case 'edit':
                if ($this->superUser()) {
                    $this->recipe = $this->getOneRecord('recipes', $_GET['id']);
                }
                else {
                    $this->recipe = $this->getOneRecord('recipes', $_GET['id'], array('user_id'=>$current_user->ID));
                }

                if ($this->recipe->id > 0) {
                    $this->view ='edit_recipe';
                } else {
                    /* translators: Message whenuser does not have permission to edit a recipe. */
                    $this->message = __('Sorry, you do not have permission to edit that recipe.', 'recipe-press');
                }

                break;
        }

        include($this->viewsPath . $this->view . '.php');
    }
}