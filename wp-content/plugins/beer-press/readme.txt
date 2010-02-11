=== Beer Press ===
Contributors: TerryLorber
Tags: recipes, cooking, food, recipe share
Requires at least: 2.8
Tested up to: 2.9.1
Stable tag: trunk

Turn your Wordpress site into a full fledged recipe sharing system with user contributions.

== Description ==

Turn your Wordpress site into a full fledged recipe sharing system. Allow users to submit recipes, organize recipes in hierarchal categories, make comments, and embed recipes in posts and pages.

<strong>Notice</strong>: Version 0.8.3 introduces preliminary image support using the Media Library built into Wordpress. I am aware that this this feature does not work in Wordpress MU and I am working on a fix for this.

Features include:

* Recipe Categories
* Pretty Permalinks
* User Submission Form with optional reCaptcha validation
* User Comments
* Recipe photo using the Media Library
* Set roles for each Recipe Press menu.
* Supports multiple recipe templates
* Imports RecipeML format XML files, including zip files with up to 100 recipes.

Upcoming Features:

* Tagging
* Multiple category templates

Requirements

* Must be run on servers with PHP5

== Installation ==

1. Upload `recipe-press` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure the plugin on the Recipes menu screen.
4. Visit http://wordpress.grandslambert.com/plugins/recipe-press.html for instructions to display recipes.

== Changelog ==

0.9.8 - February 5th, 2010

* Fixed a bug that caused multiple errors when adding new recipes.
* Fixed a bug where if a title was not entered, all recipe data was lost on add form.
* Fixed a bug that was adding extra slashes to the ingredients fields.
* Fixed the permalinks to work with most permalink settings, I think!
* Added the notes field when adding an ingredient on the public form.
* Added pagination on the recipes, categories, comments, and ingrdients pages to display only 10 (configurable in settings) results per page on the admin side.
* Added an updated version of the Portuguese language translation.
* Added an early version of a French Translation.
* Changed the "delete" action to a "trash" action in recipes, ingredients, and comments.
* Changed the public form to be fully templateable.
* Added an option to select how recipe ingredients are input on the pubic form, plain text or individual ingredients. Set this in the Settings page.
* Counted how many hairs I pulled out chasing down the stupid permalinks structure - I am going bald! :)

0.9.7 - February 3rd, 2010

* Fixed a bug where ingredients with "no size" displayed an "s" from the pluralization function.
* Fixed a bug where the "show_count" feature in the category shortcode would not count recipes from other users.
* Fixed a bug where the slugify function would throw an error when creating slugs for new recipes as they have no ID.
* Fixed issues where list items in widgets where not provided with class names.
* Added a published and updated date field to the database to use in widget - please deactivate and activate the plugin after update.
* Added sort options for the category widget to sort by name or a random order. Need suggestions for other options.
* Added the ability to include the icon in the category and recipe widgets with default settings added to settings page.

0.9.6 - January 30th, 2010

* Fixed an issue where ingredients fields could only be edited with arrow keys.
* Added an option for the [recipe-list] shortcode to use list templates for better display of recipes.
* Added an inflector helper to convert singular and pluaral words, used in ingredients.
* Added an importer for RecipeML format XML files.

0.9.5 - January 28th, 2010

* Fixed the recipe image function to support the size of the image - send as array(width,height) in the template tag.
* Fixed an issue where the recipe instructions were not saving from the front end form.
* Fixed an issue where blank ingredients lines were added and causing errors in database calls.
* Fixed an issue where the thank you page was not appearing after submitting a recipe on the front end.
* Changed how the ingredient is linked, no longer links the size of the ingredient, just the name.
* Added a "No Size" option to allow for special instructions like "pinch of salt".
* Another attempt at fixing the permalinks when using default permalinks and using a static home page.
* Added a Portuguese translation.
* Updated the recipe-press.pot file for a lot of new text that can be translated. Translations needed!

0.9.4 - January 20th, 2010

