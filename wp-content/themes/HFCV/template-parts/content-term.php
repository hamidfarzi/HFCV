<?php

/**
 * The taxonomy term template file
 * 
 * @package hfcv
 */

global $post;

$is_blog = is_home();
$queried_object = get_queried_object();
$tax_slug = isset($args['tax']) ? $args['tax'] : ($is_blog ? 'category' : $queried_object->taxonomy);
$type = isset($args['type']) ? $args['type'] : 'list';
$cols = isset($args['cols']) ? $args['cols'] : '3';
$count = isset($args['count']) ? $args['count'] : '-1';
$term_slugs = isset($args['terms']) ? $args['terms'] : ($is_blog ? '' : $queried_object->slug);
$is_shortcode = isset($args['type']) ? true : false;

$description = term_description();



$term_post_types = [];
if (is_a($queried_object, 'WP_Term')) {
    $term_post_types = get_taxonomy($tax_slug)->object_type;
    if (!is_array($term_post_types)) {
        $term_post_types = [];
    }
}


$term_slugs_arr = [];
if ($term_slugs) {
    $term_slugs_arr = explode('|', $term_slugs);
}
$tax_query_args = [
    'taxonomy'   => $tax_slug,
    'hide_empty' => false,
    'field' => 'slug',
    'terms' => $term_slugs_arr,
    'operator' => 'IN',
];
$posts = get_posts([
    'post_type' =>  $term_post_types,
    'numberposts' => $count,
    'post_status' => 'publish',
    'orderby' => 'date',
    'order' => 'DESC',
    'posts_per_page' => !$is_shortcode ? get_option('posts_per_page') : -1,
    'paged' => (get_query_var('paged')) ? get_query_var('paged') : 1,
    'tax_query' => $is_blog ? '' : [$tax_query_args]
]);

$child_query_args = [
    'taxonomy'   => $tax_slug,
    'hide_empty' => false,
    'parent' => $is_blog ? '' : get_term_by('slug', $term_slugs_arr[0], $tax_slug)->term_id,
    'orderby' => 'meta_value_num',
    'order' => 'ASC',
    'meta_query' => $is_blog ? '' :[[
        'key' => '_order',
        'type' => 'NUMERIC',
    ]],
];

$child_terms = get_terms($child_query_args);
$child_type = "carousel";
?>

<?php if (!$is_shortcode) : ?>
    <main class="content">
        <?php if (!empty($description)) : ?>
            <div class="term-description">
                <?php echo $description; ?>
            </div>

        <?php endif; ?>
        <?php if (!empty($child_terms)) : ?>
            <div class="child-terms-container">
                <div data-columns="<?php echo $cols; ?>" style="<?php echo "--data-columns:" . $cols; ?>" class="child-terms <?php echo $child_type . ' ' . ($child_type == "carousel" ? 'owl-carousel owl-loaded owl-drag' : ''); ?>">
                    <div class="terms-loop-container <?php echo $tax_slug . ' ' . ($child_type == "carousel" ? 'owl-stage-outer' : ''); ?> ">
                        <div class="terms-loop <?php echo $child_type == "carousel" ? 'owl-stage' : ''; ?>">
                            <?php
                            foreach ($child_terms as $term) {
                                get_template_part('template-parts/content', 'loop-term', [
                                    'tax' => $tax_slug,
                                    'type' => $child_type,
                                    'term' => $term->slug,
                                    'level' => "true"
                                ]);
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
    <?php endif;
    endif; ?>

    <div data-columns="<?php echo $cols; ?>" style="<?php echo "--data-columns:" . $cols; ?>" class="hfgsb-widget post-loop-widget <?php echo $type . ' ' . ($type == "carousel" ? 'owl-carousel owl-loaded owl-drag' : ''); ?>">
        <div class="posts-loop-container <?php echo $tax_slug . ' ' . ($type == "carousel" ? 'owl-stage-outer' : ''); ?> ">
            <div class="posts-loop <?php echo $type == "carousel" ? 'owl-stage' : ''; ?>">
                <?php
                foreach ($posts as $post) {
                    setup_postdata($post);
                    get_template_part('template-parts/content', 'loop-' . $post->post_type, [
                        'tax' => $tax_slug,
                        'type' => $type,
                        'term' => $is_blog ? '' : $term_slugs_arr[0],
                    ]);
                }
                wp_reset_postdata(); ?>
            </div>
        </div>
    </div>
    <?php if (!$is_shortcode) {
        get_template_part('template-parts/content-section', 'pagination');
        echo '</main>';
    } ?>