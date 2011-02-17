				<ul class="meta">
<?php if(!in_category('uncategorized')): /* Only show Cats if post has real one(s). */ ?>
					<li class="categories"><?php _e('Category:', 'simplish'); ?> <?php the_category(', '); ?></li>
<?php endif; ?>
<?php if(get_the_tags() != ''): ?>
					<li class="tags"><?php the_tags(__('Tags: ', 'simplish')); ?></li>
<?php endif; ?>
					<li><?php comments_popup_link( __( 'Leave a reply', 'simplish' ), __( '1 Comment', 'simplish' ), __( '% Comments', 'simplish' ), 'comments-link', __( 'Comments Closed', 'simplish' ) ); ?></li>
				</ul>
