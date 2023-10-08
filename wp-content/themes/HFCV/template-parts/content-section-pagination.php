<?php

/**
 * The pagination section template file
 * 
 * @package hfcv
 */

global $post;

$current = (get_query_var('paged')) ? get_query_var('paged') : 1;
$next_link = get_next_posts_link('<button type="button" class="btn-next">' . ($current + 1) . '</button>');
$prev_link = get_previous_posts_link('<button type="button" class="btn-prev">' . ($current - 1) . '</button>');

$next_link = ($next_link) ? $next_link : '<button type="button" class="btn-next"></button>';
$prev_link = ($prev_link) ? $prev_link : '<button type="button" class="btn-prev"></button>';

?>
<section class="pagination">
    <?php echo $prev_link . $next_link; ?>
</section>