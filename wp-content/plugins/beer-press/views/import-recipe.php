<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }
/**
 * recipes.php - View for the Recipes page.
 *
 * @package Recipe Press
 * @subpackage views
 * @author GrandSlambert
 * @copyright 2009-2010
 * @access public
 */
?>

<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;" class="overDiv"></div>
<div class="wrap">
    <form method="post" action="<?php $this->adminURL(rp_Recipes::menuName); ?>" id="recipe_press_import" enctype="multipart/form-data">
        <input type="hidden" name="action" value="import-action"/>
        <div class="icon32" id="icon-recipe-press"><br/></div>
        <h2><?php echo $this->pluginName; ?> &raquo; <?php _e('Import Recipe', 'recipe-press'); ?> </h2>
        <div style="width:49%; float:left">
            <div class="postbox">
                <h3 class="handl" style="margin:0;padding:3px;cursor:default;">
                    <?php _e('Display Settings', 'recipe-press'); ?>
                </h3>
                <div class="table">
                    <table class="form-table">
                        <tbody>
                            <tr align="top">
                                <th scope="row"><label for="recipe_press_display_page"><?php _e('Recipe List Page', 'recipe-press'); ?></label></th>
                                <td>
                                    <select name="import-type">
                                        <option value="recipeml"><?php _e('RecipeML XML File'); ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><label for="import-category"><?php _e('Default import category', 'recipe-press'); ?></label></th>
                                <td>
                                    <select name="import-category" id="import-category">
                                        <?php echo $this->listOptions('categories', $this->options['default-category']); ?>
                                    </select>
                                    <?php $this->help(__('The importer will check if the recipe fits into an existing category, otherwise recipe will be added to this category.', 'recipe-press')); ?>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><label for="import-file"><?php _e('File to import', 'recipe-press'); ?></label></th>
                                <td>
                                    <input type="file" name="import-file" value="" />
                                    <?php $this->help(__('Choose either a single file or a zip containing a collection of files of the same type..', 'recipe-press')); ?>

                                </td>
                            </tr>
                            <tr align="top">
                                <td colspan="2" align="center">
                                    <p class="submit">
                                        <input type="submit" value="<?php _e('Import Recipe'); ?>" name="submit" class="button-primary"/>
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>


                </div>
            </div>
        </div>
        <div style="width:49%; float:right">
            <div class="postbox">
                <h3 class="handl" style="margin:0;padding:3px;cursor:default;">
                    <?php _e('Instructions', 'recipe-press'); ?>
                </h3>
                <div class="table">Coming soon...</div>
            </div>
        </div>

    </form>
    <?php include "footer.php"; ?>
</div>
