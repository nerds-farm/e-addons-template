<?php
namespace EAddonsTemplate\Modules\Template\Tags;

use EAddonsForElementor\Core\Utils;
use EAddonsForElementor\Base\Base_Tag;
use \Elementor\Controls_Manager;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Template extends Base_Tag {

    public function get_name() {
        return 'e-tag-template';
    }
    
    public function get_icon() {
        return 'eadd-dynamic-tag-template';
    }
    
    public function get_pid() {
        return 211;
    }

    public function get_title() {
        return __('Template', 'e-addons');
    }

    public function get_categories() {
        return [
            \Elementor\Modules\DynamicTags\Module::BASE_GROUP,
            \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY,
        ];
    }

    /**
     * Register Controls
     *
     * Registers the Dynamic tag controls
     *
     * @since 2.0.0
     * @access protected
     *
     * @return void
     */
    protected function register_controls() {

        $this->add_control(
                'e_template',
                [
                    'label' => __('Template', 'e-addons'),
                    'type' => 'e-query',
                    'placeholder' => __('Select Template', 'e-addons'),
                    'label_block' => true,
                    'query_type' => 'posts',
                    'object_type' => 'elementor_library',                    
                ]
        );
        
        $this->add_control(
                'e_template_post_id',
                [
                    'label' => __('Post', 'e-addons'),
                    'type' => 'e-query',
                    'placeholder' => __('Set Post', 'e-addons'),
                    'label_block' => true,
                    'query_type' => 'posts',
                    'separator' => 'before',
                ]
        );
        
        $this->add_control(
                'e_template_term_id',
                [
                    'label' => __('Term', 'e-addons'),
                    'type' => 'e-query',
                    'placeholder' => __('Set Term', 'e-addons'),
                    'label_block' => true,
                    'query_type' => 'terms',
                ]
        );
        
        $this->add_control(
                'e_template_user_id',
                [
                    'label' => __('User', 'e-addons'),
                    'type' => 'e-query',
                    'placeholder' => __('Set User', 'e-addons'),
                    'label_block' => true,
                    'query_type' => 'users',
                ]
        );
        
        $this->add_control(
                'e_template_author_id',
                [
                    'label' => __('Author', 'e-addons'),
                    'type' => 'e-query',
                    'placeholder' => __('Set Author', 'e-addons'),
                    'label_block' => true,
                    'query_type' => 'users',
                ]
        );
        
        $this->add_control(
                'e_template_inline_css',
                [
                    'label' => __('Inline CSS', 'e-addons'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                ]
        );
        
        Utils::add_help_control($this);
    }

    public function render() {
        $settings = $this->get_settings_for_display();
        if (empty($settings))
            return;
        
        if (!empty($settings['e_template'])) {

            $args = array();
            
            if ($settings['e_template_inline_css']) {                
                $args['css'] = true;
            }

            if (!empty($settings['e_template_post_id'])) {
                $args['post_id'] = $settings['e_template_post_id'];
            }
            if (!empty($settings['e_template_term_id'])) {
                $args['term_id'] = $settings['e_template_term_id'];
            }
            if (!empty($settings['e_template_user_id'])) {
                $args['user_id'] = $settings['e_template_user_id'];
            }
            if (!empty($settings['e_template_author_id'])) {
                $args['author_id'] = $settings['e_template_author_id'];
            }

            if (!empty($settings['e_template'])) {               
                $template = \EAddonsForElementor\Core\Managers\Template::e_template($settings['e_template'], $args);
                echo Utils::get_dynamic_data($template);
            }
        }                
    }

}
