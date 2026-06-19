<?php
/**
 * Adsterra Ads integration.
 *
 * Provides a self-contained admin settings page (no parent-theme customizer
 * framework required) where ad scripts are pasted per placement zone, plus a
 * rendering engine that prints those scripts at reliable WordPress core hooks,
 * a [adsterra] shortcode and a sidebar widget.
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
 * Option key where all Adsterra settings live.
 */
if ( ! defined( 'BLOGSY_NEWS_ADSTERRA_OPTION' ) ) {
	define( 'BLOGSY_NEWS_ADSTERRA_OPTION', 'blogsy_news_adsterra_options' );
}

/**
 * Return the schema describing every Adsterra placement zone.
 *
 * @since 1.0.2
 * @return array
 */
function blogsy_news_adsterra_zones() {
	return [
		'head_code'    => [
			'label'       => esc_html__( 'Head / Verification Code', 'blogsy-news' ),
			'description' => esc_html__( 'Printed inside <head>. Use for Adsterra site-verification meta tags or the Popunder / monetag verification snippet.', 'blogsy-news' ),
			'group'       => 'global',
		],
		'top_banner'   => [
			'label'       => esc_html__( 'Top Banner (above header)', 'blogsy-news' ),
			'description' => esc_html__( 'Rendered at the very top of the page via wp_body_open. Great for a 728x90 leaderboard / responsive banner.', 'blogsy-news' ),
			'group'       => 'placement',
		],
		'before_content' => [
			'label'       => esc_html__( 'Before Post Content', 'blogsy-news' ),
			'description' => esc_html__( 'Shown at the start of single post content. Recommended: 300x250 or responsive banner.', 'blogsy-news' ),
			'group'       => 'placement',
		],
		'in_content'   => [
			'label'       => esc_html__( 'In-Content (after N paragraphs)', 'blogsy-news' ),
			'description' => esc_html__( 'Injected inside single post content after the paragraph number set below. Native Banner works well here.', 'blogsy-news' ),
			'group'       => 'placement',
		],
		'after_content' => [
			'label'       => esc_html__( 'After Post Content', 'blogsy-news' ),
			'description' => esc_html__( 'Shown at the end of single post content, before comments.', 'blogsy-news' ),
			'group'       => 'placement',
		],
		'before_footer' => [
			'label'       => esc_html__( 'Before Footer', 'blogsy-news' ),
			'description' => esc_html__( 'Full-width slot rendered just above the site footer on every page.', 'blogsy-news' ),
			'group'       => 'placement',
		],
		'footer'       => [
			'label'       => esc_html__( 'Inside Footer', 'blogsy-news' ),
			'description' => esc_html__( 'Rendered within the footer area, above the copyright bar.', 'blogsy-news' ),
			'group'       => 'placement',
		],
		'sidebar'      => [
			'label'       => esc_html__( 'Sidebar / Widget', 'blogsy-news' ),
			'description' => esc_html__( 'Use the "Blogsy News: Adsterra Ad" widget (Appearance → Widgets) or the [adsterra zone="sidebar"] shortcode to place this.', 'blogsy-news' ),
			'group'       => 'placement',
		],
	];
}

/**
 * Get the saved Adsterra settings, merged with defaults.
 *
 * @since 1.0.2
 * @return array
 */
function blogsy_news_adsterra_settings() {
	$defaults = [
		'enabled'             => 0,
		'hide_for_logged_in'  => 1,
		'in_content_paragraph' => 3,
	];

	foreach ( blogsy_news_adsterra_zones() as $key => $zone ) {
		$defaults[ $key ] = '';
	}

	$saved = get_option( BLOGSY_NEWS_ADSTERRA_OPTION, [] );
	$saved = is_array( $saved ) ? $saved : [];

	return wp_parse_args( $saved, $defaults );
}

/**
 * Get a single Adsterra setting value.
 *
 * @since 1.0.2
 * @param string $key     Setting key.
 * @param mixed  $default Fallback value.
 * @return mixed
 */
function blogsy_news_adsterra_get( $key, $default = '' ) {
	$settings = blogsy_news_adsterra_settings();
	return $settings[ $key ] ?? $default;
}

/**
 * Whether ads are allowed to render in the current request.
 *
 * @since 1.0.2
 * @return bool
 */
