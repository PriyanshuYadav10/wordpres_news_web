<?php
/**
 * Google Search Console site verification (HTML file method).
 *
 * Google fetches https://example.com/google80fcbe7d72b7324f.html and expects
 * the body to be the verification token. Rather than uploading a physical file
 * to the web root, the theme answers that exact request virtually and prints
 * the token, then exits before WordPress renders a 404.
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
 * Verification file name Google asked us to serve.
 */
if ( ! defined( 'BLOGSY_NEWS_GOOGLE_VERIFY_FILE' ) ) {
	define( 'BLOGSY_NEWS_GOOGLE_VERIFY_FILE', 'google80fcbe7d72b7324f.html' );
}

/**
 * Serve the Google verification file from the site root.
 *
 * @since 1.0.3
 * @return void
 */
function blogsy_news_google_site_verification() {
	$request = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
	$path    = (string) wp_parse_url( $request, PHP_URL_PATH );

	if ( '/' . BLOGSY_NEWS_GOOGLE_VERIFY_FILE !== $path ) {
		return;
	}

	header( 'Content-Type: text/html; charset=UTF-8' );
	header( 'X-Robots-Tag: noindex', true );
	echo 'google-site-verification: ' . BLOGSY_NEWS_GOOGLE_VERIFY_FILE; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Fixed token.
	exit;
}
add_action( 'init', 'blogsy_news_google_site_verification', 0 );
