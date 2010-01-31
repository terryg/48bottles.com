<?php
/*
Template Name: Links
*/
?>

<?php get_header(); ?>

<div id="content">

<h2 class="page-title"><?php the_title() ?></h2>

<ul>
<?php get_links_list(); ?>
</ul>

<?php edit_post_link(__('Edit&hellip;', 'simplish'),'<p class="admin-edit">&#91; ',' &#93;</p>') ?>

</div>	

<?php get_footer(); ?>
