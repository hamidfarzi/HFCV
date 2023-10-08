<?php

/**
 * The HFCV theme functions.
 * 
 * @package hfcv
 */

/* -----------------------------------------------------------
* Define HFCV Constants
*/

global $styleRootObject, $customizer;


define('HFCV_VERSION', '1.1.1');
define('HFCV_STYLE_URI', get_stylesheet_directory_uri());
define('HFCV_THEME_URI', get_template_directory_uri());
define('HFCV_STYLE_DIR', get_stylesheet_directory());
define('HFCV_THEME_DIR', get_template_directory());

define('HFGSB_DIR_URI', HFCV_STYLE_URI . '/inc/libs/gutenberg-shortcode-block');
define('HFGSB_TEMPLATES_DIR', HFCV_STYLE_DIR . '/template-parts');



/* -----------------------------------------------------------
* Theme Setup
*/


function hfcv_setup()
{
    global $styleRootObject, $customizer;

    load_theme_textdomain('hfcv', HFCV_STYLE_DIR . '/languages');

    add_theme_support('title-tag');

    add_theme_support('post-thumbnails');
    add_post_type_support('page', 'excerpt');
    add_post_type_support('post', 'excerpt');
    add_post_type_support('experience', 'excerpt');

    add_theme_support(
        'html5',
        array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        )
    );

    add_theme_support(
        'custom-logo',
        array(
            'height'      => 250,
            'width'       => 250,
            'flex-width'  => true,
            'flex-height' => true,
        )
    );

    register_nav_menus(
        array(
            'menu-1' => esc_html__('Primary', 'hfcv'),
            'menu-2' => esc_html__('Side Menu', 'hfcv')
        )
    );

    $pagesMetaBox = new \HFCV\EditorSettingsMetaBox(
        'page',
        'HFCV Settings',
        'side',
        'default',
        [
            [
                'name' => 'subtitle',
                'label' => __('Subtitle', 'hfcv'),
                'type' => 'text'
            ],
        ]
    );

    $experiencseMetaBox = new \HFCV\EditorSettingsMetaBox(
        'experience',
        'HFCV Settings',
        'advanced',
        'high',
        [
            [
                'name' => 'experience_date',
                'label' => __('Experience Date', 'hfcv'),
                'type' => 'date'
            ],
            [
                'name' => 'hfcv_post_gallery',
                'label' => __('Upload experience gallery', 'hfcv'),
                'type' => 'gallery'
            ],
        ]
    );

    $experiencseMetaBox = new \HFCV\EditorSettingsMetaBox(
        'post',
        'HFCV Settings',
        'advanced',
        'high',
        [
            [
                'name' => 'hfcv_post_gallery',
                'label' => __('Upload post gallery', 'hfcv'),
                'type' => 'gallery'
            ],
        ]
    );

    // get style root variables
    $styleRootJson = get_option('--hfcv-style-root-json');
    $styleRootVersion = get_option('--hfcv-style-root-version');

    if (!$styleRootJson || $styleRootVersion != HFCV_VERSION) {

        $cssContent = file_get_contents(HFCV_STYLE_URI . '/style.css');
        $cssParser = new \HFCV\CssParser($cssContent);

        $styleRootObject = $cssParser->extractRootObject();

        update_option('--hfcv-style-root-json', json_encode($styleRootObject));
        update_option('--hfcv-style-root-version', HFCV_VERSION);
    } else {
        $styleRootObject = json_decode($styleRootJson, true);
    }
    // set customizer content variables
    $customizerContentJson = get_option('--hfcv-customizer-content-json');
    if (empty($customizerContentJson)) {
        $customizerContentObject = [
            "--hfcv-content-contact-phone" => "+18188888888",
            "--hfcv-content-contact-email" => "email@test.com",
            "--hfcv-content-contact-address" => "this is a placeholder for address contain country, city, area and etc.",
            "--hfcv-content-footnote" => "this is a foot note",
            "--hfcv-content-additional-script" => "",
        ];
        update_option('--hfcv-customizer-content-json', json_encode($customizerContentObject));
    } else {
        $customizerContentObject = json_decode($customizerContentJson, true);
    }
    // register customizer
    $customizer = new \HFCV\Customizer();
    $customizer->set_style_variables($styleRootObject);
    $customizer->set_content_variables($customizerContentObject);

    add_action('customize_register', array($customizer, 'register'));


    // register post types
    $experience = new \HFCV\PostType('Experience', 'experience', ['taxonomies' => ['Skills', 'project-type']]);
    $experience->set_post_tax(
        'Skill',
        'skill',
        [
            'extra_fields' => [
                [
                    'name' => '_order',
                    'label' => __('Order', 'hfcv'),
                    'type' => 'text',
                    'default' => '1',
                ],
                [
                    'name' => 'term_level',
                    'label' => __('Skill Level(%)', 'hfcv'),
                    'type' => 'text'
                ],
            ],
            'extra_columns' => [
                '_order' => 'Order'
            ]
        ]
    );
    $experience->set_post_tax(
        'Client',
        'client',
        [
            'extra_fields' => [
                [
                    'name' => '_order',
                    'label' => __('Order', 'hfcv'),
                    'type' => 'text',
                    'default' => '1',
                ],
                [
                    'name' => 'client_experties',
                    'label' => __('Expertise', 'hfcv'),
                    'type' => 'text'
                ],
                [
                    'name' => 'webpage',
                    'label' => __('Webpage Url', 'hfcv'),
                    'type' => 'text'
                ],
            ],
            'extra_columns' => [
                '_order' => 'Order'
            ]
        ]
    );
    $experience->set_post_tax(
        'Experience Type',
        'experience-type',
        [
            'extra_fields' => [
                [
                    'name' => '_order',
                    'label' => __('Order', 'hfcv'),
                    'type' => 'text',
                    'default' => '1',
                ],
                [
                    'name' => 'display_mode',
                    'label' => __('Display Mode', 'hfcv'),
                    'type' => 'select',
                    'options' => [
                        __('Portfolio', 'hfcv') => 'portfolio',
                        __('Article', 'hfcv') => 'article',
                        __('Info box', 'hfcv') => 'infobox',
                    ]
                ],
            ],
            'extra_columns' => [
                '_order' => 'Order'
            ]
        ]
    );
}
add_action('after_setup_theme', 'hfcv_setup');






