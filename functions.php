<?php
/**
 * Theme functions and definitions.
 *
 * @package Blogsy News
 * @author  Peregrine Themes
 * @since   1.0.0
 */

/**
 * Main Blogsy News class.
 *
 * @since 1.0.0
 */
final class Blogsy_News {

	/**
	 * Instance
	 *
	 * @var null|self $instance instance variable.
	 */
	protected static ?self $instance = null;

	/**
	 * Returns the single instance of the class.
	 *
	 * @since 1.0.0
	 * @return object The single instance of the class.
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof self ) ) {
			self::$instance = new self();
			// Hook now that all of the Blogsy News stuff is loaded.
			do_action( 'blogsy_news_loaded' );
		}
		return self::$instance;
	}

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __construct() {
		add_action( 'after_setup_theme', [ $this, 'include_files' ], 20 );
		add_action( 'wp_enqueue_scripts', [ $this, 'blogsy_news_styles' ] );
		add_filter( 'body_class', [ $this, 'blogsy_news_body_classes' ] );

		if ( ! defined( 'BLOGSY_NEWS_THEME_VERSION' ) ) {
			define( 'BLOGSY_NEWS_THEME_VERSION', wp_get_theme()->get( 'Version' ) );
		}
	}

	/**
	 * Include files.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function include_files() {
		require get_stylesheet_directory() . '/inc/hooks.php';
		require get_stylesheet_directory() . '/inc/customizer/options.php';
		require get_stylesheet_directory() . '/inc/customizer/settings.php';
		require get_stylesheet_directory() . '/inc/top-bar.php';
		require get_stylesheet_directory() . '/inc/pages.php';
		require get_stylesheet_directory() . '/inc/footer.php';
		require get_stylesheet_directory() . '/inc/adsterra.php';
		require get_stylesheet_directory() . '/inc/analytics.php';
		require get_stylesheet_directory() . '/inc/site-verification.php';
	}

	/**
	 * Adds custom classes to the array of body classes.
	 *
	 * @since 1.0.0
	 * @param array $classes Classes for the body element.
	 * @return array
	 */
	public function blogsy_news_body_classes( $classes ) {
		// Site layout.
		$classes[] = 'blogsy_news';

		return $classes;
	}

	/**
	 * Recommended way to include parent theme styles.
	 * (Please see http://codex.wordpress.org/Child_Themes#How_to_Create_a_Child_Theme)
	 *
	 * @since 1.0.0
	 */
	public function blogsy_news_styles() {
		wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
		wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', [ 'parent-style' ], BLOGSY_NEWS_THEME_VERSION );

		if ( is_rtl() ) {
			wp_enqueue_style(
				'parent-rtl',
				get_template_directory_uri() . '/rtl.css',
				[ 'parent-style' ],
				wp_get_theme()->get( 'Version' )
			);
		}

		// Front-end interactions (back-to-top, newsletter).
		wp_enqueue_script(
			'blogsy-news',
			get_stylesheet_directory_uri() . '/assets/js/blogsy-news.js',
			[],
			BLOGSY_NEWS_THEME_VERSION,
			true
		);
	}
}

/**
 * The function which returns the one Blogsy News instance.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $blogsy_news = blogsy_news(); ?>
 *
 * @since 1.0.0
 * @return object
 */
function blogsy_news() {
	return Blogsy_News::instance();
}

blogsy_news();
