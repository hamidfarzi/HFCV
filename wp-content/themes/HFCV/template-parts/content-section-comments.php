<?php

/**
 * The template for displaying Comments.
 *
 * The area of the page that contains comments and the comment form.
 *
 * @package HFCV
 */

// Get only the approved comments

?>
<section class="comments">
    <?php if (have_comments()) : ?>

        <h4 class="comments-title">
            <?php
            printf(
                _nx(
                    'One thought on "%2$s"',
                    '%1$s thoughts on "%2$s"',
                    get_comments_number(),
                    'comments title',
                    'hfcv'
                ),
                number_format_i18n(get_comments_number()),
                '<span>' . get_the_title() . '</span>'
            );
            ?>
        </h4>

        <ol class="comment-list">
            <?php
            wp_list_comments(array(
                'style'       => 'ol',
                'short_ping'  => true,
                'avatar_size' => 30,
            ));
            ?>
        </ol><!-- .comment-list -->

        <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
            <nav class="navigation comment-navigation" role="navigation">

                <h4 class="screen-reader-text section-heading"><?php _e('Comment navigation', 'hfcv'); ?></h4>
                <div class="nav-previous"><?php previous_comments_link(__('&larr; Older Comments', 'hfcv')); ?></div>
                <div class="nav-next"><?php next_comments_link(__('Newer Comments &rarr;', 'hfcv')); ?></div>
            </nav><!-- .comment-navigation -->
        <?php endif; // Check for comment navigation 
        ?>

        <?php if (!comments_open() && get_comments_number()) : ?>
            <p class="no-comments"><?php _e('Comments are closed.', 'hfcv'); ?></p>
        <?php endif; ?>

    <?php endif; // have_comments() 
    ?>

    <?php comment_form([
        'class_form' => 'comment-form form-group',
    ]); ?>

</section>