				<ul class="meta">
<?php if(!in_category('uncategorized')): /* Only show Cats if post has real one(s). */ ?>
					<li class="categories"><?php _e('Category:', 'simplish') ?> <?php the_category(', ') ?></li>
<?php endif; ?>
<?php if(get_the_tags() != ''): ?>
					<li class="tags"><?php the_tags(__('Tags: ', 'simplish')); ?></li>
<?php endif; ?>
					<li><a href="<?php comments_link(); ?>-heading"><?php comments_number(__('0 Comments', 'simplish'), __('1 Comment', 'simplish'), __('% Comments', 'simplish')); ?></a> &ndash; <?php post_comments_feed_link(__('Feed', 'simplish')); ?></li>
				</ul>
<!-- <?php trackback_rdf(); ?> -->