* Added the option to show the most recently updated recipes in the list widget.
* Added a delete option for recipe rows.
* Added an option to turn off the ingredients header in the recipe display.
* Added a view link on the admin recipe list to quickly view the recipe on the site.
* Fixed the recipe form so newly added rows can be sorted before saving the recipe.
* Fixed the add ingredient and add divider options to create corrected rows on both admin and public side.
* Moved javascript delete functions to code so the confirm messages can be translated.

0.9.3 - January 19th, 2010

* Fixed a bug where recipe form javascript was not working and/or conflicting with other javascript.
* Added page links to ingedients on edit recipe form - link to any page within the site or create a new page with shortcode added.
* Added an ingredients table to store all ingredients in all recipes.
* Added shortcode [recipe-ingredients] to list all recipes containing an ingredient.
* Added an option to "feature" a recipe, to be used in sidebars and widgets.
* Added type and sort order to recipe list widget.
* Ingredients can point to an internal page or an external URL (to open in a new window).
* Added a notes field for ingredients to add extra detail, such as size of package or preparation method.
* Changed the add and edit recipe so when saving a recipe the edit screen for that recipe is displayed after saving.

0.9.2 - January 13th, 2010

* Fixed a bug where the depricated bulletize function was still called on instructions.
* Fixed a bug where the plugin removed the media upload functions from all areas of Wordpress.
* Fixed the update to add the ingredient-size to the options table.
* Added images to categories for use in future category templates in next version.

0.9.1 - January 12th, 2010

* Moved the WYSIWYG editor from the notes to instructions fields.
* Removed bulletize options as it is no longer needed.
* Fixed a bug in the ingredient conversion script that caused some items to revert to a divider.
* Fixed an HTML bug in the admin recipe form causing bold print where it was not supposed to be.
* Fixed the WYSIWYG editor HTML option to work properly.
* Added a delete button for ingredients.
* Added support for language translations.

0.9 - January 11th, 2010

* Fixed an issue that prevented the recipe list widget from displaying recipes when user is logged out.
* Fixed an issue where HTML was not rendered properly in the Notes, Ingredients, and Instructions fields.
* Fixed an issue where site templates were not generating the correct URLs to CSS files.
* Recipe comments no longer shown when recipe is embedded into a post, only on the Recipe page.
* Added a feature to seperate ingredients into multiple lines - will convert old recipes but needs attention when editing old recipes.
* Added a feature to add dividers into indgredients so recipes can have multiple parts.
* Added drag and drop features into ingredients box.

0.8.4 - December 23rd, 2009

* Fixed an issue that prevented the public form from being checked for required fields.
* Fixed an issue where slugs were changed when editting a recipe or category.
* Fixed an issue where the public form was broken when reCaptcha wasn't displayed to logged in users.
* Fixed an issue where the image was deleted when saving the recipe after an image was added.
* Learned a lesson on double checking everything before committing such a big change.

0.8.3 - December 23rd, 2009

* Fixed an issue with recipes being entered twice on user submit.
* Fixed an issue where a page refresh on the thank you page entered the recipe again.
* Fixed an issue where comment authors were not always displayed.
* Fixed an issue where recipes/categories with identical names had the same slugs.
* <strong>Note</strong>: If you are using a custom thank you template, please update to use the template tags.
* Fixed an issue with category URLs being created incorrectly.
* Fixed the tabindex order on the recipe form.
* Set the default user to the active user on the Add Recipe form.
* Fixed more backslash (\) display issues in some fields.
* Display child categories on category view pages.
* Fixed an issue where the recipe contributor did not always display correctly.
* Added a new icon to the menu and pages to tie all screens together (was this really needed?)
* Added access levels to grant access to menus to any Worpdress role.
* Added the first of many recipe templates with a new template system.
* Added new settings for text to display for "hours" and "minutes".
* Added preliminary photo support using built in Wordpress Media Library (does not support Wordpress MU at this point).

0.8.2 - December 21st, 2009

