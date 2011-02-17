<?php
/*
Template Name: Sitemap
*/
?>
<?php get_header() ?>

			<div id="content" class="archive-index" role="main">

<?php the_post() ?>

				<div id="post-<?php the_ID(); ?>" class="hfeed">
					<h1 class="archive-title"><?php the_title(); ?></h1>
					<div class="entry-content">
<?php the_content() ?>

					<ul id="sitemap-list">
						<li id="all-pages">
							<h3><?php _e('All Pages', 'simplish') ?></h3>
							<ul>
<?php
	$args = array(
			'echo' => true,
			'link_before' => '',
			'link_after' => '',
			'menu_class' => 'pagenav',
			'show_home' => '1',
			'sort_column' => 'post_title',
			);
	wp_page_menu($args);
?>
							</ul>
						</li>
						<li id="all-posts">
							<h3><?php _e('All Posts', 'simplish') ?></h3>
							<ul>
<?php $post_archives = new wp_query('showposts=1000'); 
while ( $post_archives->have_posts() ) : $post_archives->the_post(); ?>
								<li <?php post_class(); ?>>
									<span class="entry-title"><a href="<?php the_permalink() ?>" title="<?php printf(__( 'Permalink to %s', 'simplish' ), esc_html( get_the_title() ) ) ?>" rel="bookmark"><?php the_title(); ?></a></span>
								</li>
<?php endwhile; ?>
							</ul>
						</li>
						<li id="monthly-archives">
							<h3><?php _e('Archives by Month', 'simplish') ?></h3>
							<ul>
<?php wp_get_archives('type=monthly&show_post_count=1') ?>
							</ul>
						</li>
						<li id="category-archives">
							<h3><?php _e('Archives by Category', 'simplish') ?></h3>
							<ul>
<?php wp_list_categories('optioncount=1&title_li=&show_count=1') ?>
							</ul>
						</li>
						<li>
							<h3><?php _e('Archives by Tag', 'simplish') ?></h3>
							<p><?php wp_tag_cloud() ?></p>
						</li>
					</ul>

<?php wp_link_pages('<div class="page-link">'.__('Pages: ', 'simplish'), '</div>', 'number'); ?>

					</div>
				</div>
			</div>
<?php get_sidebar() ?>
<?php get_footer() ?>
