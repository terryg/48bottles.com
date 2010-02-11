<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }
/**
 * rp_recipes_create.php - Create the rp_recipes table.
 *
 * @package Recipe Press
 * @subpackage classes
 * @subpackage sql
 * @author GrandSlambert
 * @copyright 2009-2010
 * @access public
 */

/* Field Structure */
$fields = array(
    'id'            => "`id` bigint(11) NOT NULL AUTO_INCREMENT",
    'user_id'       => "`user_id` bigint(11) unsigned NOT NULL",
    'media_id'      => "  `media_id` bigint(11) NOT NULL DEFAULT '0'",
    'category'      => "`category` bigint(11) unsigned NOT NULL DEFAULT '1'",
    'template'      => "`template` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'standard'",
    'title'         => "`title` tinytext COLLATE utf8_unicode_ci NOT NULL",
    'slug'          => "`slug` text COLLATE utf8_unicode_ci NOT NULL",
    'prep_time'     => "`prep_time` bigint(11) unsigned NOT NULL",
    'cook_time'     => "`cook_time` bigint(11) unsigned NOT NULL",
    'ready_time'    => "`ready_time` tinytext COLLATE utf8_unicode_ci NOT NULL",
    'notes'         => "`notes` text COLLATE utf8_unicode_ci NOT NULL",
    'hide_ingredients_header' => "`hide_ingredients_header` INT NOT NULL DEFAULT '1'",
    'ingredients'   => "`ingredients` longtext COLLATE utf8_unicode_ci NOT NULL",
    'instructions'  => "`instructions` longtext COLLATE utf8_unicode_ci NOT NULL",
    'servings'      => "`servings` bigint(11) NOT NULL",
    'servings_size' => "`servings_size` tinytext COLLATE utf8_unicode_ci NOT NULL",
    'views_total'   => "`views_total` bigint(11) unsigned NOT NULL",
    'comment_total' => "`comment_total` bigint(11) NOT NULL",
    'status'        => "`status` VARCHAR( 16 ) NOT NULL DEFAULT 'active'",
    'featured'      => "`featured` INT( 1 ) NOT NULL DEFAULT '0'",
    'comments_open' => "`comments_open` INT( 1 ) NOT NULL DEFAULT '1'",
    'submitter'     => '`submitter` TEXT NOT NULL',
    'submitter_email'=> '`submitter_email` TEXT NOT NULL',
    'added'         => "`added` datetime NOT NULL",
    'published'     => "`published` datetime NOT NULL",
    'updated'       => "`updated` datetime NOT NULL",
    'modified'      => "`modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
);