* Fixed an issue where recipes did not display if Wordpress was installed in a subfolder.
* Fixed the permalinks when the [recipe-list] shortcode is used on the static homepage.
* Fixed additional slashes (\) before single and double quotes in titles and text.
* Fixed a bug where submitter name was overlooked on recipe display.
* Converted the entire recipe display template to use template tags. Please update your custom recipe template.
* Added options to bulletize ingredients list and instructions (on by default).
* Added checks to hide details that are not set (prep, cook, and ready times as well as servings).

0.8.1 - December 20th, 2009

* Fixed an issue where the [recipe-show] shortcode did not display any data.
* Fixed an issue where the recipe-comments table was not created on Wordpress MU.
* Fixed an issue where options were reset each time the plugin was reactivated.
* Added more template tags to the comments section, now all fields can be assigned or echoed.

0.8 - December 19th, 2009

* Replaced internal functions for page lists and user lists with Wordpress native functions.
* Added comments to recipes.
* <strong>NOTICE</strong>. If you are using a custom recipe template you will need to add &lt;?php $this->showComments(); ?&gt; on the bottom of your template.
* Added options for comments including a custom title for the comments form, forcing a user to log in, and option to show Gravatar.
* Rewrote the settings class to store all settings in one record in the database. Will convert older settings on activation.
* Added an option to remove all data when the plugin is deactivated.
* Fixed the page list and user list drop downs to use Wordpress native functions.
* Changed how recipes, categories, and comments are used in the plugin. Check your templates to make sure they use the correct functions.

0.7.9 - December 17th, 2009

* Fixed an issue where plugin settings could not be saved in Wordpress MU. Thank you Michael Crumm for a patch.
* Replaced a depricated function with an appropriate function, should remove errors displayed in IE8.

0.7.8 - December 15th, 2009

* Fixed an issue where the use of reCaptcha code conflicted with other plugins.
* Added a work-around so the plugin can co-exist with WP-reCAPTCHA, but WP-reCAPTCHA <em>must</em> be activated and set up first.
* Fixed some errors in the activiation scripts that were causing transient errors in the database updater.

0.7.7 - December 14th, 2009

* Fixed the links on the Plugins list page for the Overview and Settings pages.
* Added reCaptcha to the public recipe submit form. Requires reCaptcha keys for your domain, will not display unless keys are present.
* Added option to require registration and login OR add name and email address fields the to public form.
* Added required fields to the user submit form, configurable in the settings page.
* Added the class 'recipe-press-submit' to the table and classes to table rows to the public form to allow for styling.
* <em>Depricated</em>: Removed the [recipe-form] shorcode as it is no longer required. Form is automatically inserted into the page selected for user submissions.

0.7.6 - December 13th, 2009

* Fixed a small bug that created recipe links incorrectly for default type permalinks.
* Fixed a similar bug to correct the category links for default type permalinks.

0.7.5 - December 12th, 2009

* Fixed a bug that prevented new recipes from being added on the admin backend.

0.7.4 - December 11th, 2009

* Fixed an error on the edit category view preventing editting of categories.
* Fixed a bug preventing categories from being deleted.
* Fixed a bug where blank categories could be created.
* Fixed a bug where recipe links were not created correctly on some sites.
* Fixed a bug where invalid redirects where created when no display page was selected.
* Fixed a bug where blank recipes could be created.
* Fixed a bug where no default status on recipes was set.
* Added category description to category pages, if one is set.
* Added attributes 'show_count', 'count_pre' (prefix) and count_suf (suffix) to category shortcode.

0.7.3 - December 11th, 2009

* Fixed an error where categories could not be added or updated.
* Fixed a bug where recipe links do not work when listing page is a subpage.
* Fixed a bug where templates could not be added to theme folder.
* Added category hierarchy to all displays.
* Added functionality to auto-update database tables with each new update.
* Validated all admin links to use a WP Nonce field.

0.7.2 - December 10th, 2009

