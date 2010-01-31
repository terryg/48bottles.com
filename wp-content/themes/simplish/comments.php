<?php // Do not delete these lines
	if(!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die(__('Not labeled for individual sale.', 'simplish'));

	if(post_password_required()){ ?>
		<p class="nocomments">Enter post password to view comments.</p>
	<?php
		return;
	}
?>

<h5 id="comments-heading"><?php comments_number(__('0 Comments', 'simplish'), __('1 Comment', 'simplish'), __('% Comments', 'simplish'));?> <?php _e('on', 'simplish'); ?> <em><?php the_title(); ?></em></h5>
	<?php if('open' == $post->comment_status) : /* Comments open */ ?>
		<?php if(get_option('comment_registration') && !$user_ID): ?>
<p><a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php the_permalink(); ?>"><?php _e('Log in'); ?></a> <?php _e('to respond'); ?>
			<?php if('open' == $post->ping_status): /* Trackbacks open */ ?>
| <a href="<?php trackback_url(true); ?>" rel="trackback"><?php _e('Trackback', 'simplish'); ?></a>
			<?php endif; ?>
			</p>
		<?php else: ?>
<p><a href="#respond"><?php _e('Respond', 'simplish'); ?></a>
			<?php if('open' == $post->ping_status): /* Trackbacks open */ ?>
				| <a href="<?php trackback_url(true); ?>" rel="trackback"><?php _e('Trackback', 'simplish'); ?></a>
			<?php endif; ?>
			</p>
		<?php endif; ?>
		
	<?php else: /* Comments closed. */ ?>
			<p class="nocomments"><?php _e('Closed', 'simplish'); ?></p>
	<?php endif; ?>

	<?php if(have_comments()): ?>
			<ol id="commentslist" class="comments">
				<?php wp_list_comments(); ?>
			</ol>
			<div id="commentsnav" class="navigation">
				<div class="prevlink"><?php previous_comments_link(__('&laquo; Older Comments', 'simplish')) ?></div>
				<div class="nextlink"><?php next_comments_link(__('Newer Comments &raquo;', 'simplish')) ?></div>
			</div>
	<?php endif; ?>


<?php if('open' == $post->comment_status): ?>

<?php if(!(get_option('comment_registration') && !$user_ID)): ?>

<div id="respond">

	<h4><?php comment_form_title(__('Respond', 'simplish'), __('Respond to %s', 'simplish')); ?></h4>

	<div class="cancel-comment-reply">
	<small><?php cancel_comment_reply_link(); ?></small>
	</div>

	<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" class="comments" id="commentform" method="post">

	<fieldset>
	<legend><?php _e('Comments', 'simplish'); ?></legend>
	<?php if($user_ID): ?>

		<p>
		[ <?php _e('Logged in as', 'simplish'); ?> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>
		| <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="<?php _e('Log out of this account', 'simplish'); ?>"><?php _e('Log out', 'simplish'); ?></a> ]
		</p>

	<?php else : ?>

		<p>
		<label><?php _e('Name:', 'simplish'); ?> <?php if($req) echo '<small>' . __('(required)', 'simplish') . '</small>'; ?><br />
			<input name="author" id="author" value="<?php echo $comment_author; ?>" size="30" type="text" tabindex="1" />
		</label>
		</p>

		<p>
		<label><?php _e('Email:', 'simplish'); ?> <?php if($req) echo '<small>' . __('(required)', 'simplish') . '</small>'; ?><br />
			<input name="email" id="email" value="<?php echo $comment_author_email; ?>" size="30" type="text" tabindex="2" />
			<small><?php _e('(will not be published)', 'simplish'); ?></small>
		</label>
		</p>

		<p>
		<label><?php _e('URL:', 'simplish'); ?><br />
			<input name="url" id="url" value="<?php echo $comment_author_url; ?>" size="30" type="text" tabindex="3" />
		</label>
		</p>

	<?php endif; ?>

<!--<p><small><strong>XHTML:</strong> <?php _e('You can use these tags:', 'simplish'); ?> <?php echo allowed_tags(); ?></small></p>-->

	<p>
		<?php _e('Comments', 'simplish'); ?><br />
		<textarea name="comment" id="comment" cols="100%" rows="20" tabindex="4"></textarea></p>
	<p>
		<input name="submit" type="submit" id="submit" tabindex="5" value="<?php _e('Submit', 'simplish'); ?>" />
		<?php comment_id_fields(); ?>
	</p>
	<?php do_action('comment_form', $post->ID); ?>
	</fieldset>
	</form>

</div>

<?php endif; // If registration required and not logged in ?>

<?php endif; // DO NOT REMOVE ?>
