<?php

/**
 * Plugin Name: Gutenberg Shortcode Block
 * Author: Hamid Farzi
 * Description: Gutenberg block to display a shortcode with form attributes.
 * Version: 1.0.0
 */

defined('ABSPATH') || exit;

function hf_shortcode_block_register()
{
    $hfgsb_dir_uri = HFGSB_DIR_URI;

    // Register the block editor script
    wp_register_script(
        'hf-shortcode-block-editor',
        $hfgsb_dir_uri . '/editor.js',
        array('wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor', 'wp-components', 'wp-api-fetch'),
    );

    // Register the block type
    register_block_type('hf/hf-shortcode-block', array(
        'editor_script' => 'hf-shortcode-block-editor',
        'render_callback' => 'hf_shortcode_block_render',
    ));
}
add_action('init', 'hf_shortcode_block_register');

// Callback to generate shortcode from attributes
function hf_shortcode_block_render($attributes)
{
    if (isset($attributes['shortcode'])) {
        return $attributes['shortcode'];
    }
    return '';
}



// functions.php or in a custom plugin
add_action('rest_api_init', 'hf_register_shortcode_api_endpoint');
function hf_register_shortcode_api_endpoint()
{
    register_rest_route('hf-gsb/v1', '/forms', array(
        'methods' => 'GET',
        'callback' => 'hf_generate_shortcode_form',
        'permission_callback' => '__return_true'
    ));
    register_rest_route('hf-gsb/v1', '/shortcodes', array(
        'methods' => 'GET',
        'callback' => 'hf_shortcodes_list',
        'permission_callback' => '__return_true'
    ));
}

function hf_generate_shortcode_form()
{
    $template_path = HFGSB_TEMPLATES_DIR . '/hfgsb-' . sanitize_text_field($_GET['sc']) . '-guten.php';

    ob_start();
    require $template_path;
    $form = ob_get_clean();

    return $form;
}

function hf_shortcodes_list()
{
    // get shortcodes list array
    $list = apply_filters('hfgsb-shortcodes-list', true);
    return json_encode($list);
}


function hfgscb_shortcode_func($args)
{
    ob_start();
    get_template_part('template-parts/hfgsb-' . $args['sc'], 'front', $args);
    return ob_get_clean();
}
add_shortcode('HFGSB', 'hfgscb_shortcode_func', 10);
