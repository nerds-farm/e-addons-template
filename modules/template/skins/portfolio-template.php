<?php

namespace EAddonsTemplate\Modules\Template\Skins;

use EAddonsForElementor\Base\Base_Skin;
use EAddonsForElementor\Core\Utils;
use Elementor\Controls_Manager;
use Elementor\Widget_Base;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

class Portfolio_Template extends Base_Skin {

    public function __construct($parent = []) {

        if (!empty($parent)) {
            parent::__construct($parent);
        }
        $this->_register_controls_actions();

        // Add a custom skin for the PORTFOLIO widget
        add_action('elementor/widget/portfolio/skins_init', function($widget) {
            $widget->add_skin($this);
        });
    }

    public function _register_controls_actions() {
        //parent::_register_controls_actions();
        add_action('elementor/element/portfolio/section_layout/before_section_end', [$this, 'register_controls']);
        add_action('elementor/element/portfolio/section_design_overlay/after_section_end', [$this, 'register_style_sections']);
        
        //add_action('elementor/element/portfolio/section_design_overlay/before_section_start', [ $this, 'add_section_condition' ], 10, 2);
    }

    public function get_id() {
        return 'template';
    }

    public function get_icon() {
        return 'eadd-portfolio-skin-template';
    }

    public function get_pid() {
        return 3468;
    }

    public function get_title() {
        return __('Template', 'e-addons');
    }
    
    public function get_label() {
        return __('Portfolio Template', 'e-addons');
    }

    public function register_controls(Widget_Base $widget) {
        $this->parent = $widget;

        $controls = $widget->get_controls();
        if ($controls) {
            if (!empty($controls['template_id'])) {
                return false;
            }
        }

        $this->add_control(
                'id',
                [
                    'label' => __('Select Template', 'e-addons'),
                    'type' => 'e-query',
                    'placeholder' => __('Template Name', 'e-addons'),
                    'label_block' => true,
                    'query_type' => 'posts',
                    'object_type' => 'elementor_library',
                    'separator' => 'before',
                ]
        );
        $this->add_control(
                'no_overlay',
                [
                    'label' => __('Remove Overlay', 'e-addons'),
                    'type' => Controls_Manager::SWITCHER,
                    /*'selectors' => [
                        '{{WRAPPER}} .elementor-post__thumbnail__link' => 'display: none;',
                    ],*/
                ]
        );

        /* $this->register_columns_controls();
          if ($widget->get_name() == 'posts') {
          $this->register_post_count_control();
          } */
        
        $show_title = $widget->get_controls('show_title');
        $show_title['condition']['template_no_overlay'] = '';
        $widget->update_control('show_title', $show_title);
        
        $title_tag = $widget->get_controls('title_tag');
        $title_tag['condition']['template_no_overlay'] = '';
        $widget->update_control('title_tag', $title_tag);
        
        $item_ratio = $widget->get_controls('item_ratio');
        $item_ratio['condition']['_skin'] = 'default';
        $widget->update_control('item_ratio', $item_ratio);
        
        //$section_design_overlay = $widget->get_controls('section_design_overlay');
        //var_dump($section_design_overlay); die();
        
    }
    public function add_section_condition(Widget_Base $widget, $args) {
        
    }
    

    public function register_style_sections(Widget_Base $widget) {
        $this->parent = $widget;
        $color_background = $widget->get_controls('color_background');
        $color_background['selectors']['{{WRAPPER}} a .elementor-portfolio-item__overlay'] = 'background-color: {{VALUE}} !important;';
        //$color_background['selectors']["{{WRAPPER}} .elementor-portfolio-item__overlay a"] = reset($color_background['selectors']);
        $widget->update_control('color_background', $color_background); //, ['recursive' => true,]);
    }

    public function render_post() {
        $settings = $this->parent->get_settings_for_display();
        $this->render_post_header();                        
        if (!empty($settings['template_id'])) {
            $args = array(
                'post_id' => get_the_ID(),
                'css' => false
            );
            $template_html = \EAddonsForElementor\Core\Managers\Template::e_template($settings['template_id'], $args);
            echo $template_html;
        } else {
            $this->render_thumbnail();
        }
        if (empty($settings['template_no_overlay'])) {
            $this->render_overlay_header(true);
            $this->render_title();
            // $this->render_categories_names();
            $this->render_overlay_footer();
        }
        $this->render_post_footer();
    }

    public function render() {
        $this->parent->query_posts();

        $wp_query = $this->parent->get_query();
        
        if (!$wp_query->found_posts) {
            return;
        }
        
        $this->get_posts_tags();

        $this->render_loop_header();
        
        while ($wp_query->have_posts()) {
            $wp_query->the_post();

            $this->render_post();
        }

        $this->render_loop_footer();
        
        wp_reset_postdata();
        
        wp_enqueue_script('e-addons-template-portfolio');
    }

}