* Fixed a bug with jQuery actions affecting other parts of the Wordpress Admin.
* Added initial functionality for recipe comments - not complete at this release.
* Updated the recipe edit screen.
* Moved the Recipe Press Menu to the Objects menu under Comments to keep it above the fold.
* More code cleanup
* Added the Contributors page.
* Added a placholder for the tags.
* Many of these were to be released, but a major bug fix had to be released while updates were in the works.

0.7.1 - December 10th, 2009

* Fixed an issue with the auto load function which is not compatible with Wordpress.
* Fixed an issue with widgets not loading properly.
* Removed some debug code from the administration panels.
* Cleaned up the code and removed unused or duplicate functions.
* Added missing files to plugin.

0.7 - December 8th, 2009

* Changed the name from Recipe Share to Recipe Press
* Complete rewrite of code
* Fixed administrative links in Dashboard and other admin pages.
* Fixed the recipe submit page to use a template page.
* Added AJAX Autocomplete to recipes pages to quickly find and edit recipes.
* Added the category slug to the URL for displaying recipes.
* Added parent field for categories, hierarchy display coming soon.
* Added widget and shortcode to display recipe categories.

0.6.3 - November 23rd, 2009

* Fixed a bug in the permalinks when Wordpress is installed in a subdirectory.
* Fixed a bug in the receipe submit that displayed an error when recipes were submitted. Now returns a generic thank you unless a file named "recipe-thankyou.php" exists in the theme folder.

0.6.2 - October 28th, 2009

* Fixed a bug where table names were created with an incorrect table prefix. Now uses the prefix set by Wordpress.

0.6.1 - October 28th, 2009

* Fixed a bug where the shortcodes could only be used once per page.
* Fixed formatting for recipe-show to hide notes or servings when empty.

0.6 - October 24th, 2009

* Fixed the recipe links to use pretty permalinks if selected. When updating, select the page where the recipe list is in the settings.
* Added a user submit form short code [recipe-form]. Work is needed for the thank you page.
* Added a widget to display up to 20 recipes, can display from all or any one category. The widget is limited and needs more work.

0.5 - October 16th, 2009

* Fixed an issue where plugin was not compatible with PHP version 4.
* Changed the versioning style to advance to release sooner.

0.1.1Beta - October 15th, 2009

* Fixed an issue with permalinks.

0.1.0Beta - October 13th, 2009

* Early Beta release

== Upgrade Notice ==

= 0.9.5 =
Includes a fix for the public form to allow saving ingredients and instructions.

= 0.9 =
New method for storing recipes - please double check recipes when you edit to make sure the plugin converts them correctly.

= 0.8 =
This version converts old settings to a new format. Verify all settings after update.

== Frequently Asked Questions ==

= How do I add recipes to my posts or pages? =

There are two shortcodes for placing recipes on your site, recipe-list and recipe-show. Please visit http://wordpress.grandslambert.com/plugins/recipe-press.html for instructions on how to use these shortcodes.

= What widgets are available =

Currently there are two widgets, one to lest recipes and one to list categories. At this time they are limited to displaying a small set of items, but future development will expand the options for these widgets.

= How can people comment on recipes? =

The comment tool is coming in the very near future - please be patient! Currently I have the backend working and need to develop the front end screens and templates.

= How can I add a user submit form? =

To allow users to submit recipes you need to select which page to display the form on from the Recipe Press Settings page. You can choose to display the form either above or below any content on the page, or replace any existing content with just the form.

= What happened to the [recipe-submit] shortcode? =

This shortcode was in earlier versions so you could add the form. Version 0.7.7 removed the need for this and automatically adds the form to the page selected in the settings page. This change was required so we could accurately redirect to the login page when a login is required to submit recipes.

= Where can I get support? =

http://support.grandslambert.com/forum/recipe-press

== Screenshots ==

1. Overview Screen
2. Recipe List Screen
3. Add Recipe Form
4. Comments Management Screen
5. Categories Management Screen
6. Plugin Setting Screen
7. Recipe displayed on sample site.
