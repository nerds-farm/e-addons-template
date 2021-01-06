<?php

namespace EAddonsTemplate\Modules\Template\Skins;

use EAddonsForElementor\Base\Base_Skin;
use EAddonsForElementor\Core\Utils;
use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Group_Control_Box_Shadow;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

class Posts_Template extends Base_Skin {

    public function __construct($parent = []) {

        if (!empty($parent)) {
            parent::__construct($parent);
        }
        $this->_register_controls_actions();

        // Add a custom skin for the POSTS widget
        add_action('elementor/widget/posts/skins_init', function($widget) {
            $widget->add_skin($this);
        });
        // Add a custom skin for the POST Archive widget
        add_action('elementor/widget/archive-posts/skins_init', function($widget) {
            $widget->add_skin($this);
        });
    }

    public function _register_controls_actions() {
        //parent::_register_controls_actions();
        add_action('elementor/element/posts/section_layout/before_section_end', [$this, 'register_controls']);
        add_action('elementor/element/posts/section_query/after_section_end', [$this, 'register_style_sections']);
        add_action('elementor/element/archive-posts/section_layout/before_section_end', [$this, 'register_controls']);
        add_action('elementor/element/archive-posts/section_layout/after_section_end', [$this, 'register_style_sections']);
    }

    public function get_id() {
        return 'template';
    }

    public function get_icon() {
        return 'eadd-posts-skin-template';
    }

    public function get_pid() {
        return 297;
    }

