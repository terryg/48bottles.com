<?php if ( $this->have_comments() ) : ?>
<h3 id="comments"><?php $this->comments_number('No Responses', 'One Response', '% Responses' );?> to &#8220;<?php $this->recipe_title(); ?>&#8221;</h3>

<div class="navigation">
    <div class="alignleft"><?php $this->previous_comments_link() ?></div>
    <div class="alignright"><?php $this->next_comments_link() ?></div>
</div>

    <?php $this->list_comments(); ?>

<div class="navigation">
    <div class="alignleft"><?php $this->previous_comments_link() ?></div>
    <div class="alignright"><?php $this->next_comments_link() ?></div>
</div>
<?php else : // this is displayed if there are no comments so far ?>

    <?php if ( $this->comments_open() ) : ?>
<!-- If comments are open, but there are no comments. -->

    <?php else : // comments are closed ?>
<!-- If comments are closed. -->
<p class="nocomments">Comments are closed.</p>

    <?php endif; ?>
<?php endif; ?>


<?php if ( $this->comments_open() ) : ?>


<div id="respond">

    <h3><?php print $this->options['comment-form-title']; ?></h3>

        <?php if ( $this->options['comments-login'] && !is_user_logged_in() ) : ?>
    <p>You must be <a href="<?php print wp_login_url( $this->getRecipeLink() ); ?>">logged in</a> to post a comment.</p>
        <?php else : ?>

    <form action="<?php print get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
        <input name="comment_recipe_id" type="hidden" value="<?php $this->recipe_id(); ?>" />
        <input name="comment_status" type="hidden" value="pending" />

                <?php if ( is_user_logged_in() ) : ?>

        <p>Logged in as <a href="<?php print get_option('siteurl'); ?>/wp-admin/profile.php"><?php print $this->displayUser(); ?></a>. <a href="<?php print wp_logout_url($this->getRecipeLink()); ?>" title="Log out of this account">Log out &raquo;</a></p>

                <?php else : ?>

        <p><input type="text" name="author" id="author" value="<?php print esc_attr($comment_author); ?>" size="22" tabindex="1" <?php if ($req) print "aria-required='true'"; ?> />
            <label for="author"><small>Name <?php if ($req) print "(required)"; ?></small></label></p>

        <p><input type="text" name="email" id="email" value="<?php print esc_attr($comment_author_email); ?>" size="22" tabindex="2" <?php if ($req) print "aria-required='true'"; ?> />
            <label for="email"><small>Mail (will not be published) <?php if ($req) print "(required)"; ?></small></label></p>

        <p><input type="text" name="url" id="url" value="<?php print esc_attr($comment_author_url); ?>" size="22" tabindex="3" />
            <label for="url"><small>Website</small></label></p>

                <?php endif; ?>

<!--<p><small><strong>XHTML:</strong> You can use these tags: <code><?php print allowed_tags(); ?></code></small></p>-->

        <p><textarea name="comment" id="comment" cols="58" rows="10" tabindex="4"></textarea></p>

        <p><input name="submit" type="submit" id="submit" tabindex="5" value="Submit Comment" />
                    <?php comment_id_fields(); ?>
        </p>

    </form>

        <?php endif; // If registration required and not logged in ?>
</div>

<?php endif; // if you delete this the sky will fall on your head ?>