<?php get_header(); ?>

		<div id="content" role="main">

<?php if(have_posts()): while(have_posts()): the_post(); ?>

			<div id="article-<?php the_ID(); ?>" <?php post_class(); ?>>
<?php get_template_part('hentryhead') ?>
				<br class="clear" />	
				<div class="entry-content">
					<?php the_content(); ?>
					<?php wp_link_pages( array( 'before' => '<div class="pgnum-link">' . __( 'Pages:', 'simplish' ), 'after' => '</div>' ) ); ?>
				</div><!--#entry-content-->
<?php edit_post_link(__('Edit&hellip;', 'simplish'),'<p class="admin-edit">&#91; ',' &#93;</p>') ?>

<?php get_template_part('hentrymeta') ?>
			</div><!--#hentry-->

		<?php comments_template(); ?>

<?php endwhile; ?>

			<div id="postnav" class="navigation">
				<div class="prevlink"><?php previous_post_link('&laquo; %link') ?></div>
				<div class="nextlink"><?php next_post_link('%link &raquo;') ?></div>
			</div>

<?php else: ?>

			<p>Sorry, no posts matched your criteria.</p>
	
<?php endif; ?>

		</div><!-- #content -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>
