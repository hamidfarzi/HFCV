<?php

/**
 * The experience post type loop template
 * 
 * @package hfcv
 */

global $post;

$tax_slug = isset($args['tax']) ? $args['tax'] : '';
$type = isset($args['type']) ? $args['type'] : '';
$term_slug = isset($args['term']) ? $args['term'] : '';
$main_term = get_term_by('slug', $term_slug, $tax_slug);
$date = get_post_meta($post->ID, 'experience_date', true);
$display_mode = "article";

$experience_terms = get_the_terms($post->ID, $tax_slug);
$experience_type_terms = get_the_terms($post->ID, "experience-type");

$display_mode = get_term_meta($experience_type_terms[0]->term_id, 'display_mode', true);

$child_terms = array();
foreach ($experience_terms as $term) {
    if ($term->parent == $main_term->term_id) {
        $child_terms[] = $term;
    }
}

?>

<article class="experiences-loop-item loop-item <?php echo $display_mode . ' ' . ($type == "carousel" ? 'owl-item' : ''); ?>">
    <?php if ($display_mode != "article") : ?>
        <figure class="item-image">
            <a href="<?php the_permalink(); ?>">
                <div class="img-container">
                    <?php
                    echo apply_filters('hfcv/filters/thumbnail', $post);
                    ?>
                </div>
            </a>
        </figure>
    <?php endif; ?>
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

        <div class="year accent-text">
            <?php if ($date) echo date_i18n('Y', strtotime($date)); ?>
        </div>

    </div>

    <div class="item-body">
        <?php if ($display_mode !== "portfolio") : ?>
            <p class="item-description">
                <?php echo the_excerpt(); ?>
            </p>
        <?php endif; ?>

    </div>
</article>