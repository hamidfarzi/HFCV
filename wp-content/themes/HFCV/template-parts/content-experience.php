<?php

/**
 * The experience post type template file
 * 
 * @package hfcv
 */

global $post;

$experience_date = get_post_meta($post->ID, "experience_date", true);
$experience_types = wp_get_post_terms($post->ID, 'experience-type');
$experience_skills = wp_get_post_terms($post->ID, 'skill');
$experience_clients = wp_get_post_terms($post->ID, 'client');




?>

<main class="content">
    <?php get_template_part('template-parts/content-section', 'gallery'); ?>
    <section class="post-info">
        <div class="date">
            <?php if ($experience_date) echo date_i18n('Y', strtotime($experience_date)); ?>
        </div>
        <ul class="categories">
            <?php
            foreach ($experience_types as $experience_type) {
                echo '<li>' . $experience_type->name . '</li>';
            }
            ?>
        </ul>
    </section>
    <section data-columns="5" style="--data-columns:5" class="skills carousel owl-carousel owl-loaded owl-drag">
        <div class="owl-stage-outer">
            <div class="owl-stage">
        <?php
        foreach ($experience_skills as $skill) {
            get_template_part('template-parts/content', 'loop-term', [
                'tax' => 'skill',
                'type' => 'carousel',
                'term' => $skill->slug,
                'level' => "true",
                'description' => false
            ]);
        }
        ?>
        </div>
        </div>
    </section>
    <article>
        <?php the_content(); ?>
    </article>
    <?php if ($experience_clients && count($experience_clients) > 0) : ?>
        <section class="clients">
            <h2>Client</h2>
            <div data-columns=2 style="--data-columns:2" class="owl-carousel owl-loaded owl-drag">
                <div class="clients-container owl-stage-outer">
                    <div class="clients-loop owl-stage">
                        <?php
                        foreach ($experience_clients as $client) {
                            get_template_part('template-parts/content', 'loop-term', [
                                'tax' => 'client',
                                'type' => 'carousel',
                                'term' => $client->slug,
                                'level' => "false",
                                'subtitle' => get_term_meta($client->term_id, 'client_experties', true),
                            ]);
                        }
                        ?>
                    </div>
                </div>
            </div>

        </section>
    <?php endif; ?>
    <section class="footnote">
        <?php get_template_part('template-parts/content-section', 'share'); ?>
    </section>

</main>