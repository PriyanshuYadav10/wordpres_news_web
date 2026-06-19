<?php
/**
 * Blogsy News Customizer options. Blogsy News related customizer options.
 *
 * @package Blogsy News
 * @author  Peregrine Themes <peregrinethemes@gmail.com>
 */

use Blogsy\Helper;

/**
 * Blogsy News default options values.
 *
 * @since 1.0.0
 * @param array $defaults Array of default options.
 * @return array Modified array of default options.
 */
function blogsy_news_default_options_values( $defaults ) {
	$new_defaults = [
		'blogsy_logo_max_height'                          => [
			'desktop' => 55,
			'tablet'  => 40,
			'mobile'  => 25,
		],
		'blogsy_logo_margin'                              => [
			'desktop' => [
				'top'    => 36,
				'right'  => 1,
				'bottom' => 36,
				'left'   => 1,
			],
			'tablet'  => [
				'top'    => 18,
				'right'  => 1,
				'bottom' => 18,
				'left'   => 0,
			],
			'mobile'  => [
				'top'    => 18,
				'right'  => 1,
				'bottom' => 18,
				'left'   => 0,
			],
			'unit'    => 'px',
		],

		'blogsy_typo_body'                                => Helper::typography_defaults(
			[
				'font-family'         => 'Lora',
				'font-weight'         => 400,
				'letter-spacing'      => '0.2',
				'letter-spacing-unit' => 'px',
				'font-size-desktop'   => '15',
				'font-size-unit'      => 'px',
				'line-height-desktop' => '1.55',
				'color'               => '',
			],
		),
		'blogsy_typo_h1'                                  => Helper::typography_defaults(
			[
				'font-family'         => 'Ibarra Real Nova',
				'font-weight'         => 600,
				'letter-spacing'      => '',
				'font-size-desktop'   => '4.2',
				'font-size-unit'      => 'rem',
				'line-height-desktop' => '1.2',
				'color'               => '',
			],
		),
		'blogsy_typo_h2'                                  => Helper::typography_defaults(
			[
				'font-family'         => 'Ibarra Real Nova',
				'font-weight'         => 600,
				'letter-spacing'      => '',
				'font-size-desktop'   => '3.4',
				'font-size-unit'      => 'rem',
				'line-height-desktop' => '1.4',
				'color'               => '',
			],
		),
		'blogsy_typo_h3'                                  => Helper::typography_defaults(
			[
				'font-family'         => 'Ibarra Real Nova',
				'font-weight'         => 600,
				'letter-spacing'      => '',
				'font-size-desktop'   => '2.6',
				'font-size-unit'      => 'rem',
				'line-height-desktop' => '1.4',
				'color'               => '',
			],
		),
		'blogsy_typo_h4'                                  => Helper::typography_defaults(
			[
				'font-family'         => 'Ibarra Real Nova',
				'font-weight'         => 600,
				'letter-spacing'      => '',
				'font-size-desktop'   => '2.2',
				'font-size-unit'      => 'rem',
				'line-height-desktop' => '1.4',
				'color'               => '',
			],
		),
		'blogsy_typo_h5'                                  => Helper::typography_defaults(
			[
				'font-family'         => 'Ibarra Real Nova',
				'font-weight'         => 600,
				'letter-spacing'      => '',
				'font-size-desktop'   => '1.8',
				'font-size-unit'      => 'rem',
				'line-height-desktop' => '1.4',
				'color'               => '',
			],
		),
		'blogsy_typo_h6'                                  => Helper::typography_defaults(
			[
				'font-family'         => 'Ibarra Real Nova',
				'font-weight'         => 600,
				'letter-spacing'      => '',
				'font-size-desktop'   => '1.6',
				'font-size-unit'      => 'rem',
				'line-height-desktop' => '1.4',
				'color'               => '',
			],
		),

		'blogsy_typo_section_title'                       => Helper::typography_defaults(
			[
				'font-family'         => 'inherit',
				'font-weight'         => 600,
				'letter-spacing'      => '',
				'font-size-desktop'   => '1.8',
				'font-size-unit'      => 'rem',
				'line-height-desktop' => '1.4',
			]
		),
		'blogsy_typo_widgets_title'                       => Helper::typography_defaults(
			[
				'font-family'         => 'inherit',
				'font-weight'         => 600,
				'letter-spacing'      => '',
				'font-size-desktop'   => '1.8',
				'font-size-unit'      => 'rem',
				'line-height-desktop' => '1.4',
			]
		),
		'blogsy_divider_style'                            => '12',
		'blogsy_typo_terms'                               => Helper::typography_defaults(
			[
				'font-family'         => 'Ibarra Real Nova',
				'font-weight'         => 600,
				'text-transform'      => 'uppercase',
				'letter-spacing'      => '0.25',
				'letter-spacing-unit' => 'px',
				'font-size-desktop'   => '1.3',
				'font-size-unit'      => 'rem',
				'line-height-desktop' => '1.1',
			]
		),
		'blogsy_typo_menu'                                => Helper::typography_defaults(
			[
				'font-family'         => 'inherit',
				'font-weight'         => 700,
				'letter-spacing'      => '0',
				'letter-spacing-unit' => 'px',
				'font-size-desktop'   => '16',
				'font-size-unit'      => 'px',
				'line-height-desktop' => '1.4',
			],
		),

		'blogsy_accent_color'                             => '#e93314',
		'blogsy_button_shape_style'                       => 'sharp',

		'blogsy_top_bar_background'                       => Helper::design_options_defaults(
			[
				'background' => [
					'background-type' => 'color',
					'color'           => [
						'background-color' => '#e93314',
					],
					'gradient'        => [
						'gradient-color-1' => '#e93314',
						'gradient-color-2' => '#f84d57',
					],
				],
			]
		),

		'blogsy_header_layout'                            => 'layout-4',
		'blogsy_header_widgets'                           => [
			[
				'classname' => 'blogsy_customizer_widget_socials',
				'type'      => 'socials',
				'values'    => [
					'style'      => 'rounded-border',
					'size'       => 'standard',
					'location'   => 'left',
					'visibility' => 'hide-mobile-tablet',
				],
			],
			[
				'classname' => 'blogsy_customizer_widget_darkmode',
				'type'      => 'darkmode',
				'values'    => [
					'location'   => 'right',
					'visibility' => 'all',
				],
			],
			[
				'classname' => 'blogsy_customizer_widget_button',
				'type'      => 'button',
				'values'    => [
					'text'       => \Blogsy\Icon::get_svg( 'account-2' ),
					'url'        => '#',
					'class'      => 'btn-no-style btn-transparent',
					'target'     => '_self',
					'location'   => 'right',
					'visibility' => 'hide-mobile',
				],
			],
		],
		'blogsy_header_heading_design_options'            => true,
		'blogsy_header_background'                        => Helper::design_options_defaults(
			[
				'background' => [
					'color'    => [
						'background-color' => 'transparent',
					],
					'gradient' => [
						'gradient-color-1' => '#e93314',
						'gradient-color-2' => '#e61ca9',
					],
				],
			]
		),
		'blogsy_header_text_color'                        => Helper::design_options_defaults(
			[
				'color' => [
					'text-color'        => '#29294b',
					'link-color'        => '#29294b',
					'link-hover-color'  => '#e93314',
					'link-active-color' => '#e93314',
				],
			]
		),
		'blogsy_header_border'                            => Helper::design_options_defaults(
			[
				'border' => [
					'border-top-width'    => '0',
					'border-right-width'  => '0',
					'border-bottom-width' => '1',
					'border-left-width'   => '0',
					'border-style'        => 'solid',
					'border-color'        => 'rgba(186, 186, 186, 0.4)',
				],
			]
		),

		'blogsy_header_navigation_heading_widgets'        => true,
		'blogsy_header_navigation_widgets'                => [
			[
				'classname' => 'blogsy_customizer_widget_offcanvas',
				'type'      => 'offcanvas',
				'values'    => [
					'location'   => 'left',
					'visibility' => 'hide-mobile',
				],
			],
			[
				'classname' => 'blogsy_customizer_widget_button',
				'type'      => 'button',
				'values'    => [
					'text'       => \Blogsy\Icon::get_svg( 'bell' ) . ' Subscribe',
					'url'        => '#',
					'class'      => '',
					'target'     => '_self',
					'location'   => 'right',
					'visibility' => 'hide-mobile',
				],
			],
			[
				'classname' => 'blogsy_customizer_widget_search',
				'type'      => 'search',
				'values'    => [
					'style'      => 1,
					'location'   => 'right',
					'visibility' => 'hide-mobile',
				],
			],
		],
		'blogsy_header_navigation_heading_design_options' => true,
		'blogsy_header_navigation_background'             => Helper::design_options_defaults(
			[
				'background' => [
					'color'    => [
						'background-color' => 'transparent',
					],
					'gradient' => [
						'gradient-color-1' => '#e93314',
						'gradient-color-2' => '#e61ca9',
					],
				],
			]
		),
		'blogsy_header_navigation_text_color'             => Helper::design_options_defaults(
			[
				'color' => [
					'text-color'        => '#29294b',
					'link-color'        => '#29294b',
					'link-hover-color'  => '#e93314',
					'link-active-color' => '#e93314',
				],
			]
		),
		'blogsy_header_navigation_border'                 => Helper::design_options_defaults(
			[
				'border' => [
					'border-top-width'    => '0',
					'border-right-width'  => '0',
					'border-bottom-width' => '1',
					'border-left-width'   => '0',
					'border-style'        => 'solid',
					'border-color'        => '#000',
				],
			]
		),

		// Ticker.
		'blogsy_ticker_title'                             => esc_html__( 'Exclusive', 'blogsy-news' ),
		'blogsy_ticker_type'                              => 'layout-2',
		'blogsy_ticker_elements'                          => [
			'thumbnail'  => true,
			'meta'       => true,
			'play_pause' => true,
		],

		// Blog Layout.
		'blogsy_blog_layout'                              => 'blog-horizontal-2',

		// Hero.
		'blogsy_hero_enable'                              => true,
		'blogsy_hero_type'                                => 'three',
		'blogsy_hero_slider_height'                       => [
			'desktop' => 496,
			'tablet'  => 418,
			'mobile'  => 350,
			'unit'    => 'px',
		],
		'blogsy_news_banner_bg'                           => Helper::design_options_defaults(
			[
				'background' => [
					'color'    => [
						'background-color' => '',
					],
					'gradient' => [
						'gradient-color-1' => '#e93314',
						'gradient-color-2' => '#e61ca9',
					],
				],
			]
		),
	];

	$sections = [
		'editor'   => [],
		'main'     => [ 'count' => 3 ],
		'latest'   => [],
		'popular'  => [ 'filter_by' => 'comment_count' ],
		'update'   => [],
		'trending' => [ 'count' => 7 ],
	];

	$prefix = 'blogsy_news_banner';

	foreach ( $sections as $section => $args ) {
		$args = wp_parse_args(
			$args,
			[
				'heading'   => true,
				'count'     => 4,
				'filter_by' => 'category',
				'by_cat'    => [],
				'by_tag'    => [],
				'orderby'   => 'date-desc',
			]
		);

		$key = "{$prefix}_{$section}";

		$defaults[ "{$key}_heading" ]     = $args['heading'];
		$defaults[ "{$key}_label" ]       = ucfirst( $section );
		$defaults[ "{$key}_post_number" ] = $args['count'];
		$defaults[ "{$key}_filter_by" ]   = $args['filter_by'];
		$defaults[ "{$key}_by_cat" ]      = $args['by_cat'];
		$defaults[ "{$key}_by_tag" ]      = $args['by_tag'];
		$defaults[ "{$key}_orderby" ]     = $args['orderby'];

		if ( 'trending' === $section ) {
			$defaults[ "{$key}_swiper_post_number" ] = 4;
		}
	}

	return array_merge( $defaults, $new_defaults );
}

add_filter( 'blogsy_default_options_values', 'blogsy_news_default_options_values', 11 );
