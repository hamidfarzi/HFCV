<?php

/**
 * The hfgsb gutenberg form template file
 * 
 * @package hfcv
 */
global $styleRootObject;

$tax_slug = isset($args['tax']) ? $args['tax'] : '';
$type = isset($args['type']) ? $args['type'] : '';
$contain = isset($args['contain']) ? $args['contain'] : '';
$level = isset($args['level']) ? $args['level'] : '';
$cols = isset($args['cols']) ? $args['cols'] : '2';
$count = isset($args['count']) ? $args['count'] : '';
$term_slugs = isset($args['terms']) ? $args['terms'] : '';

$term_slugs_arr = [];

if ($term_slugs) {
    $term_slugs_arr = explode('|', $term_slugs);
}

$query_args = [
    'taxonomy'   => $tax_slug,
    'hide_empty' => false,
    'number' => $count,
    'orderby' => 'meta_value_num',
    'order' => 'ASC',
    'meta_query' => [[
        'key' => '_order',
        'type' => 'NUMERIC',
    ]],
];

switch ($contain) {
    case 'top':
        $query_args['parent'] = 0;
        break;
    case 'all':
        break;
    case 'sel':
        $query_args['slug'] = $term_slugs_arr;
        break;
    case 'sel-child':
        $query_args['parent'] = get_term_by('slug', $term_slugs_arr[0], $tax_slug)->term_id;
        break;
}

$terms = get_terms($query_args);

?>
<div data-columns="<?php echo $cols; ?>" style="<?php echo "--data-columns:" . $cols; ?>" class="hfgsb-widget <?php echo $type . ' ' . ($type == "carousel" ? 'owl-carousel owl-loaded owl-drag' : ''); ?>">
    <div class="terms-loop-container <?php echo $tax_slug . ' ' . ($type == "carousel" ? 'owl-stage-outer' : ''); ?> ">
        <div class="terms-loop <?php echo $type == "carousel" ? 'owl-stage' : ''; ?>">
            <?php foreach ($terms as $term) {
                get_template_part('template-parts/content', 'loop-term', [
                    'tax' => $tax_slug,
                    'type' => $type,
                    'term' => $term->slug,
                    'level' => $level
                ]);
            }
            ?>
        </div>
    </div>
</div>