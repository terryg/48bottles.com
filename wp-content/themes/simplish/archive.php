<?php get_header(); ?>

	<div id="content" role="main">

<?php the_post() ?>
<?php if(is_day()): ?>
				<h1 class="archive-title"><?php _e('Day:', 'simplish') ?> <span class="archive-subtitle"><?php the_time(__('l, F j\, Y', 'simplish')) ?></span></h1>
<?php elseif(is_month()): ?>
				<h1 class="archive-title"><?php _e('Month:', 'simplish') ?> <span class="archive-subtitle"><?php the_time(__('F Y', 'simplish')) ?></span></h1>
<?php elseif(is_year()): ?>
				<h1 class="archive-title"><?php _e('Year:', 'simplish') ?> <span class="archive-subtitle"><?php the_time(__('Y', 'simplish')) ?></span></h1>
<?php elseif(is_author()): ?>
				<h1 class="archive-title"><?php _e('Author:', 'simplish') ?></h1>
				<div class="archive-meta"><?php sp_author_hcard('64') ?></div>
				<br class="clear downpad" />
<?php elseif(is_category()): ?>
				<h1 class="archive-title"><?php _e('Category:', 'simplish') ?> <span class="archive-subtitle"><?php echo single_cat_title(); ?></span></h1>
<?php elseif(is_tag()): ?>
				<h1 class="archive-title"><?php _e('Tag:', 'simplish') ?> <span class="archive-subtitle"><?php single_tag_title(); ?></span></h1>
<?php elseif(isset($_GET['paged']) && !empty($_GET['paged'])): ?>
				<h1 class="archive-title"><?php _e('Archives', 'simplish') ?> <?php printf(__('%1$s Archives', 'simplish'), esc_html(get_the_title())) ?></h1>
<?php endif; ?>
<?php rewind_posts() ?>

<?php if(have_posts()): ?>
	<?php while(have_posts()): the_post(); ?>

		<div id="article-<?php the_ID(); ?>" <?php post_class(); ?>>
<?php get_template_part('hentryhead') ?>
			<br class="clear" />	
			<div class="entry-content">
				<?php the_content(sp_readmore_text()); ?>
			</div>
<?php get_template_part('hentrymeta') ?>
		</div><!--#hentry-->

	<?php endwhile; ?>

<?php get_template_part('prevnextnav') ?>
	
<?php else: ?>

		<h2 class="center"><?php _e('Not Found', 'simplish'); ?></h2>
		<?php get_search_form(); ?>

<?php endif; ?>
		
	</div><!-- #content -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>
