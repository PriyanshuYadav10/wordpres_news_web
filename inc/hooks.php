<?php
/**
 * Blogsy News functions and definitions.
 *
 * @package Blogsy News
 * @author  Peregrine Themes <peregrinethemes@gmail.com>
 * @since   1.0.0
 */

use Blogsy\Dynamic_Styles;
use Blogsy\Helper;

/**
 * Add new header layout to the list of available header layouts.
 *
 * @since 1.0.0
 * @param array $layouts Array of header layouts.
 * @return array Modified array of header layouts.
 */
function blogsy_news_header_layouts_prebuild( array $layouts ) {
	$layouts['layout-4'] = sprintf( 'Header 4', 'blogsy-news' );
	return $layouts;
}
add_filter( 'blogsy_header_layouts_prebuild', 'blogsy_news_header_layouts_prebuild' );

/**
 * Dynamically generate CSS code.
 *
 * @param string $css The existing CSS code.
 * @return string The modified CSS code.
 */
function blogsy_news_dynamic_styles( $css ) {

	$dynamic = Dynamic_Styles::instance();

	/**
	 * Header Design Options
	 */
	// Background.
	$header_classes = 'html:not([scheme="dark"]) .pt-header-layout-1 .pt-header .pt-header-inner .pt-header-container::after,
						html:not([scheme="dark"]) .pt-header-layout-2 .pt-header .pt-header-inner,
						html:not([scheme="dark"]) .pt-header-layout-3 .pt-header .pt-header-inner > .pt-header-container,
						html:not([scheme="dark"]) .pt-header-layout-4 .pt-header .pt-header-inner';
	$css           .= $dynamic->get_design_options_field_css( $header_classes, 'header_background', 'background' );

	// Border.
	$header_border_classes = 'html:not([scheme="dark"]) .pt-header-layout-1 .pt-header .pt-header-inner .pt-header-container::after,
						html:not([scheme="dark"]) .pt-header-layout-2 .pt-header .pt-header-inner,
						html:not([scheme="dark"]) .pt-header-layout-3 .pt-header .pt-header-inner > .pt-header-container,
						html:not([scheme="dark"]) .pt-header-layout-4 .pt-header .pt-header-inner .pt-logo-container .pt-header-container';
	$css                  .= $dynamic->get_design_options_field_css( $header_border_classes, 'header_border', 'border' );

	/**
	 * Header Navigation Design Options
	 */
	// Background.
	$header_classes = 'html:not([scheme="dark"]) .pt-header-layout-4 .pt-header .pt-header-inner .pt-nav-container';
	$css           .= $dynamic->get_design_options_field_css( $header_classes, 'header_navigation_background', 'background' );

	// Border.
	$css .= $dynamic->get_design_options_field_css( '.pt-header-layout-4 .pt-header .pt-header-inner .pt-nav-container .pt-header-container', 'header_navigation_border', 'border' );

	// Header colors.
	$header_color = Helper::get_option( 'header_text_color' );

	// Header text color.
	if ( isset( $header_color['text-color'] ) && $header_color['text-color'] ) {
		$css .= 'html:not([scheme="dark"]) .pt-header .pt-nav-container { color: ' . blogsy_sanitize_color( $header_color['text-color'] ) . '; }';
	}

	// Header link color.
	if ( isset( $header_color['link-color'] ) && $header_color['link-color'] ) {
		$css .= '
			html:not([scheme="dark"]) .pt-header .pt-nav-container .blogsy-header-nav > li > a,
			html:not([scheme="dark"]) .pt-header .pt-nav-container .blogsy-header-v-nav > li > a,
			html:not([scheme="dark"]) .pt-header .pt-nav-container .pt-header-widget .blogsy-social-icons-widget:not(.minimal-fill, .rounded-fill) > ul > li > a {
				color: ' . blogsy_sanitize_color( $header_color['link-color'] ) . '; }
		';
	}

	// Header link hover color.
	if ( isset( $header_color['link-hover-color'] ) && $header_color['link-hover-color'] ) {
		$css .= '
			html .pt-header .pt-nav-container .blogsy-header-nav > li > a:hover,
			html .pt-header .pt-nav-container .blogsy-header-nav > li.hovered > a,
			html .pt-header .pt-nav-container .blogsy-header-nav > li.current_page_item > a,
			html .pt-header .pt-nav-container .blogsy-header-nav > li.current-menu-item > a,
			html .pt-header .pt-nav-container .blogsy-header-nav > li.current-menu-ancestor > a,
			html .pt-header .pt-nav-container .blogsy-header-v-nav > li a:focus,
			html .pt-header .pt-nav-container .blogsy-header-v-nav > li a:hover,
			html .pt-header .pt-nav-container .pt-header-widget .blogsy-social-icons-widget:not(.minimal-fill, .rounded-fill) > ul > li > a:focus,
			html .pt-header .pt-nav-container .pt-header-widget .blogsy-social-icons-widget:not(.minimal-fill, .rounded-fill) > ul > li > a:hover {
				color: ' . blogsy_sanitize_color( $header_color['link-hover-color'] ) . '; }
		';
	}

	// Header link active color.
	if ( isset( $header_color['link-active-color'] ) && $header_color['link-active-color'] ) {
		$css .= '
			html .pt-header .pt-nav-container .blogsy-header-nav > li.menu-item > a {
				--menu-shape-color: ' . blogsy_sanitize_color( $header_color['link-active-color'] ) . ';
			}
		';
	}

	/**
	 * Main Banner Background
	 */
	if ( Helper::get_option( 'hero_banner_bg' ) ) {
		$css .= '
			#blogsy-hero {
				padding: 5rem 0;
			}
		';
		// Background.
		$css .= $dynamic->get_design_options_field_css( '#blogsy-hero', 'hero_banner_bg', 'background' );
	}

	return $css;
}
add_filter( 'blogsy_dynamic_styles', 'blogsy_news_dynamic_styles' );

