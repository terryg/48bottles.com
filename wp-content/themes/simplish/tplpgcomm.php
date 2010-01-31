<?php
/*
Template Name: Page with Comments
*/
?>

<?php get_header(); ?>

		<div id="content" class="hfeed">

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

			<div id="article-<?php the_ID(); ?>" class="hentry"><!-- Do not use post_class() as it outputs undesired "page" class. -->
				<h2 class="page-title entry-title"><?php the_title(); ?></h2>
				<div class="entry-content">
					<?php the_content(); ?>
					<?php wp_link_pages('before=<p><strong>'.__('Pages:', 'simplish').'</strong>&after=</p>&next_or_number=number&pagelink=%'); ?>
				</div>
			</div>

			<?php endwhile; endif; ?>

			<?php edit_post_link(__('Edit&hellip;', 'simplish'),'<p class="admin-edit">&#91; ',' &#93;</p>') ?>

			<?php comments_template(); ?>

		</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
