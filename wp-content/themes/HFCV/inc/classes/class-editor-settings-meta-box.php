<?php

/**
 * The custom gutenberg editor settings meta box class.
 * @package hfcv
 */

namespace HFCV;

class EditorSettingsMetaBox
{
    private $postID;
    private $inputs;
    private $form;
    private $post_type;
    private $title;
    private $location;
    private $priority;

    public function __construct(string $post_type, string $title, string $location, string $priority = "default", array $inputs)
    {
        $this->inputs = $inputs;
        $this->post_type = $post_type;
        $this->title = $title;
        $this->location = $location;
        $this->priority = $priority;

        add_action('save_post', array($this, 'save_post_meta'), 10, 1);
        add_action('add_meta_boxes', array($this, 'gutenberg_register_meta_box'));
    }
    public function gutenberg_register_meta_box()
    {

        add_meta_box(
            'gutenberg-custom-meta-box',
            $this->title,
            array($this, 'render_meta_box_fields'),
            $this->post_type,
            $this->location,
            $this->priority,
        );
    }
    // Render the meta box fields based on the template name
    public function render_meta_box_fields($post)
    {

        if (!$this->postID) {
            $this->postID = $post->ID;
        }
        $this->createForm();
        $this->displayForm();

        return;
    }

    // Save the meta box fields as post meta
    public function save_post_meta($post_id)
    {

        // Check if the current post is of type 'page' and if it's auto-saving
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check if the current user can edit the post
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        if (empty($this->inputs)) {
            return;
        }

        foreach ($this->inputs as $input) {
            if (isset($_POST[$input['name']])) {
                if ($input['type'] == "gallery") {
                    $gallery_images = array_map('intval', $_POST[$input['name']]);
                    update_post_meta($post_id, $input['name'], $gallery_images);
                    return;
                }
                update_post_meta($post_id, $input['name'], sanitize_text_field($_POST[$input['name']]));
            } else {
                delete_post_meta($post_id, $input['name']);
            }
        }
    }
    public function createForm()
    {
        /* input sample
        [
            [
                'name' => 'birth_date',
                'label' => 'Birth date',
                'type' => 'date'
            ],
            [
                'name' => 'freelance',
                'label' => 'Freelance',
                'type' => 'select',
                'options' =>
                [
                    ['label' => 'Available', 'value' => 'available'],
                    ['label' => 'Not Available', 'value' => 'not_available']
                ]
            ],
            [
                'name' => 'residence',
                'label' => 'Residence',
                'type' => 'text'
            ]
        ]
        */
        $html = "";
        foreach ($this->inputs as $input) {
            if ($input['type'] == "text" || $input['type'] == "date") {
                $value =  esc_attr(get_post_meta($this->postID, $input['name'], true));
                $html .= <<<EOT
                <div>
                <label for="{$input['name']}">{$input['label']}</label>
                <input type="{$input['type']}" id="{$input['name']}" name="{$input['name']}" value="{$value}" >
                </div>
                EOT;
            }
            if ($input['type'] == "select") {
                $html .= <<<EOT
                <div>
                <label for="{$input['name']}">{$input['label']}</label>
                <select id="{$input['name']}" name="{$input['name']}">
EOT;
                foreach ($input['options'] as $option) {
                    $selected = selected(get_post_meta($this->postID, $input['name'], true), $option['value']);
                    $html .= <<<EOT
                <option value="{$option['value']}" {$selected}>{$option['label']}</option>
EOT;
                }
                $html .= "</select></div>";
            }
            if ($input['type'] == "gallery") {
                $gallery_images = get_post_meta($this->postID, $input['name'], true);
                $html .= "<input data-name='{$input['name']}' type='button' id='upload_gallery_button' class='button' value='{$input['label']}' />";
                $html .= '<div id="hfcv_gallery_images_preview" class="hfcv_gallery_images_preview">';
                if (!empty($gallery_images)) {
                    foreach ($gallery_images as $image_id) {
                        $image_url = wp_get_attachment_url($image_id);
                        $removeLabel = __("X", "hfcv");
                        $html .= <<<EOT
                        <div class="gallery_image">
                        <img src="{$image_url}" alt="" width="100" height="100" />
                        <input type="hidden" name="{$input['name']}[]" value="{$image_id}" />
                        <button class="remove_gallery_image">{$removeLabel}</button>
                        </div>
EOT;
                    }
                }
                $html .= "</div>";
            }
            $html .= "<br>";
        }
        $this->form = $html;
    }
    public function getInputs()
    {
        return $this->inputs;
    }
    public function displayForm()
    {
        echo $this->form;
    }
}