/**
 * Outputs the header widgets in Header Navigation Widget Locations.
 *
 * @since 1.0.0
 * @param array $locations Widget location.
 */
function blogsy_header_navigation_widgets( array $locations ): void {

	$all_widgets = (array) apply_filters( 'blogsy_main_header_selected_widgets', Helper::get_option( 'header_navigation_widgets' ) );
	blogsy_header_widget_output( $locations, $all_widgets );
}
add_action( 'blogsy_header_navigation_widget_location', 'blogsy_header_navigation_widgets', 1 );

/**
 * Modify search widget styles
 *
 * @param array $styles Array of styles.
 * @return array Modified array of styles.
 */
function blogsy_news_header_search_widget_styles( $styles ) {

	// Expand remove karo (key = 3).
	if ( isset( $styles[3] ) ) {
		unset( $styles[3] );
	}

	// Popup add karo (key = 1).
	$styles[1] = esc_html__( 'Popup', 'blogsy-news' );

	return $styles;
}
add_filter( 'blogsy_header_search_widget_styles', 'blogsy_news_header_search_widget_styles' );

/**
 * Build and return hero data for layout two, three (and future layouts).
 *
 * @param  array $data Data returned by parent theme's blogsy_get_hero_data().
 * @return array
 */
function blogsy_news_hero_data( array $data ): array {
	// Only run for supported layouts.
	if ( empty( $data['type'] ) ) {
		return $data;
	}

	$type = $data['type'];

	// Future-ready layout support.
	$positions_map = [
		'two'   => [ 'main', 'editor' ],
		'three' => [ 'latest', 'popular', 'update', 'main', 'trending' ],
	];

	if ( ! isset( $positions_map[ $type ] ) ) {
		return $data;
	}

	$positions                  = $positions_map[ $type ];
	$sections                   = [];
	$all_post_ids               = [];
	$main_slider_settings       = [];
	$trending_carousel_settings = [];

	foreach ( $positions as $position ) {

		// ── Order ──────────────────────────────────────────────────────────────
		$orderby_raw = Helper::get_option( "news_banner_{$position}_orderby" ) ?: 'date-desc';
		$order_parts = explode( '-', $orderby_raw );

		$orderby = $order_parts[0] ?? 'date';
		$order   = strtoupper( $order_parts[1] ?? 'DESC' );

		// Safety for orderby.
		$allowed_orderby = [ 'date', 'title', 'rand' ];
		if ( ! in_array( $orderby, $allowed_orderby, true ) ) {
			$orderby = 'date';
		}

		// ── Posts per section ──────────────────────────────────────────────────
		$posts_per_page = (int) Helper::get_option( "news_banner_{$position}_post_number" );

		// ── Tax query ──────────────────────────────────────────────────────────
		$filter_by = Helper::get_option( "news_banner_{$position}_filter_by" ) ?: 'category';
		$tax_query = [];
		if ( 'category' === $filter_by ) {
			$cats = Helper::get_option( "news_banner_{$position}_by_cat" );
			if ( ! empty( $cats ) ) {
				$tax_query[] = [
					'taxonomy' => 'category',
					'field'    => 'term_id',
					'terms'    => (array) $cats,
				];
			}
		} elseif ( 'tag' === $filter_by ) {
			$tags = Helper::get_option( "news_banner_{$position}_by_tag" );
			if ( ! empty( $tags ) ) {
				$tax_query[] = [
					'taxonomy' => 'post_tag',
					'field'    => 'term_id',
					'terms'    => (array) $tags,
				];
			}
		} elseif ( 'comment_count' === $filter_by ) {
			// Retrive most commented posts, no need for tax query.
			$orderby = 'comment_count';
			$order   = 'DESC';

		}

		// ── Build query args ───────────────────────────────────────────────────
		$query_args = [
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => $posts_per_page, // phpcs:ignore WordPress.WP.PostsPerPage.posts_per_page_posts_per_page
			'order'               => $order,
			'orderby'             => $orderby,
			'ignore_sticky_posts' => true,
			// 'post__not_in'        => ! empty( $tax_query ) && ! empty( $all_post_ids ) ? $all_post_ids : [],
		];

		// Add tax_query only if exists (performance fix).
		if ( ! empty( $tax_query ) ) {
			$query_args['tax_query'] = $tax_query;
		}

		$query_args = apply_filters( "blogsy_news_hero_{$position}_query_args", $query_args );

		// ── Run the query & collect IDs ────────────────────────────────────────
		$query = new WP_Query( $query_args );

		if ( $query->have_posts() ) {
			$ids          = wp_list_pluck( $query->posts, 'ID' );
			$all_post_ids = array_merge( $all_post_ids, $ids );
		}

		// Main slider settings (mirrors hero-one Swiper config).
		$main_slider_settings = apply_filters(
			'blogsy_news_hero_slider_settings',
			[
				'autoplay'   => [ 'delay' => 4000 ],
				'loop'       => true,
				'speed'      => 800,
				'effect'     => 'slide',
				'pagination' => [
					'el'        => '#blogsy-hero-nexo-slider .carousel-pagination',
					'type'      => 'bullets',
					'clickable' => true,
				],
				'navigation' => [
					'nextEl' => '#blogsy-hero-nexo-slider .carousel-nav-next',
					'prevEl' => '#blogsy-hero-nexo-slider .carousel-nav-prev',
				],
				'a11y'       => [ 'enabled' => false ],
			]
		);

		// Trending carousel settings (mirrors hero-six Swiper config).
		$swiper_post_number         = Helper::get_option( "news_banner_{$position}_swiper_post_number" );
		$trending_carousel_settings = apply_filters(
			'blogsy_news_hero_trending_carousel_settings',
			[
				'autoplay'       => [
					'delay' => 8000,
				],
				'loop'           => true,
				'speed'          => 800,
				'slidesPerView'  => $swiper_post_number,
				'slidesPerGroup' => 1,
				'spaceBetween'   => 16,
				'effect'         => 'slide',
				'autoHeight'     => false,
				'direction'      => 'vertical',
				'navigation'     => [
					'nextEl' => '#blogsy-hero-elastic-carousel .carousel-nav-next',
					'prevEl' => '#blogsy-hero-elastic-carousel .carousel-nav-prev',
				],
				'pagination'     => false,
				'a11y'           => [
					'enabled' => false,
				],
				'fadeEffect'     => [
					'crossFade' => true,
				],
			]
		);

		$sections[ $position ] = [
			'query' => $query,
			'label' => Helper::get_option( "news_banner_{$position}_label" ),
		];
	}

	// ── Store used post IDs ─────────────────────────────
	$existing_ids = get_transient( 'blogsy_hero_slider_post_ids' );
	$existing_ids = is_array( $existing_ids ) ? $existing_ids : [];

	$merged_ids = array_unique( array_merge( $existing_ids, $all_post_ids ) );
	set_transient( 'blogsy_hero_slider_post_ids', $merged_ids );

	return [
		'type'                       => $type,
		'sections'                   => $sections,
		'main_slider_settings'       => $main_slider_settings,
		'trending_carousel_settings' => $trending_carousel_settings,
	];
}
add_filter( 'blogsy_hero_data', 'blogsy_news_hero_data' );

add_filter(
	'blogsy_blog_layout_choices',
	function ( $choices ) {
		return blogsy_array_insert( $choices, [ 'blog-horizontal-2' => esc_html__( 'Horizontal 2', 'blogsy-news' ) ], 'blog-horizontal' );
	}
);
