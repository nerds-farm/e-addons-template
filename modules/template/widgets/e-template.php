<?php

namespace EAddonsTemplate\Modules\Template\Widgets;

use EAddonsForElementor\Base\Base_Widget;
use EAddonsForElementor\Core\Utils;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Elementor Template
 *
 */
class E_Template extends Base_Widget {

    public function get_name() {
        return 'e-template';
    }
    
    public function get_pid() {
        return 212;
    }

    public function get_title() {
        return __('E-Template', 'e-addons');
    }

    public function get_icon() {
        return 'eadd-widget-template';
    }

    public function get_description() {
        return __('Include every element of your site in a template without having to redo it');
    }
    
    public function get_categories() {
        return [ 'e-addons' ];
    }
    
    protected function _register_controls() {
        $this->start_controls_section(
                'section_template', [
            'label' => __('Template', 'e-addons'),
                ]
        );

        $this->add_control(
                'template_id',
                [
                    'label' => __('Select Template', 'e-addons'),
                    'type' => 'e-query',
                    'placeholder' => __('Template Name', 'e-addons'),
                    'label_block' => true,
                    'query_type' => 'posts',
                    'object_type' => 'elementor_library',
                    'separator' => 'after',
                ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'section_template_other', [
                'label' => __('Set different Sources', 'e-addons'),
            ]
        );
        $this->add_control(
            'post_id',
            [
                'label' => __('Select other Post', 'e-addons'),
                'type' => 'e-query',
                'placeholder' => __('Post Title', 'e-addons'),
                'label_block' => true,
                'query_type' => 'posts',
            ]
        );
        
        $this->add_control(
            'term_id',
            [
                'label' => __('Select other Term', 'e-addons'),
                'type' => 'e-query',
                'placeholder' => __('Term Title', 'e-addons'),
                'label_block' => true,
                'query_type' => 'terms',
            ]
        );
        
        $this->add_control(
                'user_id',
                [
                    'label' => __('Select other User', 'e-addons'),
                    'type' => 'e-query',
                    'placeholder' => __('Force User content', 'e-addons'),
                    'label_block' => true,
                    'query_type' => 'users',
                ]
        );
        $this->add_control(
                'other_author_id',
                [
                    'label' => __('Select other Author', 'e-addons'),
                    'type' => 'e-query',
                    'placeholder' => __('Force Author content', 'e-addons'),
                    'label_block' => true,
                    'query_type' => 'users',
                ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'section_template_options', [
                'label' => __('Options', 'e-addons'),
            ]
        );
        $this->add_control(
            'ajax',
            [
                'label' => __('Ajax', 'e-addons'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );
        

        $this->end_controls_section();
    }

    protected function render() {

        $settings = $this->get_settings_for_display();
        if (empty($settings))
            return;

        $args = array();
        if (Utils::is_preview(true)) {                   
            $args['css'] = true;
        }

        if (!empty($settings['post_id'])) {
            $args['post_id'] = $settings['post_id'];
        }
        if (!empty($settings['user_id'])) {
            $args['user_id'] = $settings['user_id'];
        }
        if (!empty($settings['author_id'])) {
            $args['author_id'] = $settings['author_id'];
        }
        if (!empty($settings['term_id'])) {
            $args['term_id'] = $settings['term_id'];
        }
        
        if (!empty($settings['ajax'])) {
            $args['ajax'] = true;
            $args['loading'] = 'lazy';
        }
        
        if (!empty($settings['template_id'])) {               
            echo \EAddonsForElementor\Core\Managers\Template::e_template($settings['template_id'], $args);
        }
    }

}