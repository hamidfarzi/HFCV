<?php

/**
 * The HFCV theme header.
 * 
 * @package hfcv
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <meta property="og:title" content="<?php the_title(); ?>" />
    <meta property="og:description" content="<?php echo get_the_excerpt(); ?>" />
    <meta property="og:image" content="<?php echo apply_filters('hfcv/filters/thumbnail', get_queried_object(), "url"); ?>" />
    <meta property="og:url" content="<?php the_permalink(); ?>" />
    <meta property="og:type" content="<?php echo is_singular() ? "article" : "website" ?>" />
    <meta property="og:locale" content="<?php get_locale(); ?>" />
    <link rel="canonical" href="<?php the_permalink(); ?>" />
    <?php
    function hfcv_meta_robots($args)
    {
        if (!is_admin()) {
            $args['index'] = true;
            $args['follow'] = true;
        }
        if (is_archive() || is_search() || is_404()) {
            $args['index'] = false;
            $args['noindex'] = true;
        }
        return $args;
    }
    add_filter('wp_robots', 'hfcv_meta_robots');

    ?>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <div id="main-container" class="main-container hfcv">
        <header id="site-header" class="site-header">
            <div class="branding">
                <a href="<?php echo esc_url(home_url('/')); ?>">
                    <?php echo apply_filters('hfcv/filters/brand', true); ?>
                </a>
            </div>
            <div class="navigation">
                <nav id="site-navigation" class="site-navigation">
                    <?php
                    $locations = get_nav_menu_locations();
                    $menu = get_term($locations['menu-1'], 'nav_menu');
                    $items = wp_get_nav_menu_items($menu->term_id);
                    if ($items) :
                    ?>

                        <div class="mobile-menu">
                            <select id="mobile-menu-drop">
                                <?php
                                $url = get_permalink();
                                foreach ($items as $item) {
                                    echo "<option value='" . $item->url . "'" . ($url == $item->url ? " selected " : "") . ">" . $item->title . "</option>";
                                }
                                ?>
                            </select>
                        </div>

                    <?php
                    endif;
                    wp_nav_menu(
                        array(
                            'theme_location' => 'menu-1',
                            'menu_id'        => 'primary-menu',
                        )
                    );
                    ?>
                </nav>
            </div>
        </header>