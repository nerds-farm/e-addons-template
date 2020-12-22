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
            'base', //\Elementor\Modules\DynamicTags\Module::BASE_GROUP
            'text', //\Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY,
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
                    'placeholder' => __('Template Name', 'e-addons'),
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
                    'placeholder' => __('Force Post content', 'e-addons'),
                    'label_block' => true,
                    'query_type' => 'posts',
                    'separator' => 'before',
                    'condition' => [
                        'e_template!' => '',
                    ],
                ]
        );
        
        $this->add_control(
                'e_template_user_id',
                [
                    'label' => __('User', 'e-addons'),
                    'type' => 'e-query',
                    'placeholder' => __('Force User content', 'e-addons'),
                    'label_block' => true,
                    'query_type' => 'users',
                    'condition' => [
                        'e_template!' => '',
                    ],
                ]
        );
        
        $this->add_control(
                'e_template_author_id',
                [
                    'label' => __('Author', 'e-addons'),
                    'type' => 'e-query',
                    'placeholder' => __('Force Author content', 'e-addons'),
                    'label_block' => true,
                    'query_type' => 'users',
                    'condition' => [
                        'e_template!' => '',
                    ],
                ]
        );
        
        $this->add_control(
                'e_template_inline_css',
                [
                    'label' => __('Inline CSS', 'e-addons'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'condition' => [
                        'e_template!' => '',
                    ],
                ]
        );
        
        $this->add_control(
                'e_template_code',
                [
                    'label' => __('Show shortcode', 'e-addons'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'condition' => [
                        'e_template!' => '',
                    ],
                ]
        );
        
        Utils::add_help_control($this);
    }

    public function render() {
        $settings = $this->get_settings_for_display();
        if (empty($settings))
            return;
        
        if (!empty($settings['e_template'])) {
            $template = '[e-addons-template id="'.$settings['e_template'].'"';
            
            if ($settings['e_template_post_id']) {
                $template .= ' post_id="'.$settings['e_template_post_id'].'"';
            }
            if ($settings['e_template_user_id']) {
                $template .= ' user_id="'.$settings['e_template_user_id'].'"';
            }
            if ($settings['e_template_author_id']) {
                $template .= ' author_id="'.$settings['e_template_author_id'].'"';
            }
            if ($settings['e_template_inline_css']) {
                $template .= ' css="true"';
            }
            
            $template .= ']';

            if ($settings['e_template_code']) {
                echo $template; 
                return;
            }
            
            $template = do_shortcode($template);
            echo Utils::get_dynamic_data($template);
        }                
    }

}
