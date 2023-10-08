<?php

/**
 * The hfgsb gutenberg form template file
 * 
 * @package hfcv
 */
global $styleRootObject;

$tax_slug = isset($args['tax']) ? $args['tax'] : '';
$type = isset($args['type']) ? $args['type'] : '';
$level = isset($args['level']) ? $args['level'] : 'false';
$term_slug = isset($args['term']) ? $args['term'] : '';
$term = get_term_by('slug', $term_slug, $tax_slug);
$subtitle = isset($args['subtitle']) ? $args['subtitle'] : '';
$has_description = isset($args['description']) ? $args['description'] : "true";
?>

<article class="terms-loop-item loop-item <?php echo $type == "carousel" ? 'owl-item' : ''; ?>">
    <figure class="item-image <?php echo $level == "true" ? 'has-level' : '' ?>">
        <?php if ($level == "true") : ?>
            <div data-donutty data-circle=false data-radius=50 data-value="<?php $term_level = get_term_meta($term->term_id, 'term_level', true);
                                                                            echo $term_level ? $term_level : "100" ?>" data-color="var(--hfcv-color-primary-color)"></div>
        <?php
        endif;
        echo apply_filters('hfcv/filters/thumbnail', $term);
        ?>
    </figure>
    <div class="item-head">
        <a class="item-title" href="<?php echo get_term_link($term); ?>">
            <?php echo $term->name; ?>
        </a>
        <?php
        if (!empty($subtitle)) {
            echo '<span class="item-subtitle">' . $subtitle . '</span>';
        }
        ?>
    </div>
    <?php if ($has_description == "true") : ?>
        <div class="item-body">
            <p class="item-description">
                <?php echo $term->description; ?>
            </p>
        </div>
    <? endif; ?>
</article>