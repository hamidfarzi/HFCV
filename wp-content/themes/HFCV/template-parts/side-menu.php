<?php

/**
 * The side menu.
 * 
 * @package hfcv
 */


function modify_menu_pattern($items, $args)
{

    foreach ($items as $item) {
        $image = apply_filters('hfcv/filters/thumbnail', $item, 10, 1);
        if ($args->theme_location == 'menu-2' && !empty($image)) {
            $item->title =  "<div class='menu-thumbnail'>" . $image . "</div>";
        }
    }

    return $items;
}
add_filter('wp_nav_menu_objects', 'modify_menu_pattern', 10, 2);

?>
<aside id="side-menu-container" class="side-menu-container">
    <nav>
        <?php
        wp_nav_menu(
            array(
                'theme_location' => 'menu-2',
                'menu_id'        => 'side-menu',
            )
        );
        ?>
    </nav>
</aside>