<?php

/**
 * The  post loop template
 * 
 * @package hfcv
 */

global $post;

$tax_slug = isset($args['tax']) ? $args['tax'] : '';
$type = isset($args['type']) ? $args['type'] : '';
$term_slug = isset($args['term']) ? $args['term'] : '';
$main_term = get_term_by('slug', $term_slug, $tax_slug);
$display_mode = "article";

$post_terms = get_the_terms($post->ID, "category");

$child_terms = array();
foreach ($post_terms as $term) {
    $child_terms[] = $term;
}

?>

<article class="posts-loop-item loop-item <?php echo $display_mode . ' ' . ($type == "carousel" ? 'owl-item' : ''); ?>">
    <figure class="item-image">
        <a href="<?php the_permalink(); ?>">
            <div class="img-container">
                <?php
                echo apply_filters('hfcv/filters/thumbnail', $post, 'thumbnail');
                ?>
            </div>
        </a>
    </figure>
    <div class="item-head">
        <?php if ($child_terms) : ?>
            <ul class="item-terms">
                <?php foreach ($child_terms as $child_term) : ?>
                    <li><a class="accent-text" href="<?php echo get_term_link($child_term); ?>"><?php echo $child_term->name; ?></a></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <a class="item-title" href="<?php the_permalink(); ?>">
            <?php the_title(); ?>
        </a>
    </div>
    <div class="item-body">
        <?php if ($display_mode !== "portfolio") : ?>
            <div class="item-description">
                <?php echo the_excerpt(); ?>
            </div>
        <?php endif; ?>

    </div>
</article>