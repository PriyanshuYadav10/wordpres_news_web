<?php
/**
 * Top bar extras for Blogsy News.
 *
 * Adds a row of quick links (Privacy Policy, FAQ's, Contact Us, About Us,
 * Location, Terms of Use) together with social media handles to the right side
 * of the parent theme's red top bar.
 *
 * The parent theme renders the top bar, so this module is intentionally
 * self-contained and dependency-free: it server-renders the markup (escaped,
 * translatable, filterable) into a hidden holder at `wp_footer`, and a small
 * progressive-enhancement script relocates it into the top bar. If the top bar
 * is ever absent, the script renders its own matching strip so the content is
 * never lost.
 *
 * @package Blogsy News
 * @author  Peregrine Themes
 * @since   1.0.3
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the top bar quick-links menu location.
 *
 * Assign a menu here (Appearance → Menus) to fully control the links; when no
 * menu is assigned the sensible defaults below are used instead.
 */
add_action(
	'after_setup_theme',
	function () {
		register_nav_menus(
			[
				'blogsy_news_top_bar' => esc_html__( 'Top Bar Quick Links', 'blogsy-news' ),
			]
		);
	},
	30
);

/**
 * Whether the top bar extras should render.
 *
 * @since 1.0.3
 * @return bool
 */
function blogsy_news_top_bar_enabled() {
	/**
	 * Toggle the Blogsy News top bar extras.
	 *
	 * @param bool $enabled Default true.
	 */
	return (bool) apply_filters( 'blogsy_news_top_bar_enabled', true );
}

/**
 * Resolve a quick-link URL.
 *
 * Links to the matching page when one exists (by slug); otherwise points at the
 * expected slug so the link is ready the moment the page is published.
 *
 * @since 1.0.3
 * @param string $slug Page slug to resolve.
 * @return string
 */
function blogsy_news_top_bar_link_url( $slug ) {
	$page = get_page_by_path( $slug );

	if ( $page instanceof WP_Post ) {
		return (string) get_permalink( $page );
	}

	return home_url( '/' . $slug . '/' );
}

/**
 * Default top bar quick links.
 *
 * Override with the `blogsy_news_top_bar_links` filter, e.g. in a small plugin
 * or child theme.
 *
 * @since 1.0.3
 * @return array List of [ 'label' => string, 'url' => string ].
 */
function blogsy_news_top_bar_links() {
	$defaults = [
		'privacy-policy' => esc_html__( 'Privacy Policy', 'blogsy-news' ),
		'faq'            => esc_html__( "FAQ's", 'blogsy-news' ),
		'contact-us'     => esc_html__( 'Contact Us', 'blogsy-news' ),
		'about-us'       => esc_html__( 'About Us', 'blogsy-news' ),
		'location'       => esc_html__( 'Location', 'blogsy-news' ),
		'terms-of-use'   => esc_html__( 'Terms of Use', 'blogsy-news' ),
	];

	$links = [];
	foreach ( $defaults as $slug => $label ) {
		$links[] = [
			'label' => $label,
			'url'   => blogsy_news_top_bar_link_url( $slug ),
		];
	}

	/**
	 * Filter the top bar quick links.
	 *
	 * @param array $links List of [ 'label', 'url' ] items.
	 */
	return (array) apply_filters( 'blogsy_news_top_bar_links', $links );
}

/**
 * Default social handles shown in the top bar.
 *
 * Defaults mirror the networks shown in the header. Set real URLs to make them
 * live, or override the whole set with the `blogsy_news_top_bar_socials` filter.
 *
 * @since 1.0.3
 * @return array Map of network key => [ label, url, icon ].
 */
