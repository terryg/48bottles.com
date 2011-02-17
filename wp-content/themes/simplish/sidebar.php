		<div id="sidebar" role="complementary">
			<ul>
			<?php if (!dynamic_sidebar('widget-right-sidebar')): //If no widget sidebar, output default sidebar. ?>

				<li id="search" class="search">
				    <?php the_widget('WP_Widget_Search', 'title='); ?>
				</li>

				<li role="navigation"><h2 class="sidebar-title"><?php _e('Navigation', 'simplish') ?></h2>
					<?php /* wp_nav_menu falls back to wp_page_menu if user has no menu setup/assigned. */
					wp_nav_menu( array(
									 'sort_column' => 'menu_order',
									 'container_class' => 'pagenav',
									 'theme_location' => 'nowidget-right-sidebar'
									 )
								);
					?>
				</li>

				<li id="categories">
					<?php the_widget('WP_Widget_Categories', 'count=24&dropdown=0&hierarchical=1'); ?>
				</li>

				<li id="archives">
					<?php the_widget('WP_Widget_Archives', 'count=24&dropdown=0'); ?>
				</li>

				<?php wp_list_bookmarks(); ?>

				<li id="meta">
					<?php the_widget('WP_Widget_Meta') ?>
				</li>

				<?php wp_meta(); ?>

			<?php endif; ?>
			</ul>
		</div><!-- #sidebar -->