// Enqueue frontend scripts and styles.
function hfcv_scripts()
{
    wp_enqueue_script('hfcv-carousel', HFCV_THEME_URI . '/assets/js/owl.carousel.min.js', array('jquery'), HFCV_VERSION, true);
    wp_enqueue_style('hfcv-style', HFCV_STYLE_URI . '/style.css', array(), HFCV_VERSION);
    wp_style_add_data('hfcv-style', 'rtl', 'replace');

    wp_enqueue_script('hfcv-donutty', HFCV_THEME_URI . '/assets/js/donutty-jquery.js', array('jquery'), HFCV_VERSION, true);

    wp_enqueue_script('hfcv-main', HFCV_THEME_URI . '/assets/js/main.js', array('jquery'), HFCV_VERSION, false);
    wp_localize_script('hfcv-main', 'initData', apply_filters('hfcv/filters/init-main-js', true));
    wp_localize_script('hfcv-main', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'hfcv_scripts');


// Enqueue admin scripts and styles.
function hfcv_admin_scripts()
{
    wp_enqueue_style('hfcv-admin-style', HFCV_THEME_URI . '/assets/css/admin.css', array(), HFCV_VERSION);
    wp_style_add_data('hfcv-admin-style', 'rtl', 'replace');

    wp_enqueue_script('hfcv-admin-script', HFCV_THEME_URI . '/assets/js/admin.js', array(), HFCV_VERSION, true);
    wp_enqueue_media();
}
add_action('admin_enqueue_scripts', 'hfcv_admin_scripts');

// footer additional scripts

function hfcv_additional_scripts()
{
    echo get_theme_mod('--hfcv-content-additional-script');
}
add_action('wp_footer', 'hfcv_additional_scripts');


/* -----------------------------------------------------------
* Includes
*/

// Gutenberg Widgets

require_once(HFCV_THEME_DIR . '/inc/libs/gutenberg-shortcode-block/gutenberg-shortcode-block.php');

// Classes

require_once(HFCV_THEME_DIR . '/inc/classes/class-editor-settings-meta-box.php');
require_once(HFCV_THEME_DIR . '/inc/classes/class-css-parser.php');
require_once(HFCV_THEME_DIR . '/inc/classes/class-customizer.php');
require_once(HFCV_THEME_DIR . '/inc/classes/class-posttype.php');


/* -----------------------------------------------------------
* Hooks
*/



//Pass Data to main js
function init_main_js_func($args)
{
    global $styleRootObject;
    $props = [];
    foreach ($styleRootObject[':root'] as $key => $value) {
        $props[$key] = get_theme_mod($key, $value);
    }

    return [
        'style_variables' => $props
    ];
}
add_filter('hfcv/filters/init-main-js', 'init_main_js_func', 10, 1);


//Replace Logo with site title if was not uploaded
function hfcv_brand_func($args)
{
    $logo = get_theme_mod('custom_logo');

    if (!$logo) {
        if (is_front_page()) {
            $logo = '<h1 class="site-title">' . get_bloginfo('name') . '</h1>';
        } else {
            $logo = '<span class="site-title">' . get_bloginfo('name') . '</span>';
        }
    } else {

        $image = wp_get_attachment_image_src($logo, 'full');
        $image[0];
        $element = apply_filters('hfcv/filters/thumbnail', [
            "url" => $image[0],
            "alt" => get_bloginfo('name'),
            "type" => "logo",
        ], "full");
        $logo = '<span class="site-title logo">' . $element . '</span>';
    }
    return $logo;
}
add_filter('hfcv/filters/brand', 'hfcv_brand_func', 10, 1);


// extend wordpress get_post_type
function hfcv_template_type_func($args)
{
    $pageType = get_post_type();
    if (is_search()) {
        $pageType = 'search';
    }
    if (is_tax() || is_category() || is_tag() || is_home()) {
        $pageType = 'term';
    }
    if (is_404()) {
        $pageType = 'err404';
    }
    return $pageType;
}
add_filter('hfcv/filters/template-type', 'hfcv_template_type_func', 10, 1);


// get post children
function hfcv_post_children_func($args)
{
    global $post;

    $queryArgs = array(
        'post_type'      => get_post_type($post),
        'posts_per_page' => -1,
        'post_parent'    => $post->ID,
        'order'          => 'ASC',
        'orderby'        => 'menu_order'
    );
    $children = new WP_Query($queryArgs);
    return $children;
}
add_filter('hfcv/filters/post-children', 'hfcv_post_children_func', 10, 1);


//HFGSB shortcodes list
function hfcv_hfgsb_shortcodes_list_func($args)
{
    return array(
        'Select a shortcode',
        'typing-effect',
        'quality-item',
        'terms',
        'experience'
    );
}
add_filter('hfgsb-shortcodes-list', 'hfcv_hfgsb_shortcodes_list_func', 10, 1);


// get thumbnail
function hfcv_thumbnail_func($obj, $size = 'thumbnail')
{

    try {
        if (!$obj) $obj = new WP_Error('404');

        global $styleRootObject;
        $url = "";
        $alt = "";
        $type = "";
        $reqURL = false;
        if ($size == "url") {
            $size = "full";
            $reqURL = true;
        }
        $compare = is_array($obj) ? $obj : get_class($obj);
        switch ($compare) {
            case WP_Term::class:
                $url = get_term_meta($obj->term_id, 'taxonomy_image', true);
                $alt = $obj->name;
                $type = "term";
                if (empty($url)) {
                    $url =  HFCV_THEME_URI . '/assets/img/' . $obj->taxonomy . '-icon.svg';
                }
                break;
            case WP_Post::class:

                $post_type = get_post_type($obj);
                if ($post_type == "nav_menu_item") {

                    $url = get_post_meta($obj->ID, '_menu_image', true);
                    $alt = $obj->title;
                    $type = $post_type;
                } elseif (is_home() && $post_type == 'page') {
                    $url = get_the_post_thumbnail_url($obj->ID, $size);
                    $alt = $obj->title;
                    $type = $post_type;
                    if (empty($url)) {
                        $url =  HFCV_THEME_URI . '/assets/img/blog-icon.svg';
                    }
                } else {

                    $url = get_the_post_thumbnail_url($obj->ID, $size);
                    $alt = $obj->title;
                    $type = $post_type;
                    if (empty($url)) {
                        $url =  HFCV_THEME_URI . '/assets/img/' . $post_type . '-icon.svg';
                    }
                }
                break;
            case WP_Error::class:
                $url =  HFCV_THEME_URI . '/assets/img/err404-icon.svg';
                $alt = 'page not found';
                $type = 'err404';
                break;
            default:
                $url =  isset($obj['url']) ? $obj['url'] : "";
                $alt = isset($obj['alt']) ? $obj['alt'] : "";
                $type = isset($obj['type']) ? $obj['type'] : "";
                break;
        }

        if (!$url) {
            return;
        }

        if (strpos($url, '.svg')) {
            //$element = "<object type='image/svg+xml' data='{$url}' class='icon {$type}-thumbnail' ></object>";
            $element = "<object type='image/svg+xml' class='icon {$type}-thumbnail' style='-webkit-mask-image:url({$url}) ;mask-image:url({$url}); '></object>";
        } else {
            $element = "<img src='{$url}' class='{$type}-thumbnail' alt='{$alt}'>";
        }
        if ($reqURL) {
            return $url;
        }
        return $element;
    } catch (Exception $e) {
        return null;
    }
}
add_filter('hfcv/filters/thumbnail', 'hfcv_thumbnail_func', 10, 2);



//add SVG support
function add_file_types_to_uploads($file_types)
{

    $new_filetypes = array();
    $new_filetypes['svg'] = 'image/svg';
    $file_types = array_merge($file_types, $new_filetypes);

    return $file_types;
}
add_action('upload_mimes', 'add_file_types_to_uploads');




// menu item image support
function custom_menu_item_fields($item_id, $item, $depth, $args)
{
    ob_start();
    $field_id = 'custom_image_' . $item_id;
    $custom_image = get_post_meta($item_id, '_menu_image', true);
?>
    <p class="field-custom-image description description-wide">
        <label for="<?php echo esc_attr($field_id); ?>">
            <?php esc_html_e('Image', 'text-domain'); ?>
        </label>
        <br />
        <img src="<?php echo esc_url($custom_image); ?>" style="max-width: 4vw;" class="custom-image-preview" />
        <br />
        <input type="hidden" name="<?php echo esc_attr($field_id); ?>" id="<?php echo esc_attr($field_id); ?>" value="<?php echo esc_attr($custom_image); ?>" class="widefat custom-image-input" />
        <input type="button" data-id="<?php echo $item_id; ?>" class="button button-secondary custom-image-button" value="<?php esc_attr_e('Select Image', 'text-domain'); ?>" />
        <input type="button" class="button button-secondary custom-image-remove-button" value="<?php esc_attr_e('Remove Image', 'text-domain'); ?>" />
    </p>
<?php
    $output = ob_get_clean();
    echo $output;
}

add_action('wp_nav_menu_item_custom_fields', 'custom_menu_item_fields', 10, 4);

function save_custom_menu_item_fields($menu_id, $menu_item_db_id, $menu_item_args)
{
    if (isset($_POST['custom_image_' . $menu_item_db_id])) {
        $custom_image = $_POST['custom_image_' . $menu_item_db_id];
        update_post_meta($menu_item_db_id, '_menu_image', $custom_image);
    }
}
add_action('wp_update_nav_menu_item', 'save_custom_menu_item_fields', 10, 3);



/* -----------------------------------------------------------
* Ajax Handlers
*/


// Contact Form

function contact_form_ajax_submittion()
{

    $request_params = [
        'name' => $_REQUEST["name"],
        'email' => $_REQUEST["email"],
        'phone' => $_REQUEST["phone"],
        'message' => $_REQUEST["message"]
    ];

    $responce = [
        "status" => 'success',
        "content" => __("The message has been submitted successfully.", 'hfcv'),
        "invalids" => []
    ];

    foreach ($request_params as $key => $value) {
        if (empty($value)) {
            $responce["status"] = 'failed';
            $responce["content"] = __('Please input valid value for these fields:', 'hfcv');
            $responce["invalids"][] = $key;
        }
        if ($key == 'email' && !is_email($value)) {
            $responce["status"] = 'failed';
            $responce["content"] = __('Please input valid value for these fields:', 'hfcv');
            $responce["invalids"][] = $key;
        }
    }
    if ($responce["status"] == 'success') {
        $emailBody = <<<EOT
        Name: {$request_params['name']} <br>
        email: {$request_params['email']} <br>
        phone: {$request_params['phone']} <br>
        message: <br> {$request_params['message']}
EOT;
        $result = wp_mail(
            get_bloginfo('admin_email'),
            __("Contact form submittion", 'hfcv'),
            $emailBody,
            array('Content-Type: text/html; charset=UTF-8')
        );
        if (!$result) {
            $responce["status"] = 'failed';
            $responce["content"] = __('Submittion has been failed, please try again.', 'hfcv');
        }
    }
    echo json_encode($responce);
    die();
}
add_action('wp_ajax_contact_form_submittion', 'contact_form_ajax_submittion');
add_action('wp_ajax_nopriv_contact_form_submittion', 'contact_form_ajax_submittion');