function blogsy_news_top_bar_socials() {
	$socials = [
		'facebook'  => [
			'label' => esc_html__( 'Facebook', 'blogsy-news' ),
			'url'   => '#',
			'icon'  => '<svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M13.5 21v-8h2.7l.4-3.1h-3.1V7.9c0-.9.3-1.5 1.6-1.5h1.7V3.6c-.3 0-1.3-.1-2.5-.1-2.5 0-4.1 1.5-4.1 4.2v2.2H7.5V13h2.7v8h3.3z"/></svg>',
		],
		'twitter'   => [
			'label' => esc_html__( 'X (Twitter)', 'blogsy-news' ),
			'url'   => '#',
			'icon'  => '<svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M17.5 3h3l-6.6 7.5L21.7 21H15.6l-4.3-5.6L6.3 21H3.3l7-8L2.6 3h6.3l3.9 5.1L17.5 3zm-1 16h1.7L7.6 4.7H5.8L16.5 19z"/></svg>',
		],
		'telegram'  => [
			'label' => esc_html__( 'Telegram', 'blogsy-news' ),
			'url'   => '#',
			'icon'  => '<svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M21.9 4.3 18.7 19.4c-.2 1.1-.9 1.3-1.8.8l-4.9-3.6-2.4 2.3c-.3.3-.5.5-1 .5l.3-5 9.1-8.2c.4-.4-.1-.6-.6-.2L6.4 13 1.6 11.4c-1-.3-1-1 .2-1.5l19.1-7.4c.9-.3 1.6.2 1 1.8z"/></svg>',
		],
		'instagram' => [
			'label' => esc_html__( 'Instagram', 'blogsy-news' ),
			'url'   => '#',
			'icon'  => '<svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M12 7.4a4.6 4.6 0 100 9.2 4.6 4.6 0 000-9.2zm0 7.6a3 3 0 110-6 3 3 0 010 6zm4.8-7.8a1.1 1.1 0 11-2.2 0 1.1 1.1 0 012.2 0zM20 7.7c-.1-1.4-.4-2.7-1.4-3.7S16.4 2.7 15 2.6c-1.4-.1-5.6-.1-7 0-1.4.1-2.6.4-3.6 1.4S2.7 7.6 2.6 9c-.1 1.4-.1 5.6 0 7 .1 1.4.4 2.6 1.4 3.6s2.2 1.3 3.6 1.4c1.4.1 5.6.1 7 0 1.4-.1 2.7-.4 3.7-1.4s1.3-2.2 1.4-3.6c.1-1.4.1-5.6 0-7zM18 16.5c-.3.8-.9 1.4-1.7 1.7-1.2.5-4 .4-5.3.4s-4.1.1-5.3-.4c-.8-.3-1.4-.9-1.7-1.7-.5-1.2-.4-4-.4-5.3s-.1-4.1.4-5.3c.3-.8.9-1.4 1.7-1.7 1.2-.5 4-.4 5.3-.4s4.1-.1 5.3.4c.8.3 1.4.9 1.7 1.7.5 1.2.4 4 .4 5.3s.1 4.1-.4 5.3z"/></svg>',
		],
		'youtube'   => [
			'label' => esc_html__( 'YouTube', 'blogsy-news' ),
			'url'   => '#',
			'icon'  => '<svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M22 8.1c-.2-1.4-.8-2.4-2.3-2.6C17.6 5.1 12 5.1 12 5.1s-5.6 0-7.7.4C2.8 5.7 2.2 6.7 2 8.1 1.8 9.5 1.8 12 1.8 12s0 2.5.2 3.9c.2 1.4.8 2.4 2.3 2.6 2.1.4 7.7.4 7.7.4s5.6 0 7.7-.4c1.5-.2 2.1-1.2 2.3-2.6.2-1.4.2-3.9.2-3.9s0-2.5-.2-3.9zM10.2 15V9l5 3-5 3z"/></svg>',
		],
	];

	/**
	 * Filter the top bar social links.
	 *
	 * @param array $socials Map of network key => [ label, url, icon ].
	 */
	return (array) apply_filters( 'blogsy_news_top_bar_socials', $socials );
}

/**
 * Build the top bar extras markup (quick links + social handles).
 *
 * @since 1.0.3
 * @return string
 */
function blogsy_news_top_bar_extras_markup() {
	$socials = blogsy_news_top_bar_socials();

	ob_start();
	?>
	<div class="blogsy-news-topbar-extras">
		<nav class="blogsy-news-topbar-links" aria-label="<?php esc_attr_e( 'Top bar quick links', 'blogsy-news' ); ?>">
			<?php
			if ( has_nav_menu( 'blogsy_news_top_bar' ) ) {
				wp_nav_menu(
					[
						'theme_location' => 'blogsy_news_top_bar',
						'container'      => false,
						'menu_class'     => 'blogsy-news-topbar-menu',
						'depth'          => 1,
						'fallback_cb'    => false,
					]
				);
			} else {
				$links = blogsy_news_top_bar_links();
				if ( ! empty( $links ) ) {
					echo '<ul class="blogsy-news-topbar-menu">';
					foreach ( $links as $link ) {
						if ( empty( $link['label'] ) ) {
							continue;
						}
						printf(
							'<li class="menu-item"><a href="%1$s">%2$s</a></li>',
							esc_url( $link['url'] ?? '#' ),
							esc_html( $link['label'] )
						);
					}
					echo '</ul>';
				}
			}
			?>
		</nav>

		<?php if ( ! empty( $socials ) ) : ?>
			<ul class="blogsy-news-topbar-socials" aria-label="<?php esc_attr_e( 'Social media', 'blogsy-news' ); ?>">
				<?php foreach ( $socials as $key => $social ) : ?>
					<?php if ( empty( $social['url'] ) ) { continue; } ?>
					<li>
						<a href="<?php echo esc_url( $social['url'] ); ?>" class="social-<?php echo esc_attr( $key ); ?>" aria-label="<?php echo esc_attr( $social['label'] ?? $key ); ?>" target="_blank" rel="noopener noreferrer">
							<?php echo $social['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Inline SVG. ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
	<?php
	return (string) ob_get_clean();
}

/**
 * Print the extras inside a hidden holder. The front-end script relocates this
 * into the top bar (or builds a matching strip if the top bar is absent).
 *
 * @since 1.0.3
 * @return void
 */
add_action(
	'wp_footer',
	function () {
		if ( ! blogsy_news_top_bar_enabled() ) {
			return;
		}

		echo '<div id="blogsy-news-topbar-extras-holder" hidden>' . blogsy_news_top_bar_extras_markup() . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Markup escaped at build time.
	},
	5
);