function blogsy_news_adsterra_active() {
	$settings = blogsy_news_adsterra_settings();

	if ( empty( $settings['enabled'] ) ) {
		return false;
	}

	if ( ! empty( $settings['hide_for_logged_in'] ) && is_user_logged_in() ) {
		return false;
	}

	// Never serve ads inside the block editor / customizer preview, feeds or REST.
	if ( is_admin() || is_feed() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
		return false;
	}

	/**
	 * Filter whether Adsterra ads render for this request.
	 *
	 * @param bool $active Whether ads are active.
	 */
	return (bool) apply_filters( 'blogsy_news_adsterra_active', true );
}

/**
 * Domains known to serve popunder / auto-redirect ads.
 *
 * Scripts that load a popunder / social-bar unit from one of these domains are
 * stripped from every ad zone before output, so they can never open a new tab
 * (e.g. openadblocker.com), regardless of what is saved in the settings.
 *
 * EVERY script from these domains is removed — including their "invoke.js"
 * banner units, because for these networks the banner script ALSO triggers the
 * popunder/redirect (it is decided server-side, which is why it is intermittent).
 * Ad code from networks NOT on this list (e.g. highperformanceformat.com display
 * banners, Google AdSense) is never touched, so clean ads keep working.
 *
 * @since 1.0.4
 * @return string[]
 */
function blogsy_news_adsterra_blocked_domains() {
	return apply_filters(
		'blogsy_news_adsterra_blocked_domains',
		[
			'effectivecpmnetwork.com',
			'propellerads.com',
			'propellerclick.com',
			'propu.net',
			'monetag.com',
			'onclickalgo.com',
			'onclckpdara.com',
		]
	);
}

/**
 * Strip popunder / redirect ad scripts from a snippet.
 *
 * @since 1.0.4
 * @param string $code Raw ad snippet.
 * @return string
 */
function blogsy_news_adsterra_strip_popunders( $code ) {
	if ( '' === $code ) {
		return $code;
	}

	$domains = array_filter( array_map( 'trim', blogsy_news_adsterra_blocked_domains() ) );

	if ( empty( $domains ) ) {
		return trim( (string) $code );
	}

	$domain_re = implode(
		'|',
		array_map(
			static function ( $d ) {
				return preg_quote( $d, '#' );
			},
			$domains
		)
	);

	// Remove any <script src="…"> that loads from a blocked popunder network —
	// banners (invoke.js) included, because those scripts also pop. Ad code from
	// networks not on the list (highperformanceformat.com, AdSense, …) is kept.
	$code = preg_replace_callback(
		'#<script\b[^>]*?\bsrc\s*=\s*["\']([^"\']+)["\'][^>]*>\s*(?:</script>)?#is',
		static function ( $m ) use ( $domain_re ) {
			return preg_match( '#' . $domain_re . '#i', $m[1] ) ? '' : $m[0];
		},
		(string) $code
	);

	// Drop now-empty native-banner container divs left behind by a stripped script.
	$code = preg_replace( '#<div\b[^>]*\bid\s*=\s*["\']container-[0-9a-f]{16,}["\'][^>]*>\s*</div>#is', '', (string) $code );

	return trim( (string) $code );
}

/**
 * Build the markup for a single ad zone.
 *
 * Ad network snippets contain <script> and are stored / output verbatim — they
 * are only editable by users who can manage options, so they are trusted code.
 * Popunder / redirect networks are stripped first (see blocklist above).
 *
 * @since 1.0.2
 * @param string $zone Zone key.
 * @return string
 */
function blogsy_news_adsterra_markup( $zone ) {
	$code = trim( (string) blogsy_news_adsterra_get( $zone ) );
	$code = blogsy_news_adsterra_strip_popunders( $code );

	if ( '' === $code ) {
		return '';
	}

	// Global zones (head/social bar/popunder) print raw, no wrapper.
	$zones = blogsy_news_adsterra_zones();
	$group = $zones[ $zone ]['group'] ?? 'placement';

	if ( 'global' === $group ) {
		return $code;
	}

	$label = apply_filters( 'blogsy_news_adsterra_label', esc_html__( 'Advertisement', 'blogsy-news' ), $zone );

	$markup  = '<div class="blogsy-ad blogsy-ad--' . esc_attr( $zone ) . '" aria-hidden="true">';
	$markup .= $label ? '<span class="blogsy-ad__label">' . esc_html( $label ) . '</span>' : '';
	$markup .= '<div class="blogsy-ad__inner">' . $code . '</div>';
	$markup .= '</div>';

	return $markup;
}