    public function get_title() {
        return __('Template', 'e-addons');
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
                    'separator' => 'after',
                ]
        );

        $this->register_columns_controls();
        if ($widget->get_name() == 'posts') {
            $this->register_post_count_control();
        }
    }

    public function register_style_sections(Widget_Base $widget) {
        $this->parent = $widget;
        //$controls = $widget->get_controls();
        //if (empty($controls['template_column_gap'])) {
        $this->register_design_controls();
        //}
    }

    public function register_design_controls() {
        $this->register_design_layout_controls();
        $this->register_additional_design_controls();
        //$this->register_design_image_controls();
        //$this->register_design_content_controls();
    }

    public function render_post() {
        $settings = $this->parent->get_settings_for_display();
        //var_dump($settings);
        if (!empty($settings['template_id'])) {
            $args = array(
                'post_id' => get_the_ID(),
                'css' => false
            );
            $this->render_post_header();
            $template_html = \EAddonsForElementor\Core\Managers\Template::e_template($settings['template_id'], $args);
            echo $template_html;
            $this->render_post_footer();
        }
    }

    /*     * ************************ SKIN-BASE methods ************************
     * Credits Elementor PRO
     * /elementor-pro/modules/posts/skins/skin-base.php
     * I copy them to remove Base_Skin dependencies
     */

    protected function register_columns_controls() {
        $this->add_responsive_control(
                'columns',
                [
                    'label' => __('Columns', 'elementor-pro'),
                    'type' => Controls_Manager::SELECT,
                    'default' => '3',
                    'tablet_default' => '2',
                    'mobile_default' => '1',
                    'options' => [
                        '1' => '1',
                        '2' => '2',
                        '3' => '3',
                        '4' => '4',
                        '5' => '5',
                        '6' => '6',
                    ],
                    'prefix_class' => 'elementor-grid%s-',
                    'frontend_available' => true,
                ]
        );
    }

    protected function register_post_count_control() {
        $this->add_control(
                'posts_per_page',
                [
                    'label' => __('Posts Per Page', 'elementor-pro'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 6,
                ]
        );
    }

    protected function register_design_layout_controls() {
        $this->start_controls_section(
                'section_design_layout',
                [
                    'label' => __('Layout', 'elementor-pro'),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
        );

        $this->add_control(
                'column_gap',
                [
                    'label' => __('Columns Gap', 'elementor-pro'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 30,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}}' => '--grid-column-gap: {{SIZE}}{{UNIT}}',
                    ],
                ]
        );

        $this->add_control(
                'row_gap',
                [
                    'label' => __('Rows Gap', 'elementor-pro'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 35,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'frontend_available' => true,
                    'selectors' => [
                        '{{WRAPPER}}' => '--grid-row-gap: {{SIZE}}{{UNIT}}',
                    ],
                ]
        );

        $this->add_control(
                'alignment',
                [
                    'label' => __('Alignment', 'elementor-pro'),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => __('Left', 'elementor-pro'),
                            'icon' => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => __('Center', 'elementor-pro'),
                            'icon' => 'eicon-text-align-center',
                        ],
                        'right' => [
                            'title' => __('Right', 'elementor-pro'),
                            'icon' => 'eicon-text-align-right',
                        ],
                    ],
                    'prefix_class' => 'elementor-posts--align-',
                ]
        );

        $this->end_controls_section();
    }
    
    public function register_additional_design_controls() {
		$this->start_controls_section(
			'section_design_box',
			[
				'label' => __( 'Box', 'elementor-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
                
                $this->add_control(
			'box_element_width',
			[
				'label' => __( 'Box Width', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
                                'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-post > .elementor' => 'width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'box_border_width',
			[
				'label' => __( 'Border Width', 'elementor-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-post' => 'border-style: solid; border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'box_border_radius',
			[
				'label' => __( 'Border Radius', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-post' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'box_padding',
			[
				'label' => __( 'Padding', 'elementor-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-post' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->start_controls_tabs( 'bg_effects_tabs' );

		$this->start_controls_tab( 'classic_style_normal',
			[
				'label' => __( 'Normal', 'elementor-pro' ),
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'selector' => '{{WRAPPER}} .elementor-post',
			]
		);

		$this->add_control(
			'box_bg_color',
			[
				'label' => __( 'Background Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-post' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'box_border_color',
			[
				'label' => __( 'Border Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-post' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'classic_style_hover',
			[
				'label' => __( 'Hover', 'elementor-pro' ),
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow_hover',
				'selector' => '{{WRAPPER}} .elementor-post:hover',
			]
		);

		$this->add_control(
			'box_bg_color_hover',
			[
				'label' => __( 'Background Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-post:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'box_border_color_hover',
			[
				'label' => __( 'Border Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-post:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

    public function render() {
        $this->parent->query_posts();

        /** @var \WP_Query $query */
        $query = $this->parent->get_query();

        if (!$query->found_posts) {
            return;
        }

        $this->render_loop_header();

        // It's the global `wp_query` it self. and the loop was started from the theme.
        if ($query->in_the_loop) {
            $this->current_permalink = get_permalink();
            $this->render_post();
        } else {
            while ($query->have_posts()) {
                $query->the_post();

                $this->current_permalink = get_permalink();
                $this->render_post();
            }
        }

        wp_reset_postdata();

        $this->render_loop_footer();
    }

    protected function render_loop_header() {
        $classes = [
            'elementor-posts-container',
            'elementor-posts',
            $this->get_container_class(),
        ];

        /** @var \WP_Query $wp_query */
        $wp_query = $this->parent->get_query();

        // Use grid only if found posts.
        if ($wp_query->found_posts) {
            $classes[] = 'elementor-grid';
        }

        $this->parent->add_render_attribute('container', [
            'class' => $classes,
        ]);
        ?>
        <div <?php echo $this->parent->get_render_attribute_string('container'); ?>>
            <?php
        }

        protected function render_loop_footer() {
            ?>
        </div>
        <?php
        $parent_settings = $this->parent->get_settings();
        if ('' === $parent_settings['pagination_type']) {
            return;
        }

        $page_limit = $this->parent->get_query()->max_num_pages;
        if ('' !== $parent_settings['pagination_page_limit']) {
            $page_limit = min($parent_settings['pagination_page_limit'], $page_limit);
        }

        if (2 > $page_limit) {
            return;
        }

        $this->parent->add_render_attribute('pagination', 'class', 'elementor-pagination');

        $has_numbers = in_array($parent_settings['pagination_type'], ['numbers', 'numbers_and_prev_next']);
        $has_prev_next = in_array($parent_settings['pagination_type'], ['prev_next', 'numbers_and_prev_next']);

        $links = [];

        if ($has_numbers) {
            $paginate_args = [
                'type' => 'array',
                'current' => $this->parent->get_current_page(),
                'total' => $page_limit,
                'prev_next' => false,
                'show_all' => 'yes' !== $parent_settings['pagination_numbers_shorten'],
                'before_page_number' => '<span class="elementor-screen-only">' . __('Page', 'elementor-pro') . '</span>',
            ];

            if (is_singular() && !is_front_page()) {
                global $wp_rewrite;
                if ($wp_rewrite->using_permalinks()) {
                    $paginate_args['base'] = trailingslashit(get_permalink()) . '%_%';
                    $paginate_args['format'] = user_trailingslashit('%#%', 'single_paged');
                } else {
                    $paginate_args['format'] = '?page=%#%';
                }
            }

            $links = paginate_links($paginate_args);
        }

        if ($has_prev_next) {
            $prev_next = $this->parent->get_posts_nav_link($page_limit);
            array_unshift($links, $prev_next['prev']);
            $links[] = $prev_next['next'];
        }
        ?>
        <nav class="elementor-pagination" role="navigation" aria-label="<?php esc_attr_e('Pagination', 'elementor-pro'); ?>">
        <?php echo implode(PHP_EOL, $links); ?>
        </nav>
            <?php
        }

        public function get_container_class() {
            return 'elementor-posts--skin-' . $this->get_id();
        }

    }
    