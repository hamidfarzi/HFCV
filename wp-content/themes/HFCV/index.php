<?php

/**
 * The main template file
 * 
 * @package hfcv
 */
get_header();
$pageType = apply_filters('hfcv/filters/template-type', true);

get_template_part('template-parts/side', 'cover');
?>

<main id="main-content" class="main-content">
    <article class="<?php echo $pageType; ?>">
        <div class="title">
            <?php
            if (!is_home() && $pageType == "term") {
                echo "<h1>" . get_the_archive_title() . "</h1>";
            } else {
                $subtitle = get_post_meta(get_the_ID(), 'subtitle', true);
                if (is_home()) {
                    echo '<h1>' . get_the_title(get_option('page_for_posts', true)) . '<small class="subtitle">' . $subtitle . '</small></h1>';
                } elseif (strpos(get_page_template_slug(), 'template-one-page') !== false) {
                    the_title('<h2>', '<small class="subtitle">' . $subtitle . '</small></h2>');
                } else {
                    the_title('<h1>', '<small class="subtitle">' . $subtitle . '</small></h1>');
                }
            }
            ?>
        </div>


        <?php
        do_action('hfcv/actions/after-title');
        get_template_part('template-parts/content', $pageType);

        ?>
    </article>
</main>
<?php
get_template_part('template-parts/side', 'menu');

get_footer();