/**
 * Echo / return an ad zone, respecting the active state.
 *
 * @since 1.0.2
 * @param string $zone Zone key.
 * @param bool   $echo Whether to echo (default true).
 * @return string
 */
function blogsy_news_adsterra_render( $zone, $echo = true ) {
	if ( ! blogsy_news_adsterra_active() ) {
		return '';
	}

	$markup = blogsy_news_adsterra_markup( $zone );

	if ( $echo ) {
		echo $markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Trusted ad-network markup.
	}

	return $markup;
}

/* -------------------------------------------------------------------------
 * Front-end hooks
 * ---------------------------------------------------------------------- */

/**
 * Head / verification code.
 */
add_action(
	'wp_head',
	function () {
		if ( blogsy_news_adsterra_active() ) {
			echo blogsy_news_adsterra_markup( 'head_code' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	},
	5
);

/**
 * Top banner — fires right after the opening <body> tag.
 */
add_action(
	'wp_body_open',
	function () {
		blogsy_news_adsterra_render( 'top_banner' );
	},
	20
);

/**
 * Inject before / in / after content ads on single posts.
 *
 * @param string $content Post content.
 * @return string
 */
function blogsy_news_adsterra_content( $content ) {
	if ( ! blogsy_news_adsterra_active() || ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}

	$before = blogsy_news_adsterra_markup( 'before_content' );
	$after  = blogsy_news_adsterra_markup( 'after_content' );
	$inline = blogsy_news_adsterra_markup( 'in_content' );

	// In-content: insert after the Nth top-level paragraph by rebuilding the
	// content around the </p> delimiters (no stray tags). If there are fewer
	// paragraphs than requested, the ad lands at the end.
	if ( '' !== $inline ) {
		$paragraph = max( 1, (int) blogsy_news_adsterra_get( 'in_content_paragraph', 3 ) );
		$parts     = explode( '</p>', $content );
		$total     = count( $parts );
		$insert_at = min( $paragraph, $total );
		$rebuilt   = '';

		foreach ( $parts as $i => $part ) {
			// Re-attach the closing tag we split on (all but the trailing piece).
			if ( $i < $total - 1 ) {
				$part .= '</p>';
			}
			$rebuilt .= $part;

			if ( $i === $insert_at - 1 ) {
				$rebuilt .= $inline;
			}
		}

		$content = $rebuilt;
	}

	return $before . $content . $after;
}
add_filter( 'the_content', 'blogsy_news_adsterra_content', 20 );

/**
 * [adsterra zone="before_footer"] shortcode for manual placement.
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function blogsy_news_adsterra_shortcode( $atts ) {
	$atts = shortcode_atts( [ 'zone' => 'sidebar' ], $atts, 'adsterra' );
	return blogsy_news_adsterra_render( $atts['zone'], false );
}
add_shortcode( 'adsterra', 'blogsy_news_adsterra_shortcode' );

/* -------------------------------------------------------------------------
 * Sidebar widget
 * ---------------------------------------------------------------------- */

/**
 * Adsterra sidebar widget.
 */
class Blogsy_News_Adsterra_Widget extends WP_Widget {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct(
			'blogsy_news_adsterra_widget',
			esc_html__( 'Blogsy News: Adsterra Ad', 'blogsy-news' ),
			[ 'description' => esc_html__( 'Displays the Adsterra "Sidebar / Widget" ad zone.', 'blogsy-news' ) ]
		);
	}

	/**
	 * Front-end output.
	 *
	 * @param array $args     Widget args.
	 * @param array $instance Widget instance.
	 */
	public function widget( $args, $instance ) {
		$markup = blogsy_news_adsterra_render( 'sidebar', false );

		if ( '' === $markup ) {
			return;
		}

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . esc_html( $instance['title'] ) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		echo $markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Back-end form.
	 *
	 * @param array $instance Widget instance.
	 */
	public function form( $instance ) {
		$title = $instance['title'] ?? '';
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title (optional):', 'blogsy-news' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p class="description"><?php esc_html_e( 'Paste the ad code under Adsterra Ads → Sidebar / Widget.', 'blogsy-news' ); ?></p>
		<?php
	}

	/**
	 * Save.
	 *
	 * @param array $new New instance.
	 * @param array $old Old instance.
	 * @return array
	 */
	public function update( $new, $old ) {
		return [ 'title' => sanitize_text_field( $new['title'] ?? '' ) ];
	}
}

add_action(
	'widgets_init',
	function () {
		register_widget( 'Blogsy_News_Adsterra_Widget' );
	}
);

/* -------------------------------------------------------------------------
 * Admin settings page
 * ---------------------------------------------------------------------- */

/**
 * Register the admin menu page.
 */
add_action(
	'admin_menu',
	function () {
		add_menu_page(
			esc_html__( 'Adsterra Ads', 'blogsy-news' ),
			esc_html__( 'Adsterra Ads', 'blogsy-news' ),
			'manage_options',
			'blogsy-news-adsterra',
			'blogsy_news_adsterra_settings_page',
			'dashicons-money-alt',
			59
		);
	}
);

/**
 * Register settings.
 */
add_action(
	'admin_init',
	function () {
		register_setting(
			'blogsy_news_adsterra_group',
			BLOGSY_NEWS_ADSTERRA_OPTION,
			[
				'type'              => 'array',
				'sanitize_callback' => 'blogsy_news_adsterra_sanitize',
			]
		);
	}
);

/**
 * Sanitize the settings.
 *
 * Ad snippets contain <script> by design and are only editable by users with
 * `manage_options`, so the code zones are stored verbatim (matching the
 * behaviour of header/footer code plugins). Non-code fields are sanitised.
 *
 * @param array $input Raw input.
 * @return array
 */
function blogsy_news_adsterra_sanitize( $input ) {
	$input  = is_array( $input ) ? $input : [];
	$output = [];

	$output['enabled']            = empty( $input['enabled'] ) ? 0 : 1;
	$output['hide_for_logged_in'] = empty( $input['hide_for_logged_in'] ) ? 0 : 1;
	$output['in_content_paragraph'] = isset( $input['in_content_paragraph'] ) ? max( 1, absint( $input['in_content_paragraph'] ) ) : 3;

	foreach ( blogsy_news_adsterra_zones() as $key => $zone ) {
		// Trusted code, kept verbatim. Only managers reach this code path.
		$output[ $key ] = isset( $input[ $key ] ) ? trim( (string) $input[ $key ] ) : '';
	}

	return $output;
}

/**
 * Render the settings page.
 */
function blogsy_news_adsterra_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$settings = blogsy_news_adsterra_settings();
	$zones    = blogsy_news_adsterra_zones();
	$opt      = BLOGSY_NEWS_ADSTERRA_OPTION;
	?>
	<div class="wrap blogsy-adsterra-wrap">
		<h1><span class="dashicons dashicons-money-alt"></span> <?php esc_html_e( 'Adsterra Ads', 'blogsy-news' ); ?></h1>
		<p class="description" style="max-width:760px;">
			<?php esc_html_e( 'Paste your Adsterra ad snippets into the matching placement below, then enable ads at the top. Each box accepts the full <script> snippet Adsterra gives you for that ad unit.', 'blogsy-news' ); ?>
		</p>

		<?php if ( empty( $settings['enabled'] ) ) : ?>
			<div class="notice notice-warning inline"><p><strong><?php esc_html_e( 'Ads are currently disabled.', 'blogsy-news' ); ?></strong> <?php esc_html_e( 'Toggle "Enable ads" below and save to start serving.', 'blogsy-news' ); ?></p></div>
		<?php endif; ?>

		<form method="post" action="options.php">
			<?php settings_fields( 'blogsy_news_adsterra_group' ); ?>

			<div class="blogsy-ads-card">
				<h2><?php esc_html_e( 'General', 'blogsy-news' ); ?></h2>
				<table class="form-table" role="presentation">
					<tr>
						<th scope="row"><?php esc_html_e( 'Enable ads', 'blogsy-news' ); ?></th>
						<td>
							<label>
								<input type="checkbox" name="<?php echo esc_attr( $opt ); ?>[enabled]" value="1" <?php checked( $settings['enabled'], 1 ); ?>>
								<?php esc_html_e( 'Serve Adsterra ads on the front end.', 'blogsy-news' ); ?>
							</label>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Hide for logged-in users', 'blogsy-news' ); ?></th>
						<td>
							<label>
								<input type="checkbox" name="<?php echo esc_attr( $opt ); ?>[hide_for_logged_in]" value="1" <?php checked( $settings['hide_for_logged_in'], 1 ); ?>>
								<?php esc_html_e( 'Do not show ads to logged-in users (recommended).', 'blogsy-news' ); ?>
							</label>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="blogsy-in-content-para"><?php esc_html_e( 'In-content paragraph', 'blogsy-news' ); ?></label></th>
						<td>
							<input type="number" min="1" max="50" id="blogsy-in-content-para" name="<?php echo esc_attr( $opt ); ?>[in_content_paragraph]" value="<?php echo esc_attr( $settings['in_content_paragraph'] ); ?>" class="small-text">
							<p class="description"><?php esc_html_e( 'The "In-Content" ad is inserted after this paragraph number.', 'blogsy-news' ); ?></p>
						</td>
					</tr>
				</table>
			</div>

			<div class="blogsy-ads-card">
				<h2><?php esc_html_e( 'Placements', 'blogsy-news' ); ?></h2>
				<?php
				foreach ( $zones as $key => $zone ) :
					if ( 'placement' !== $zone['group'] ) {
						continue;
					}
					?>
					<div class="blogsy-ads-field">
						<label for="blogsy-ad-<?php echo esc_attr( $key ); ?>"><strong><?php echo esc_html( $zone['label'] ); ?></strong></label>
						<p class="description"><?php echo esc_html( $zone['description'] ); ?></p>
						<textarea id="blogsy-ad-<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $opt ); ?>[<?php echo esc_attr( $key ); ?>]" rows="4" class="large-text code" placeholder="<?php esc_attr_e( '<script>...your Adsterra snippet...</script>', 'blogsy-news' ); ?>"><?php echo esc_textarea( $settings[ $key ] ); ?></textarea>
					</div>
				<?php endforeach; ?>
			</div>

			<div class="blogsy-ads-card">
				<h2><?php esc_html_e( 'Global Scripts', 'blogsy-news' ); ?></h2>
				<?php
				foreach ( $zones as $key => $zone ) :
					if ( 'global' !== $zone['group'] ) {
						continue;
					}
					?>
					<div class="blogsy-ads-field">
						<label for="blogsy-ad-<?php echo esc_attr( $key ); ?>"><strong><?php echo esc_html( $zone['label'] ); ?></strong></label>
						<p class="description"><?php echo esc_html( $zone['description'] ); ?></p>
						<textarea id="blogsy-ad-<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $opt ); ?>[<?php echo esc_attr( $key ); ?>]" rows="4" class="large-text code" placeholder="<?php esc_attr_e( '<script>...your Adsterra snippet...</script>', 'blogsy-news' ); ?>"><?php echo esc_textarea( $settings[ $key ] ); ?></textarea>
					</div>
				<?php endforeach; ?>
			</div>

			<?php submit_button( esc_html__( 'Save Ad Settings', 'blogsy-news' ) ); ?>
		</form>
	</div>

	<style>
		.blogsy-adsterra-wrap h1 .dashicons { font-size: 28px; width: 28px; height: 28px; vertical-align: -4px; color: #e93314; }
		.blogsy-ads-card { background: #fff; border: 1px solid #dcdcde; border-radius: 8px; padding: 8px 22px 18px; margin: 18px 0; max-width: 860px; box-shadow: 0 1px 2px rgba(0,0,0,.04); }
		.blogsy-ads-card h2 { border-bottom: 1px solid #f0f0f1; padding-bottom: 12px; }
		.blogsy-ads-field { padding: 14px 0; border-bottom: 1px solid #f0f0f1; }
		.blogsy-ads-field:last-child { border-bottom: 0; }
		.blogsy-ads-field label strong { font-size: 14px; }
		.blogsy-ads-field .description { margin: 4px 0 8px; }
		.blogsy-ads-field textarea { font-size: 12px; }
	</style>
	<?php
}
