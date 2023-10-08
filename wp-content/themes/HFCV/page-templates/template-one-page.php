<?php

/** 
 * Template Name: One page
 * @package hfcv
 **/

get_header();
global $post;
$pageType = apply_filters('hfcv/filters/template-type', true);
$subtitle = get_post_meta(get_the_ID(), 'subtitle', true);

get_template_part('template-parts/side', 'cover');
?>
<main id="main-content" class="main-content one-page-content">
    <section id="main-section" class="one-page-section main-section">
        <article class="<?php echo $pageType; ?>">
            <div class="title">
                <?php the_title('<h1>', '<small class="subtitle">' . $subtitle . '</small></h1>'); ?>
            </div>
            <div class="content">
                <?php
                the_content();
                ?>
            </div>
        </article>
    </section>

    <?php

    //get and display child pages
    $children = apply_filters('hfcv/filters/post-children', $post);

    //before children hook
    do_action('hfcv/actions/templates/one-page/before-children');

    //display children
    if ($children->have_posts()) : ?>

        <?php while ($children->have_posts()) : $children->the_post(); ?>

            <section id="<?php echo $post->post_name; ?>" class="one-page-section <?php echo $post->post_name; ?>">
                <div class="title">
                    <?php
                    $subtitle = get_post_meta(get_the_ID(), 'subtitle', true);
                    the_title('<h2>', '<small class="subtitle">' . $subtitle . '</small></h2>');
                    ?>
                </div>
                <?php
                get_template_part('template-parts/content', apply_filters('hfcv/filters/template-type', true));
                ?>
            </section>

        <?php endwhile; ?>

    <?php endif;
    wp_reset_postdata();

    get_template_part('template-parts/side', 'menu');
    ?>
</main>
<?php
get_footer();
