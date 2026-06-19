<?php
/**
 * Customizer settings.
 *
 * @param array $settings Array of customizer settings.
 * @return array Modified array of customizer settings.
 */
function blogsy_news_customizer_settings( $settings ) {
	$settings = blogsy_news_customizer_header_settings( $settings );
	$settings = blogsy_news_hero_settings( $settings );
	return $settings;
}

add_filter( 'blogsy_customize_settings', 'blogsy_news_customizer_settings', 11 );

/**
 * Header settings.
 *
 * @param array $settings Array of customizer settings.
 * @return array Modified array of customizer settings.
 */
function blogsy_news_customizer_header_settings( $settings ) {
	$settings['blogsy_header_section']['blogsy_header_navigation_heading_widgets'] = [
		'transport'         => 'postMessage',
		'sanitize_callback' => 'blogsy_sanitize_toggle',
		'type'              => 'blogsy-heading',
		'label'             => esc_html__( 'Header Navigation Widgets', 'blogsy-news' ),
		'description'       => esc_html__( 'Click the "Add Widget" button to add available widgets to your Header navigation. Click the down arrow icon to expand widget options.', 'blogsy-news' ),
		'space'             => true,
		'priority'          => 51,
		'required'          => [
			[
				'control'  => 'blogsy_header_present',
				'operator' => '==',
				'value'    => 'prebuild',
			],
			[
				'control'  => 'blogsy_header_layout',
				'operator' => '==',
				'value'    => 'layout-4',
			],
		],
	];

	$settings['blogsy_header_section']['blogsy_header_navigation_widgets'] = [
		'transport'         => 'postMessage',
		'sanitize_callback' => 'blogsy_sanitize_widget',
		'type'              => 'blogsy-widget',
		'label'             => esc_html__( 'Header Navigation Widgets', 'blogsy-news' ),
		'priority'          => 51,
		'widgets'           => apply_filters(
			'blogsy_main_header_navigation_widgets',
			[
				'search'    => [
					'max_uses' => 1,
					'styles'   => apply_filters(
						'blogsy_header_search_widget_styles',
						[
							4 => esc_html__( 'Inline', 'blogsy-news' ),
							3 => esc_html__( 'Expand', 'blogsy-news' ),
						]
					),
				],
				'darkmode'  => [
					'max_uses' => 1,
				],
				'offcanvas' => [
					'max_uses' => 1,
				],
				'button'    => [
					'max_uses' => 4,
				],
				'socials'   => [
					'max_uses' => 2,
					'styles'   => [
						'minimal'        => esc_html__( 'Minimal', 'blogsy-news' ),
						'minimal-fill'   => esc_html__( 'Minimal Fill', 'blogsy-news' ),
						'rounded-border' => esc_html__( 'Rounded Border', 'blogsy-news' ),
						'rounded-fill'   => esc_html__( 'Rounded Fill', 'blogsy-news' ),
					],
					'sizes'    => [
						'small'    => esc_html__( 'Small', 'blogsy-news' ),
						'standard' => esc_html__( 'Standard', 'blogsy-news' ),
						'large'    => esc_html__( 'Large', 'blogsy-news' ),
						'xlarge'   => esc_html__( 'Extra Large', 'blogsy-news' ),
					],
				],
				'text'      => [
					'max_uses' => 4,
				],
			]
		),
		'locations'         => [
			'left'  => esc_html__( 'Left', 'blogsy-news' ),
			'right' => esc_html__( 'Right', 'blogsy-news' ),
		],
		'visibility'        => [
			'all'                => esc_html__( 'Show on All Devices', 'blogsy-news' ),
			'hide-mobile'        => esc_html__( 'Hide on Mobile', 'blogsy-news' ),
			'hide-tablet'        => esc_html__( 'Hide on Tablet', 'blogsy-news' ),
			'hide-mobile-tablet' => esc_html__( 'Hide on Mobile and Tablet', 'blogsy-news' ),
		],
		'required'          => [
			[
				'control'  => 'blogsy_header_present',
				'operator' => '==',
				'value'    => 'prebuild',
			],
			[
				'control'  => 'blogsy_header_layout',
				'operator' => '==',
				'value'    => 'layout-4',
			],
			[
				'control'  => 'blogsy_header_navigation_heading_widgets',
				'value'    => true,
				'operator' => '==',
			],
			[
				'control'  => 'blogsy_site_header',
				'value'    => '0',
				'operator' => '==',
			],
			[
				'control'  => 'blogsy_single_post_header',
				'value'    => '0',
				'operator' => '==',
			],
			[
				'control'  => 'blogsy_site_sticky_header',
				'value'    => '0',
				'operator' => '==',
			],
			[
				'control'  => 'blogsy_single_post_sticky_header',
				'value'    => '0',
				'operator' => '==',
			],
		],
		'partial'           => [
			'selector'            => '.pt-header-inner',
			'render_callback'     => 'blogsy_header_content_output',
			'container_inclusive' => true,
			'fallback_refresh'    => true,
		],
	];

	// Design options heading.
	$settings['blogsy_header_section']['blogsy_header_navigation_heading_design_options'] = [
		'transport'         => 'postMessage',
		'sanitize_callback' => 'blogsy_sanitize_toggle',
		'type'              => 'blogsy-heading',
		'label'             => esc_html__( 'Navigation Design Options', 'blogsy-news' ),
		'priority'          => 61,
		'required'          => [
			[
				'control'  => 'blogsy_header_present',
				'value'    => 'prebuild',
				'operator' => '==',
			],
		],
	];

	// Background.
	$settings['blogsy_header_section']['blogsy_header_navigation_background'] = [
		'transport'         => 'postMessage',
		'sanitize_callback' => 'blogsy_sanitize_design_options',
		'type'              => 'blogsy-design-options',
		'label'             => esc_html__( 'Background', 'blogsy-news' ),
		'priority'          => 61,
		'display'           => [
			'background' => [
				'color'    => esc_html__( 'Solid Color', 'blogsy-news' ),
				'gradient' => esc_html__( 'Gradient', 'blogsy-news' ),
			],
		],
		'required'          => [
			[
				'control'  => 'blogsy_header_present',
				'value'    => 'prebuild',
				'operator' => '==',
			],
			[
				'control'  => 'blogsy_header_heading_design_options',
				'value'    => true,
				'operator' => '==',
			],
		],
	];

	// Text Color.
	$settings['blogsy_header_section']['blogsy_header_navigation_text_color'] = [
		'transport'         => 'postMessage',
		'sanitize_callback' => 'blogsy_sanitize_design_options',
		'type'              => 'blogsy-design-options',
		'label'             => esc_html__( 'Font Color', 'blogsy-news' ),
		'priority'          => 61,
		'display'           => [
			'color' => [
				'text-color'        => esc_html__( 'Text Color', 'blogsy-news' ),
				'link-color'        => esc_html__( 'Link Color', 'blogsy-news' ),
				'link-hover-color'  => esc_html__( 'Link Hover Color', 'blogsy-news' ),
				'link-active-color' => esc_html__( 'Link Active Color', 'blogsy-news' ),
			],
		],
		'required'          => [
			[
				'control'  => 'blogsy_header_present',
				'value'    => 'prebuild',
				'operator' => '==',
			],
			[
				'control'  => 'blogsy_header_heading_design_options',
				'value'    => true,
				'operator' => '==',
			],
		],
	];

	// Border.
	$settings['blogsy_header_section']['blogsy_header_navigation_border'] = [
		'transport'         => 'postMessage',
		'sanitize_callback' => 'blogsy_sanitize_design_options',
		'type'              => 'blogsy-design-options',
		'label'             => esc_html__( 'Border', 'blogsy-news' ),
		'priority'          => 61,
		'display'           => [
			'border' => [
				'style'     => esc_html__( 'Style', 'blogsy-news' ),
				'color'     => esc_html__( 'Color', 'blogsy-news' ),
				'width'     => esc_html__( 'Width (px)', 'blogsy-news' ),
				'positions' => [
					'top'    => esc_html__( 'Top', 'blogsy-news' ),
					'right'  => esc_html__( 'Right', 'blogsy-news' ),
					'bottom' => esc_html__( 'Bottom', 'blogsy-news' ),
					'left'   => esc_html__( 'Left', 'blogsy-news' ),
				],
			],
		],
		'required'          => [
			[
				'control'  => 'blogsy_header_present',
				'value'    => 'prebuild',
				'operator' => '==',
			],
			[
				'control'  => 'blogsy_header_heading_design_options',
				'value'    => true,
				'operator' => '==',
			],
		],
	];

	// Ticker Type.
	$settings['blogsy_ticker_section']['blogsy_ticker_type'] = [
		'transport'         => 'refresh',
		'sanitize_callback' => 'blogsy_sanitize_select',
		'type'              => 'blogsy-select',
		'label'             => esc_html__( 'TIcker Layout', 'blogsy-news' ),
		'description'       => esc_html__( 'Choose ticker layout.', 'blogsy-news' ),
		'choices'           => [
			'layout-1' => esc_html__( 'Layout 1', 'blogsy-news' ),
			'layout-2' => esc_html__( 'Layout 2', 'blogsy-news' ),
		],
		'required'          => [
			[
				'control'  => 'blogsy_ticker_enable',
				'value'    => true,
				'operator' => '==',
			],
		],
	];

	return $settings;
}

