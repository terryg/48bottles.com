<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

/**
 * bp_importers.php - helper file for changing inflection of text.
 *
 * @package Beer Press
 * @subpackage helpers
 * @author TerryLorber
 * @copyright 2010
 * @access public
 */

class bp_importers {

    public static function recipeml($file, $category, $table) {

        if ($file['type'] == 'application/zip') {
            $allRecipes = array();
            $zip = zip_open($file['tmp_name']);

            while ($infile = zip_read($zip)) {
                $data = zip_entry_read($infile, zip_entry_filesize($infile));
                $data = simplexml_load_string($data);
                $recipes = self::recipemlRead($data, $category, $table);
                $allRecipes = array_merge($allRecipes, $recipes);
            }

            zip_close($zip);
        } else {
            $data = simplexml_load_file($file['tmp_name']);
            $allRecipes = self::recipemlRead($data, $category, $table);
        }

        return $allRecipes;
    }

    protected function recipemlRead ($data, $category, $table) {
        global $wpdb;

        $allRecipes = array();

        foreach ($data->recipe as $recipe) {
            unset($newRecipe);

            $newRecipe['title'] = (string)$recipe->head->title;
            $newRecipe['servings'] = (int)$recipe->head->yield;
            $newRecipe['category'] = $category;
            $newRecipe['instructions'] = (string)$recipe->directions->step;

            /* Check to see if recipe fits into an existing category */
            foreach ($recipe->head->categories as $category) {
                $slug = rp_Inflector::underscore($category->cat);

                if ($foundCategory = $wpdb->get_var('select `id` from `' . $table . '` where `slug` = "' . $slug . '"')) {
                    $newRecipe['category'] = $foundCategory;
                }
            }

            $newIngredients = array();

            /* Method for getting ingredients when there are no division */
            foreach ($recipe->ingredients->ing as $ingredient) {
                $newIngredient['quantity'] = (string)$ingredient->amt->qty;
                $size = rp_Inflector::singular( (string)$ingredient->amt->unit );
                $newIngredient['size'] = ($size) ? $size : 'none';
                $newIngredient['item'] = (string)$ingredient->item;

                array_push($newIngredients, $newIngredient);
            }

            /* Method for getting ingredients from division */
            foreach ($recipe->ingredients->{ing-div} as $division) {
                if ($title = (string) $division->title) {
                    array_push($newIngredients, array('size'=>'divider', 'item'=>$title));
                }

                foreach ($division->ing as $ingredient) {
                    $newIngredient['quantity'] = (string)$ingredient->amt->qty;
                    $size = rp_Inflector::singular( (string)$ingredient->amt->unit );
                    $newIngredient['size'] = ($size) ? $size : 'none';
                    $newIngredient['item'] = (string)$ingredient->item;

                    array_push($newIngredients, $newIngredient);
                }
            }

            $newRecipe['ingredients'] = serialize($newIngredients);
            array_push($allRecipes, $newRecipe);
        }

        return $allRecipes;
    }
}