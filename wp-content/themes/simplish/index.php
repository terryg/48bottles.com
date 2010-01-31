<?php get_header(); ?>

		<div id="content">

<?php if(have_posts()): ?>

<?php while(have_posts()): the_post(); ?>
			<div id="article-<?php the_ID(); ?>" <?php post_class(); ?>>
<?php include(TEMPLATEPATH . '/hentryhead.php'); ?>
				<br class="clear" />
				<div class="entry-content">
					<?php the_content('<span class="readmore">'.__('More&hellip;', 'simplish').'</span>'); ?>
				</div>
<?php include(TEMPLATEPATH . '/hentrymeta.php'); ?>
			</div><!--#article-num .hentry-->

<?php endwhile; ?>

<?php include(TEMPLATEPATH . '/prevnextnav.php'); ?>

<?php else : ?>

<h2 class="center"><?php _e('Not Found', 'simplish'); ?></h2>
			<?php get_search_form(); ?>

<?php endif; ?>

		</div><!-- #content -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>
