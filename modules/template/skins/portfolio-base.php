<?php

namespace EAddonsTemplate\Modules\Template\Skins;

use EAddonsForElementor\Base\Base_Skin;
use EAddonsForElementor\Core\Utils;
use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Group_Control_Image_Size;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

class Portfolio_Base extends Base_Skin {
    
    public function __construct($parent = []) {

        if (!empty($parent)) {
            parent::__construct($parent);
        }
        //$this->_register_controls_actions();

        // Add a custom skin for the PORTFOLIO widget
        add_action('elementor/widget/portfolio/skins_init', function($widget) {
            $widget->add_skin($this);
        });
        add_action( 'elementor/element/portfolio/section_layout/before_section_end', [$this, 'register_controls'] );
        add_action( 'elementor/element/portfolio/section_layout/before_section_end', [ $this, 'remove_controls' ] );
    }
    
    public function get_id() {
        return 'default';
    }

    public function get_icon() {
        return 'eadd-portfolio-skinbase-template';
    }

    public function get_pid() {
        return 3468;
    }

    public function get_title() {
        return __('Portfolio', 'e-addons');
    }
    
    public function get_label() {
        return __('Portfolio Classic', 'e-addons');
    }
    
    public function show_in_settings() {
        return false;
    }
    
    public function remove_controls(Widget_Base $widget) { 
        
        //$widget->remove_control('masonry');
        //$widget->remove_control('item_ratio');
        
        $widget->remove_control('thumbnail_size_size');
        //$widget->remove_control('show_title');
        //$widget->remove_control('title_tag');
    }
    
    public function register_controls(Widget_Base $widget) { 
        $widget->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                        'name' => 'skin_thumbnail_size',
                        'exclude' => [ 'custom' ],
                        'default' => 'medium',
                        'prefix_class' => 'elementor-portfolio--thumbnail-size-',
                        'condition' => [
                                '_skin' => 'default',
                        ],
                ]
        );
        /*$widget->add_control(
                'skin_show_title',
                [
                        'label' => __( 'Show Title', 'elementor-pro' ),
                        'type' => Controls_Manager::SWITCHER,
                        'default' => 'yes',
                        'label_off' => __( 'Off', 'elementor-pro' ),
                        'label_on' => __( 'On', 'elementor-pro' ),
                        'condition' => [
                                '_skin' => 'portfolio',
                        ],
                ]
        );

        $widget->add_control(
                'skin_title_tag',
                [
                        'label' => __( 'Title HTML Tag', 'elementor-pro' ),
                        'type' => Controls_Manager::SELECT,
                        'options' => [
                                'h1' => 'H1',
                                'h2' => 'H2',
                                'h3' => 'H3',
                                'h4' => 'H4',
                                'h5' => 'H5',
                                'h6' => 'H6',
                                'div' => 'div',
                                'span' => 'span',
                                'p' => 'p',
                        ],
                        'default' => 'h3',
                        'condition' => [
                                'skin_show_title' => 'yes',
                                '_skin' => 'portfolio',
                        ],
                ]
        );*/
    }
    public function register_style_sections(Widget_Base $widget) { }
    
    public function render() {
            $this->parent->query_posts();

            $wp_query = $this->parent->get_query();

            if ( ! $wp_query->found_posts ) {
                    return;
            }

            $this->get_posts_tags();

            $this->render_loop_header();

            while ( $wp_query->have_posts() ) {
                    $wp_query->the_post();

                    $this->render_post();
            }

            $this->render_loop_footer();

            wp_reset_postdata();
    }
    
    protected function render_post() {
            $this->render_post_header();
            $this->render_thumbnail();
            $this->render_overlay_header();
            $this->render_title();
            // $this->render_categories_names();
            $this->render_overlay_footer();
            $this->render_post_footer();
    }
    
    

}