/**
 * Hero Settings.
 *
 * @param array $settings Array of customizer settings.
 * @return array Modified array of customizer settings.
 *
 * @package Blogsy News
 * @author  Peregrine Themes <https://peregrinethemes.com>
 *
 * @since   1.0.0
 */
function blogsy_news_hero_settings( $settings ) {

	// Default post counts per position.
	$sections = [
		'editor'   => [
			'layout' => [ 'two' ],
		],
		'main'     => [
			'layout' => [ 'two', 'three' ],
		],
		'latest'   => [
			'layout' => [ 'three' ],
		],
		'popular'  => [
			'layout'    => [ 'three' ],
			'filter_by' => [ 'comment_count' => esc_html__( 'Most Commented', 'blogsy-news' ) ],
		],
		'update'   => [
			'layout' => [ 'three' ],
		],
		'trending' => [
			'layout' => [ 'three' ],
		],
	];

	$filter_by_options = apply_filters(
		'blogsy_news_banner_filter_by_options',
		[
			'category' => esc_html__( 'Category', 'blogsy-news' ),
			'tag'      => esc_html__( 'Tag', 'blogsy-news' ),
		]
	);

	// Hero Type.
	$settings['blogsy_hero_section']['blogsy_hero_type'] = [
		'transport'         => 'refresh',
		'sanitize_callback' => 'blogsy_sanitize_select',
		'type'              => 'blogsy-select',
		'label'             => esc_html__( 'Hero Layout', 'blogsy-news' ),
		'description'       => esc_html__( 'Choose hero layout.', 'blogsy-news' ),
		'choices'           => [
			'one'   => esc_html__( 'Layout 1', 'blogsy-news' ),
			'two'   => esc_html__( 'Layout 2', 'blogsy-news' ),
			'three' => esc_html__( 'Layout 3', 'blogsy-news' ),
		],
		'priority'          => 2,
		'required'          => [
			[
				'control'  => 'blogsy_hero_enable',
				'value'    => true,
				'operator' => '==',
			],
		],
	];

	foreach ( $sections  as $section => $args ) {
		$allowed_types = $args['layout'];
		$filter_by     = $args['filter_by'] ?? $filter_by_options;
		$settings['blogsy_hero_section'][ "blogsy_news_banner_{$section}_heading" ] = [
			'transport'         => 'postMessage',
			'sanitize_callback' => 'blogsy_sanitize_toggle',
			'type'              => 'blogsy-heading',
			'label'             => sprintf( esc_html__( '%s section', 'blogsy-news' ), ucfirst( $section ) ),
			'priority'          => 2,
			'required'          => [
				[
					'control'  => 'blogsy_hero_enable',
					'operator' => '==',
					'value'    => true,
				],
				[
					'control'  => 'blogsy_hero_type',
					'value'    => $allowed_types,
					'operator' => 'in',
				],
			],
		];

		$settings['blogsy_hero_section'][ "blogsy_news_banner_{$section}_label" ] = [
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_text_field',
			'type'              => 'blogsy-text',
			'label'             => esc_html__( 'Section Label', 'blogsy-news' ),
			'priority'          => 2,
			'required'          => [
				[
					'control'  => 'blogsy_hero_enable',
					'value'    => true,
					'operator' => '==',
				],
				[
					'control'  => 'blogsy_hero_type',
					'value'    => $allowed_types,
					'operator' => 'in',
				],
			],
		];

		$settings['blogsy_hero_section'][ "blogsy_news_banner_{$section}_filter_by" ] = [
			'transport'         => 'refresh',
			'sanitize_callback' => 'blogsy_sanitize_select',
			'type'              => 'blogsy-select',
			'label'             => esc_html__( 'Filter posts by', 'blogsy-news' ),
			'priority'          => 2,
			'choices'           => $filter_by,
			'required'          => [
				[
					'control'  => 'blogsy_hero_enable',
					'operator' => '==',
					'value'    => true,
				],
				[
					'control'  => 'blogsy_hero_type',
					'value'    => $allowed_types,
					'operator' => 'in',
				],
			],
		];

		$settings['blogsy_hero_section'][ "blogsy_news_banner_{$section}_by_cat" ] = [
			'transport'         => 'refresh',
			'sanitize_callback' => 'blogsy_sanitize_select',
			'type'              => 'blogsy-select',
			'label'             => esc_html__( 'Select Categories', 'blogsy-news' ),
			'priority'          => 2,
			'is_select2'        => true,
			'multiple'          => true,
			'data_source'       => 'category',
			'required'          => [
				[
					'control'  => 'blogsy_hero_enable',
					'operator' => '==',
					'value'    => true,
				],
				[
					'control'  => "blogsy_news_banner_{$section}_filter_by",
					'operator' => '==',
					'value'    => 'category',
				],
				[
					'control'  => 'blogsy_hero_type',
					'value'    => $allowed_types,
					'operator' => 'in',
				],
			],
		];

		$settings['blogsy_hero_section'][ "blogsy_news_banner_{$section}_by_tag" ] = [
			'transport'         => 'refresh',
			'sanitize_callback' => 'blogsy_sanitize_select',
			'type'              => 'blogsy-select',
			'label'             => esc_html__( 'Select Tags', 'blogsy-news' ),
			'priority'          => 2,
			'is_select2'        => true,
			'multiple'          => true,
			'data_source'       => 'tags',
			'required'          => [
				[
					'control'  => 'blogsy_hero_enable',
					'operator' => '==',
					'value'    => true,
				],
				[
					'control'  => "blogsy_news_banner_{$section}_filter_by",
					'operator' => '==',
					'value'    => 'tag',
				],
				[
					'control'  => 'blogsy_hero_type',
					'value'    => $allowed_types,
					'operator' => 'in',
				],
			],
		];

		$settings['blogsy_hero_section'][ "blogsy_news_banner_{$section}_post_number" ] = [
			'transport'         => 'refresh',
			'sanitize_callback' => 'blogsy_sanitize_range',
			'type'              => 'blogsy-range',
			'label'             => esc_html__( 'Number of Posts', 'blogsy-news' ),
			'priority'          => 2,
			'input_attrs'       => [
				'min'  => 1,
				'max'  => 50,
				'step' => 1,
			],
			'required'          => [
				[
					'control'  => 'blogsy_hero_enable',
					'operator' => '==',
					'value'    => true,
				],
				[
					'control'  => 'blogsy_hero_type',
					'value'    => $allowed_types,
					'operator' => 'in',
				],
			],
		];

		if ( 'trending' === $section ) {
			$settings['blogsy_hero_section'][ "blogsy_news_banner_{$section}_swiper_post_number" ] = [
				'transport'         => 'refresh',
				'sanitize_callback' => 'blogsy_sanitize_range',
				'type'              => 'blogsy-range',
				'label'             => esc_html__( 'Number of Posts in Swiper', 'blogsy-news' ),
				'priority'          => 2,
				'input_attrs'       => [
					'min'  => 1,
					'max'  => 10,
					'step' => 1,
				],
				'required'          => [
					[
						'control'  => 'blogsy_hero_enable',
						'operator' => '==',
						'value'    => true,
					],
					[
						'control'  => 'blogsy_hero_type',
						'value'    => $allowed_types,
						'operator' => 'in',
					],
				],
			];
		}

		$settings['blogsy_hero_section'][ "blogsy_news_banner_{$section}_orderby" ] = [
			'transport'         => 'refresh',
			'sanitize_callback' => 'blogsy_sanitize_select',
			'type'              => 'blogsy-select',
			'label'             => esc_html__( 'Orderby', 'blogsy-news' ),
			'description'       => esc_html__( 'Show post orderby DESC/ASC.', 'blogsy-news' ),
			'priority'          => 2,
			'choices'           => [
				'date-desc'  => esc_html__( 'Newest - Oldest', 'blogsy-news' ),
				'date-asc'   => esc_html__( 'Oldest - Newest', 'blogsy-news' ),
				'title-asc'  => esc_html__( 'A - Z', 'blogsy-news' ),
				'title-desc' => esc_html__( 'Z - A', 'blogsy-news' ),
				'rand-desc'  => esc_html__( 'Random', 'blogsy-news' ),
			],
			'required'          => [
				[
					'control'  => 'blogsy_hero_enable',
					'operator' => '==',
					'value'    => true,
				],
				[
					'control'  => 'blogsy_hero_type',
					'value'    => $allowed_types,
					'operator' => 'in',
				],
			],
		];
	}

	$main_banner_bg['blogsy_news_banner_bg'] = [
		'transport'         => 'refresh',
		'sanitize_callback' => 'blogsy_sanitize_design_options',
		'type'              => 'blogsy-design-options',
		'label'             => esc_html__( 'Background', 'blogsy-news' ),
		'display'           => [
			'background' => [
				'color'    => esc_html__( 'Solid Color', 'blogsy-news' ),
				'gradient' => esc_html__( 'Gradient', 'blogsy-news' ),
				'image'    => esc_html__( 'Image', 'blogsy-news' ),
			],
		],
		'required'          => [
			[
				'control'  => 'blogsy_hero_enable',
				'operator' => '==',
				'value'    => true,
			],
			[
				'control'  => 'blogsy_hero_slider_style_heading',
				'operator' => '==',
				'value'    => true,
			],
		],
	];
	$settings['blogsy_hero_section']         = blogsy_array_insert(
		$settings['blogsy_hero_section'],
		$main_banner_bg,
		'blogsy_hero_slider_height'
	);

	$settings['blogsy_hero_section']['blogsy_hero_enable']['priority']     = 0;
	$settings['blogsy_hero_section']['blogsy_hero_visibility']['priority'] = 1;
	$settings['blogsy_hero_section']['blogsy_hero_enable_on']['priority']  = 2;

	$settings['blogsy_hero_section']['blogsy_hero_slider_settings_heading']['required'][] = [
		'control'  => 'blogsy_hero_type',
		'value'    => 'one',
		'operator' => '==',
	];
	$settings['blogsy_blog_archive_section']['blogsy_blog_layout_column']['required'][]   = [
		'control'  => 'blogsy_blog_layout',
		'value'    => 'blog-horizontal-2',
		'operator' => '!=',
	];
	$settings['blogsy_hero_section']['blogsy_hero_slider_post_number']['required'][]      = [
		'control'  => 'blogsy_hero_type',
		'value'    => 'one',
		'operator' => '==',
	];
	$settings['blogsy_hero_section']['blogsy_hero_slider_category']['required'][]         = [
		'control'  => 'blogsy_hero_type',
		'value'    => 'one',
		'operator' => '==',
	];
	$settings['blogsy_hero_section']['blogsy_hero_slider_tags']['required'][]             = [
		'control'  => 'blogsy_hero_type',
		'value'    => 'one',
		'operator' => '==',
	];
	$settings['blogsy_hero_section']['blogsy_hero_slider_orderby']['required'][]          = [
		'control'  => 'blogsy_hero_type',
		'value'    => 'one',
		'operator' => '==',
	];

	$settings['blogsy_hero_section']['blogsy_hero_slider_title_font_size']['required'][] = [
		'control'  => 'blogsy_hero_type',
		'value'    => 'one',
		'operator' => '==',
	];

	$settings['blogsy_hero_section']['blogsy_hero_slider_elements']['required'][] = [
		'control'  => 'blogsy_hero_type',
		'value'    => 'one',
		'operator' => '==',
	];

	$settings['blogsy_hero_section']['blogsy_hero_slider_excerpt_length']['required'][] = [
		'control'  => 'blogsy_hero_type',
		'value'    => 'one',
		'operator' => '==',
	];

	return $settings;
}
