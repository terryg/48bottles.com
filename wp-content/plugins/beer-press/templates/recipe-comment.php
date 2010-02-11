<li id="li-comment-<?php $this->comment_id(); ?>" class="rp-comment">
    <div class="rp-comment-head">
        <?php $this->comment_gravatar(); ?>
        <div class="rp-user-meta">
            <span class="rp-name"><?php $this->comment_author(); ?></span>
            <span class="rp-date"><?php $this->comment_date(); ?></span>
            <span class="rp-perma"><?php $this->comment_link(); ?></span>
        </div>
        <div class="rp-clear"/>
    </div>
    <div id="comment-<?php $this->comment_id(); ?>" class="rp-comment-entry">
        <p><?php $this->comment_content(); ?></p>
    </div>
    <?php $this->comment_status(); ?>
</li>