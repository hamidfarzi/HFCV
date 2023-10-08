<?php

/**
 * customizer support 
 * @package hfcv
 */

namespace HFCV;

use WP_Customize_Color_Control;
use WP_Customize_Control;

class Customizer
{
    private $style_variables = [];
    private $content_variables = [];

    public function __construct()
    {
    }

    public function register($wp_customize)
    {
        $this->setup_section($this->style_variables, $wp_customize, 'Color', 'color', WP_Customize_Color_Control::class);
        $this->setup_section($this->style_variables, $wp_customize, 'Layout', 'layout', WP_Customize_Control::class);
        $this->setup_section($this->style_variables, $wp_customize, 'Typograpphy', 'text', WP_Customize_Control::class);
        $this->setup_section($this->style_variables, $wp_customize, 'Animations', 'animations', WP_Customize_Control::class);
        $this->setup_section($this->content_variables, $wp_customize, 'Content', 'content', WP_Customize_Control::class);
    }

    public function set_style_variables(array $cssVariables)
    {
        foreach ($cssVariables[':root'] as $key => $value) {
            $item = $this->parse_variable_slug($key);
            $this->style_variables[$item['section']][] =
                [
                    'property' => $key,
                    'value' => $value,
                    'title' =>  $item['title']
                ];
        }
    }
    public function set_content_variables(array $contentVariables)
    {
        foreach ($contentVariables as $key => $value) {
            $item = $this->parse_variable_slug($key);
            $this->content_variables[$item['section']][] =
                [
                    'property' => $key,
                    'value' => $value,
                    'title' =>  $item['title']
                ];
        }
    }

    private function parse_variable_slug($input)
    {
        $words = explode('-', $input);
        $section = $words[3];
        $words = array_slice($words, 4);
        $output = implode(' ', $words);
        $output = ucfirst($output);
        $output = str_replace('-', ' ', $output);

        return  [
            'section' => $section,
            'title' => $output
        ];
    }

    private function setup_section($collection, $wp_customize, $section_title, $section_slug, $control_type)
    {
        $wp_customize->add_section($section_slug, array(
            'title' => __($section_title, 'hfcv'),
            'priority' => 30,
        ));


        foreach ($collection[$section_slug] as $item) {
            // Add color settings
            $wp_customize->add_setting($item['property'], array(
                'default' => $item['value'],
                'transport' => 'refresh',
            ));

            $wp_customize->add_control(new $control_type($wp_customize, $item['property'], array(
                'label' => __($item['title'], 'hfcv'),
                'section' => $section_slug,
                'settings' => $item['property'],
            )));
        }
    }
}