/* Table Structure */
$tableSQL = "
    CREATE TABLE IF NOT EXISTS `" . $tableName. "` (
    `id` bigint(11) NOT NULL AUTO_INCREMENT,
    `user_id` bigint(11) unsigned NOT NULL,
    `media_id` bigint(11) NOT NULL DEFAULT '0',
    `category` bigint(11) unsigned NOT NULL DEFAULT '1',
    `template` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'standard',
    `title` tinytext COLLATE utf8_unicode_ci NOT NULL,
    `slug` text COLLATE utf8_unicode_ci NOT NULL,
    `prep_time` bigint(11) unsigned NOT NULL,
    `cook_time` bigint(11) unsigned NOT NULL,
    `ready_time` tinytext COLLATE utf8_unicode_ci NOT NULL,
    `notes` text COLLATE utf8_unicode_ci NOT NULL,
    `hide_ingredients_header` INT NOT NULL DEFAULT '1',
    `ingredients` longtext COLLATE utf8_unicode_ci NOT NULL,
    `instructions` longtext COLLATE utf8_unicode_ci NOT NULL,
    `servings` bigint(11) NOT NULL,
    `servings_size` tinytext COLLATE utf8_unicode_ci NOT NULL,
    `views_total` bigint(11) unsigned NOT NULL,
    `comment_total` bigint(11) NOT NULL,
    `status` tinytext COLLATE utf8_unicode_ci NOT NULL,
    `featured` INT( 1 ) NOT NULL DEFAULT '0',
    `comments_open` INT( 1 ) NOT NULL DEFAULT '1',
    `submitter` TEXT NOT NULL,
    `submitter_email` TEXT NOT NULL,
    `added` datetime NOT NULL,
    `published` datetime NOT NULL,
    `updated` datetime NOT NULL,
    `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=0 ;
";

/* Default Recipes */
$defaultSQL = <<<END
    INSERT INTO `$tableName` (`id`, `user_id`, `media_id`, `category`, `template`, `title`, `slug`, `prep_time`, `cook_time`, `ready_time`, `notes`, `ingredients`, `instructions`, `servings`, `servings_size`, `views_total`, `comment_total`, `status`, `comments_open`, `submitter`, `submitter_email`, `added`, `published`, `updated`) VALUES
    (1, 1, 0, 3, 'standard', 'Sugar Cookies', 'sugar-cookies', 15, 10, '25  min', 'Quick and easy sugar cookies! They are really good, plain or with candies in them. My friend uses chocolate mints on top, and they are great!', 'a:8:{i:0;a:4:{s:8:"quantity";s:6:"2 3/4 ";s:4:"size";s:3:"cup";s:4:"item";s:17:"all-purpose flour";s:9:"page-link";s:3:"178";}i:1;a:4:{s:8:"quantity";s:2:"1 ";s:4:"size";s:8:"teaspoon";s:4:"item";s:11:"baking soda";s:9:"page-link";s:0:"";}i:2;a:4:{s:8:"quantity";s:4:"1/2 ";s:4:"size";s:8:"teaspoon";s:4:"item";s:13:"baking powder";s:9:"page-link";s:0:"";}i:3;a:4:{s:8:"quantity";s:2:"1 ";s:4:"size";s:3:"cup";s:4:"item";s:16:"butter, softened";s:9:"page-link";s:3:"211";}i:4;a:4:{s:8:"quantity";s:6:"1 1/2 ";s:4:"size";s:3:"cup";s:4:"item";s:11:"white sugar";s:9:"page-link";s:0:"";}i:5;a:4:{s:8:"quantity";s:2:"1 ";s:4:"size";s:5:"whole";s:4:"item";s:3:"egg";s:9:"page-link";s:3:"179";}i:6;a:4:{s:8:"quantity";s:2:"1 ";s:4:"size";s:8:"teaspoon";s:4:"item";s:15:"vanilla extract";s:9:"page-link";s:0:"";}s:9:"page_link";a:3:{s:4:"size";s:7:"divider";s:4:"item";s:0:"";s:9:"page-link";s:0:"";}}', '<ol>\r\n	<li>Preheat oven to 375 degrees F (190 degrees C).</li>\r\n	<li>In a small bowl, stir together flour, baking soda, and baking powder. Set aside.</li>\r\n	<li>In a large bowl, cream together the butter and sugar until smooth. Beat in egg and vanilla.</li>\r\n	<li>Gradually blend in the dry ingredients. Roll rounded teaspoonfuls of dough into balls, and place onto ungreased cookie sheets.</li>\r\n	<li>Bake 8 to 10 minutes in the preheated oven, or until golden. Let stand on cookie sheet two minutes before removing to cool on wire racks.</li>\r\n</ol>', 4, 'dozen', 22, 0, 'active', 1, '', '', now(), now(), now()),
    (2, 1, 0, 3, 'standard', 'Zucchini Cake', 'zucchini-cake', 20, 35, '55  min', 'This is a new recipe that I love to make and take to family events. It has been to quite a few, and has even been made for others to take to potlucks. Don\'t let the name fool you, it doesn\'t even taste like Zucchini!', 'a:15:{i:0;a:4:{s:8:"quantity";s:6:"2 1/4 ";s:4:"size";s:3:"cup";s:4:"item";s:17:"all-purpose flour";s:9:"page-link";s:3:"178";}i:1;a:4:{s:8:"quantity";s:6:"2 1/4 ";s:4:"size";s:3:"cup";s:4:"item";s:11:"white sugar";s:9:"page-link";s:0:"";}i:2;a:4:{s:8:"quantity";s:4:"3/4 ";s:4:"size";s:8:"teaspoon";s:4:"item";s:8:"sea salt";s:9:"page-link";s:3:"200";}i:3;a:4:{s:8:"quantity";s:2:"1 ";s:4:"size";s:8:"teaspoon";s:4:"item";s:11:"baking soda";s:9:"page-link";s:0:"";}i:4;a:4:{s:8:"quantity";s:4:"3/4 ";s:4:"size";s:8:"teaspoon";s:4:"item";s:13:"baking powder";s:9:"page-link";s:0:"";}i:5;a:4:{s:8:"quantity";s:6:"1 3/4 ";s:4:"size";s:8:"teaspoon";s:4:"item";s:15:"ground cinnamon";s:9:"page-link";s:3:"209";}i:6;a:4:{s:8:"quantity";s:4:"3/4 ";s:4:"size";s:8:"teaspoon";s:4:"item";s:16:"vanilla extratct";s:9:"page-link";s:3:"210";}i:7;a:4:{s:8:"quantity";s:2:"3 ";s:4:"size";s:5:"whole";s:4:"item";s:4:"eggs";s:9:"page-link";s:3:"179";}i:8;a:4:{s:8:"quantity";s:4:"3/4 ";s:4:"size";s:3:"cup";s:4:"item";s:13:"vegetable oil";s:9:"page-link";s:3:"225";}i:9;a:4:{s:8:"quantity";s:6:"2 1/4 ";s:4:"size";s:3:"cup";s:4:"item";s:15:"grated zucchini";s:9:"page-link";s:3:"226";}i:10;a:3:{s:4:"size";s:7:"divider";s:4:"item";s:37:"Ingredients for Cream Cheese Frosting";s:9:"page-link";s:0:"";}i:11;a:4:{s:8:"quantity";s:1:"8";s:4:"size";s:5:"ounce";s:4:"item";s:12:"cream cheese";s:9:"page-link";s:3:"207";}i:12;a:4:{s:8:"quantity";s:4:"1/2 ";s:4:"size";s:3:"cup";s:4:"item";s:6:"butter";s:9:"page-link";s:3:"211";}i:13;a:4:{s:8:"quantity";s:2:"2 ";s:4:"size";s:3:"cup";s:4:"item";s:19:"confectioners sugar";s:9:"page-link";s:0:"";}i:14;a:4:{s:8:"quantity";s:2:"2 ";s:4:"size";s:8:"teaspoon";s:4:"item";s:15:"vanilla extract";s:9:"page-link";s:3:"210";}}', '<ol>\r\n	<li>Preheat oven to 325 degrees F. Lightly grease and flour a 9 x 11 cake pan.</li>\r\n	<li>In a medium sized bowl, combine the flour, baking soda, baking powder, cinnamon, white sugar and salt. Mix well.</li>\r\n	<li>In another bowl, beat the eggs, vegetable oil and 3/4 teaspoon vanilla extract together.</li>\r\n	<li>Pour the egg mixture into the flour mixture and mix well.</li>\r\n	<li>Stir in shredded zucchini and mix well.</li>\r\n	<li>Pour batter into prepared pan.</li>\r\n	<li>Bake at 325 degrees F for 35-40 minutes until a toothpick inserted into the center of the cake comes out clean. Allow cakes to cool in pans.</li>\r\n</ol>\r\n<h3>To make frosting:</h3>\r\n<ol>\r\n	<li>Cream together the cream cheese and butter.</li>\r\n	<li>Add the confectioners sugar a little at a time, mixing well.</li>\r\n	<li>Add 2 teaspoons vanilla and spread on your cake.</li>\r\n</ol>', 24, 'pieces', 6, 0, 'active', 1, '', '', now(), now(), now()),
    (3, 1, 0, 1, 'standard', 'Kielbasa and Linguine', 'kielbasa-and-linguine', 15, 30, '45  min', 'This is a quick and simple recipe that can be made with very few ingredients.', 'a:9:{i:0;a:5:{s:8:"quantity";s:2:"1 ";s:4:"size";s:5:"whole";s:4:"item";s:8:"Kielbasa";s:5:"notes";s:0:"";s:9:"page-link";s:3:"176";}i:1;a:5:{s:8:"quantity";s:2:"1 ";s:4:"size";s:10:"tablespoon";s:4:"item";s:13:"Sunflower Oil";s:5:"notes";s:0:"";s:9:"page-link";s:3:"218";}i:2;a:5:{s:8:"quantity";s:2:"1 ";s:4:"size";s:3:"can";s:4:"item";s:22:"Cream of Mushroom Soup";s:5:"notes";s:12:"8 ounce can.";s:9:"page-link";s:3:"224";}i:3;a:5:{s:8:"quantity";s:2:"1 ";s:4:"size";s:8:"teaspoon";s:4:"item";s:13:"Garlic Powder";s:5:"notes";s:0:"";s:9:"page-link";s:3:"199";}i:4;a:5:{s:8:"quantity";s:2:"1 ";s:4:"size";s:8:"teaspoon";s:4:"item";s:8:"Sea Salt";s:5:"notes";s:0:"";s:9:"page-link";s:3:"200";}i:5;a:5:{s:8:"quantity";s:4:"1/2 ";s:4:"size";s:8:"teaspoon";s:4:"item";s:12:"Black Pepper";s:5:"notes";s:0:"";s:9:"page-link";s:3:"201";}i:8;a:2:{s:4:"size";s:7:"divider";s:4:"item";s:18:"Server over either";}i:6;a:5:{s:8:"quantity";s:1:"1";s:4:"size";s:3:"bag";s:4:"item";s:16:"Linguine Noodles";s:5:"notes";s:15:"Cooked al-dente";s:9:"page-link";s:3:"177";}i:9;a:5:{s:8:"quantity";s:1:"2";s:4:"size";s:3:"cup";s:4:"item";s:10:"White Rice";s:5:"notes";s:13:"Cooked sticky";s:9:"page-link";s:3:"234";}}', '<ol>\r\n	<li>Chop the kielbasa into small pieces. I like to cut slices about 1/2 inch thick then cut each slice into 4 pieces.</li>\r\n	<li>Fry the kielbasa in the cooking oil for at least 5 minutes.</li>\r\n	<li>Add the cream of mushroom soup and the spices. Bring to a boil then simmer on low heat for 30 minutes.</li>\r\n	<li>Serve over noodles or rice prepared per package instructions.</li>\r\n</ol>', 6, 'servings', 12, 0, 'active', 1, '', '', now(), now(), now()),
    (4, 1, 0, 1, 'right-image', 'Breakfast Bake', 'breakfast-bake', 20, 30, '50  min', 'This is a wonderful way to start the day and can be customized with peppers or onion for a bit more flavor.', 'a:10:{i:0;a:5:{s:8:"quantity";s:4:"1/2 ";s:4:"size";s:5:"pound";s:4:"item";s:12:"pork sausage";s:5:"notes";s:0:"";s:9:"page-link";s:3:"187";}i:1;a:5:{s:8:"quantity";s:4:"1/2 ";s:4:"size";s:5:"pound";s:4:"item";s:5:"bacon";s:5:"notes";s:33:"apple smoked adds a lot of flavor";s:9:"page-link";s:3:"188";}i:2;a:5:{s:8:"quantity";s:4:"1/4 ";s:4:"size";s:3:"cup";s:4:"item";s:5:"onion";s:5:"notes";s:0:"";s:9:"page-link";s:0:"";}i:3;a:5:{s:8:"quantity";s:2:"1 ";s:4:"size";s:3:"cup";s:4:"item";s:11:"hash browns";s:5:"notes";s:0:"";s:9:"page-link";s:0:"";}i:4;a:5:{s:8:"quantity";s:2:"3 ";s:4:"size";s:5:"whole";s:4:"item";s:4:"eggs";s:5:"notes";s:0:"";s:9:"page-link";s:3:"179";}i:5;a:5:{s:8:"quantity";s:4:"1/2 ";s:4:"size";s:3:"cup";s:4:"item";s:4:"milk";s:5:"notes";s:0:"";s:9:"page-link";s:3:"189";}i:6;a:5:{s:8:"quantity";s:4:"1/2 ";s:4:"size";s:8:"teaspoon";s:4:"item";s:4:"sage";s:5:"notes";s:0:"";s:9:"page-link";s:3:"203";}i:7;a:5:{s:8:"quantity";s:4:"1/2 ";s:4:"size";s:8:"teaspoon";s:4:"item";s:11:"dry mustard";s:5:"notes";s:0:"";s:9:"page-link";s:3:"204";}i:8;a:5:{s:8:"quantity";s:4:"1/4 ";s:4:"size";s:8:"teaspoon";s:4:"item";s:6:"garlic";s:5:"notes";s:0:"";s:9:"page-link";s:3:"199";}i:9;a:5:{s:8:"quantity";s:2:"1 ";s:4:"size";s:3:"cup";s:4:"item";s:14:"cheddar cheese";s:5:"notes";s:15:"Finely shredded";s:9:"page-link";s:3:"205";}}', '<ol>\r\n	<li>Cut bacon into small pieces and brown with the pork sausage and onion.</li>\r\n	<li>Layer 1/2 the hash browns, meat mixture, and cheese, in a 9x4 bread pan (they work great for small sized casseroles) and then the second layer of hash browns and meat mixture. (You may also use a 9x9 pan but you will only have one layer of each then and hold back the cheese layer.)</li>\r\n	<li>Mix eggs, milk, and spices and pour over the top of the rest. These is where you can refrigerate if you wish. (Since I am not sure how well egg freezes, you may want to freeze after the prior step and add the egg mixture when thawing or just before baking.).</li>\r\n	<li>Bake at 400F for 20 minutes, add the remaining cheese and bake an additional 10 minutes. It may also be baked at 350F for 30 minutes add the cheese and back an additional 15 minutes. A knife inserted should come out clean.</li>\r\n</ol>', 2, 'cups', 2, 0, 'active', 1, '', '', now(), now(), now()),
    (5, 1, 0, 4, 'standard', 'Warm Cheesy Salsa Dip', 'warm-cheesy-salsa-dip', 0, 120, '2  hour, 0  min', 'The cream cheese gives this a bit of a sweet tangy taste you don\'t have in most salsa dips. Pick a salsa you like since that is where most of the flavor comes from. If you like a hotter taste you can always add additional hot sauce. I recommend using a crock pot to keep it warm while serving.', 'a:3:{i:0;a:5:{s:8:"quantity";s:1:"8";s:4:"size";s:5:"ounce";s:4:"item";s:12:"cream cheese";s:5:"notes";s:0:"";s:9:"page-link";s:3:"207";}i:2;a:5:{s:8:"quantity";s:1:"8";s:4:"size";s:5:"ounce";s:4:"item";s:15:"Velveeta cheese";s:5:"notes";s:0:"";s:9:"page-link";s:0:"";}i:3;a:5:{s:8:"quantity";s:1:"8";s:4:"size";s:5:"ounce";s:4:"item";s:5:"salsa";s:5:"notes";s:0:"";s:9:"page-link";s:0:"";}}', '<ol>\r\n	<li>Melt cream cheese and Velveeta. Add salsa.</li>\r\n	<li>If using a slow cooker, it is going to take much longer to melt, so plan at least 2 hours for mixing.</li>\r\n</ol>', 3, 'cups', 1, 0, 'active', 1, '', '', now(), now(), now());
END;
