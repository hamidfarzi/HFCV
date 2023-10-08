<?php

/**
 * The post post type template file
 * 
 * @package hfcv
 */

global $post;

$post_date = get_the_date();
$post_cat = wp_get_post_terms($post->ID, 'category');
$post_tags = wp_get_post_terms($post->ID, 'post_tag');

?>

<main class="content">
    <?php get_template_part('template-parts/content-section', 'gallery'); ?>
    <section class="post-info">
        <div class="date">
            <?php if ($post_date) echo date_i18n('d M Y', strtotime($post_date)); ?>
        </div>
        <ul class="categories">
            <?php
            foreach ($post_cat as $post_cat) {
                echo '<li>' . $post_cat->name . '</li>';
            }
            ?>
        </ul>
    </section>
    <article>
        <?php the_content(); ?>
    </article>
    <section class="footnote">
        <ul class="tags">
            <?php
            foreach ($post_tags as $tag) {
                echo '<li><a href="' . get_term_link($tag) . '">' . $tag->name . '</a></li>';
            }
            ?>
        </ul>
        <?php get_template_part('template-parts/content-section', 'share'); ?>
    </section>

    <?php
    // If comments are open or we have at least one comment, load up the comment template.
    if (comments_open() || get_comments_number()) :
        comments_template('/template-parts/content-section-comments.php', true);
    endif;
    ?>
</main>