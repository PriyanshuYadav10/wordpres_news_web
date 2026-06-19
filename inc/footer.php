<?php
/**
 * Custom footer for Blogsy News.
 *
 * Renders a rich, dark, multi-column footer. It is wired to the parent theme's
 * `blogsy_footer` action when available, and falls back to `wp_footer` so the
 * footer always appears regardless of the parent theme's exact hook names. A
 * static guard guarantees it renders exactly once.
 *
 * @package Blogsy News
 * @author  Peregrine Themes
 * @since   1.0.2
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register footer menu locations.
 */
add_action(
	'after_setup_theme',
	function () {
		register_nav_menus(
			[
				'blogsy_news_footer'        => esc_html__( 'Footer Quick Links', 'blogsy-news' ),
				'blogsy_news_footer_bottom' => esc_html__( 'Footer Bottom Bar', 'blogsy-news' ),
			]
		);
	},
	30
);

/**
 * Whether the custom footer should render.
 *
 * @since 1.0.2
 * @return bool
 */
function blogsy_news_footer_enabled() {
	/**
	 * Toggle the Blogsy News custom footer.
	 *
	 * Disabled by default so the parent theme's widget-based footer (managed
	 * from Appearance → Widgets / the Customizer) is the only footer shown.
	 * Set this filter to true to bring back the bundled custom footer.
	 *
	 * @param bool $enabled Default false.
	 */
	return (bool) apply_filters( 'blogsy_news_footer_enabled', false );
}

/**
 * Default social links shown in the footer.
 *
 * Override the URLs with the `blogsy_news_footer_socials` filter, e.g. in a
 * child theme or a small plugin. Networks with an empty/`#` URL still render so
 * the footer looks complete out of the box — set real URLs to make them live.
 *
 * @since 1.0.2
 * @return array
 */
function blogsy_news_footer_socials() {
	$socials = [
		'facebook'  => [
			'label' => esc_html__( 'Facebook', 'blogsy-news' ),
			'url'   => '#',
			'icon'  => '<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true"><path d="M13.5 21v-8h2.7l.4-3.1h-3.1V7.9c0-.9.3-1.5 1.6-1.5h1.7V3.6c-.3 0-1.3-.1-2.5-.1-2.5 0-4.1 1.5-4.1 4.2v2.2H7.5V13h2.7v8h3.3z"/></svg>',
		],
		'twitter'   => [
			'label' => esc_html__( 'X (Twitter)', 'blogsy-news' ),
			'url'   => '#',
			'icon'  => '<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true"><path d="M17.5 3h3l-6.6 7.5L21.7 21H15.6l-4.3-5.6L6.3 21H3.3l7-8L2.6 3h6.3l3.9 5.1L17.5 3zm-1 16h1.7L7.6 4.7H5.8L16.5 19z"/></svg>',
		],
		'instagram' => [
			'label' => esc_html__( 'Instagram', 'blogsy-news' ),
			'url'   => '#',
			'icon'  => '<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true"><path d="M12 7.4a4.6 4.6 0 100 9.2 4.6 4.6 0 000-9.2zm0 7.6a3 3 0 110-6 3 3 0 010 6zm4.8-7.8a1.1 1.1 0 11-2.2 0 1.1 1.1 0 012.2 0zM20 7.7c-.1-1.4-.4-2.7-1.4-3.7S16.4 2.7 15 2.6c-1.4-.1-5.6-.1-7 0-1.4.1-2.6.4-3.6 1.4S2.7 7.6 2.6 9c-.1 1.4-.1 5.6 0 7 .1 1.4.4 2.6 1.4 3.6s2.2 1.3 3.6 1.4c1.4.1 5.6.1 7 0 1.4-.1 2.7-.4 3.7-1.4s1.3-2.2 1.4-3.6c.1-1.4.1-5.6 0-7zM18 16.5c-.3.8-.9 1.4-1.7 1.7-1.2.5-4 .4-5.3.4s-4.1.1-5.3-.4c-.8-.3-1.4-.9-1.7-1.7-.5-1.2-.4-4-.4-5.3s-.1-4.1.4-5.3c.3-.8.9-1.4 1.7-1.7 1.2-.5 4-.4 5.3-.4s4.1-.1 5.3.4c.8.3 1.4.9 1.7 1.7.5 1.2.4 4 .4 5.3s.1 4.1-.4 5.3z"/></svg>',
		],
		'youtube'   => [
			'label' => esc_html__( 'YouTube', 'blogsy-news' ),
			'url'   => '#',
			'icon'  => '<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true"><path d="M22 8.1c-.2-1.4-.8-2.4-2.3-2.6C17.6 5.1 12 5.1 12 5.1s-5.6 0-7.7.4C2.8 5.7 2.2 6.7 2 8.1 1.8 9.5 1.8 12 1.8 12s0 2.5.2 3.9c.2 1.4.8 2.4 2.3 2.6 2.1.4 7.7.4 7.7.4s5.6 0 7.7-.4c1.5-.2 2.1-1.2 2.3-2.6.2-1.4.2-3.9.2-3.9s0-2.5-.2-3.9zM10.2 15V9l5 3-5 3z"/></svg>',
		],
	];

	/**
	 * Filter the footer social links.
	 *
	 * @param array $socials Map of network key => [label, url, icon].
	 */
	return (array) apply_filters( 'blogsy_news_footer_socials', $socials );
}

/**
 * Output the custom footer (rendered once).
 *
 * @since 1.0.2
 * @return void
 */
function blogsy_news_footer_output() {
	static $rendered = false;

	if ( $rendered || ! blogsy_news_footer_enabled() ) {
		return;
	}
	$rendered = true;

	// Ad slot just above the footer.
	if ( function_exists( 'blogsy_news_adsterra_render' ) ) {
		blogsy_news_adsterra_render( 'before_footer' );
	}

	get_template_part( 'template-parts/footer/footer-main' );
}

// Primary: parent theme footer hook (if present).
add_action( 'blogsy_footer', 'blogsy_news_footer_output', 20 );

// Fallback: if the parent never fires `blogsy_footer`, render at wp_footer.
add_action(
	'wp_footer',
	function () {
		if ( ! did_action( 'blogsy_footer' ) ) {
			blogsy_news_footer_output();
		}
	},
	5
);

/**
 * Render the footer Adsterra zones when the custom footer is disabled.
 *
 * The "Before Footer" and "Inside Footer" ad placements used to be printed only
 * by the custom footer markup. With that footer disabled, this prints them in
 * the footer area so those placements keep working. When the custom footer is
 * enabled it already outputs them, so this bails to avoid duplicates.
 *
 * @since 1.0.3
 * @return void
 */
function blogsy_news_footer_ads_output() {
	static $rendered = false;

	if ( $rendered || blogsy_news_footer_enabled() || ! function_exists( 'blogsy_news_adsterra_render' ) ) {
		return;
	}
	$rendered = true;

	blogsy_news_adsterra_render( 'before_footer' );
	blogsy_news_adsterra_render( 'footer' );
}

// Primary: parent theme footer hook (render early, above the parent footer).
add_action( 'blogsy_footer', 'blogsy_news_footer_ads_output', 5 );

// Fallback: if the parent never fires `blogsy_footer`, render at wp_footer.
add_action(
	'wp_footer',
	function () {
		if ( ! did_action( 'blogsy_footer' ) ) {
			blogsy_news_footer_ads_output();
		}
	},
	1
);
