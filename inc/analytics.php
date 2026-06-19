<?php
/**
 * Google Analytics (gtag.js) integration.
 *
 * Prints the GA4 tracking snippet in the document <head> for every front-end
 * visitor. Unlike the Adsterra "Head / Verification Code" box, this is not
 * gated by the ad on/off toggle, so analytics keeps collecting data regardless
 * of ad settings.
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
 * Google Analytics 4 Measurement ID.
 */
if ( ! defined( 'BLOGSY_NEWS_GA_ID' ) ) {
	define( 'BLOGSY_NEWS_GA_ID', 'G-DJVTMVYX0T' );
}

/**
 * Output the GA4 gtag.js snippet in <head>.
 *
 * @since 1.0.3
 * @return void
 */
function blogsy_news_google_analytics() {
	$ga_id = BLOGSY_NEWS_GA_ID;

	// Never load in the admin, feeds, REST or the customizer preview.
	if ( ! $ga_id || is_admin() || is_feed() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
		return;
	}

	/**
	 * Filter whether Google Analytics loads for this request.
	 *
	 * Return false (e.g. for logged-in admins) to skip tracking.
	 *
	 * @param bool $enabled Whether GA should load.
	 */
	if ( ! apply_filters( 'blogsy_news_google_analytics_enabled', true ) ) {
		return;
	}

	$ga_id = esc_js( $ga_id );
	?>
	<!-- Google tag (gtag.js) -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo rawurlencode( BLOGSY_NEWS_GA_ID ); ?>"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());
		gtag('config', '<?php echo $ga_id; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped with esc_js above. ?>');
	</script>
	<?php
}
add_action( 'wp_head', 'blogsy_news_google_analytics', 1 );
