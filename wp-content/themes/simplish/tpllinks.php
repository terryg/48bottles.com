<?php
/*
Template Name: Links
*/
?>

<?php get_header(); ?>

<div id="content" role="main">

<h2 class="page-title"><?php the_title() ?></h2>

<ul>
<?php wp_list_bookmarks(); ?>
</ul>

<?php edit_post_link(__('Edit&hellip;', 'simplish'),'<p class="admin-edit">&#91; ',' &#93;</p>') ?>

</div>	

<?php get_footer(); ?>
