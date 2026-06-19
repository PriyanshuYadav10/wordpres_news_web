<?php
/**
 * The custom Blogsy News footer markup.
 *
 * @package Blogsy News
 * @author  Peregrine Themes
 * @since   1.0.2
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$blogsy_news_about = get_bloginfo( 'description' );
/**
 * Filter the footer "about" blurb.
 *
 * @param string $about Default: the site tagline.
 */
$blogsy_news_about = apply_filters( 'blogsy_news_footer_about', $blogsy_news_about ?: esc_html__( 'Fresh perspectives, trusted reporting, and the stories that matter — delivered daily.', 'blogsy-news' ) );

$blogsy_news_socials = blogsy_news_footer_socials();

// Copyright line.
$blogsy_news_copyright = sprintf(
	/* translators: 1: year, 2: site name. */
	esc_html__( '© %1$s %2$s. All rights reserved.', 'blogsy-news' ),
	date_i18n( 'Y' ),
	get_bloginfo( 'name' )
);
$blogsy_news_copyright = apply_filters( 'blogsy_news_footer_copyright', $blogsy_news_copyright );

$blogsy_news_newsletter_action = apply_filters( 'blogsy_news_newsletter_action', '#' );
?>

<footer class="blogsy-news-footer" role="contentinfo">
	<div class="blogsy-news-footer__main">
		<div class="pt-container">
			<div class="blogsy-news-footer__grid">

				<!-- Brand / About -->
				<div class="blogsy-news-footer__col blogsy-news-footer__brand">
					<div class="blogsy-news-footer__logo">
						<?php if ( function_exists( 'has_custom_logo' ) && has_custom_logo() ) : ?>
							<?php the_custom_logo(); ?>
						<?php else : ?>
							<a class="blogsy-news-footer__site-title" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
								<?php bloginfo( 'name' ); ?>
							</a>
						<?php endif; ?>
					</div>

					<p class="blogsy-news-footer__about"><?php echo esc_html( $blogsy_news_about ); ?></p>

					<?php if ( ! empty( $blogsy_news_socials ) ) : ?>
						<ul class="blogsy-news-footer__socials">
							<?php foreach ( $blogsy_news_socials as $key => $social ) : ?>
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

				<!-- Quick Links -->
				<div class="blogsy-news-footer__col blogsy-news-footer__links">
					<h3 class="blogsy-news-footer__heading"><?php esc_html_e( 'Quick Links', 'blogsy-news' ); ?></h3>
					<?php
					if ( has_nav_menu( 'blogsy_news_footer' ) ) {
						wp_nav_menu(
							[
								'theme_location' => 'blogsy_news_footer',
								'container'      => false,
								'menu_class'     => 'blogsy-news-footer__menu',
								'depth'          => 1,
								'fallback_cb'    => false,
							]
						);
					} else {
						echo '<ul class="blogsy-news-footer__menu">';
						wp_list_pages(
							[
								'title_li' => '',
								'number'   => 6,
								'depth'    => 1,
								'sort_column' => 'menu_order, post_title',
							]
						);
						echo '</ul>';
					}
					?>
				</div>

				<!-- Categories -->
				<div class="blogsy-news-footer__col blogsy-news-footer__cats">
					<h3 class="blogsy-news-footer__heading"><?php esc_html_e( 'Categories', 'blogsy-news' ); ?></h3>
					<ul class="blogsy-news-footer__menu">
						<?php
						wp_list_categories(
							[
								'title_li'   => '',
								'number'     => 6,
								'orderby'    => 'count',
								'order'      => 'DESC',
								'show_count' => true,
								'depth'      => 1,
							]
						);
						?>
					</ul>
				</div>

				<!-- Newsletter -->
				<div class="blogsy-news-footer__col blogsy-news-footer__newsletter">
					<h3 class="blogsy-news-footer__heading"><?php esc_html_e( 'Stay in the loop', 'blogsy-news' ); ?></h3>
					<p class="blogsy-news-footer__news-text"><?php esc_html_e( 'Get the week’s best stories in your inbox. No spam, ever.', 'blogsy-news' ); ?></p>
					<form class="blogsy-news-newsletter" action="<?php echo esc_url( $blogsy_news_newsletter_action ); ?>" method="post" novalidate>
						<div class="blogsy-news-newsletter__field">
							<input type="email" name="blogsy_news_email" class="blogsy-news-newsletter__input" placeholder="<?php esc_attr_e( 'Your email address', 'blogsy-news' ); ?>" aria-label="<?php esc_attr_e( 'Your email address', 'blogsy-news' ); ?>" required>
							<button type="submit" class="blogsy-news-newsletter__btn">
								<?php esc_html_e( 'Subscribe', 'blogsy-news' ); ?>
							</button>
						</div>
						<p class="blogsy-news-newsletter__msg" role="status" aria-live="polite"></p>
					</form>
				</div>

			</div><!-- .blogsy-news-footer__grid -->

			<?php
			// In-footer ad zone.
			if ( function_exists( 'blogsy_news_adsterra_render' ) ) {
				blogsy_news_adsterra_render( 'footer' );
			}
			?>
		</div><!-- .pt-container -->
	</div><!-- .blogsy-news-footer__main -->

	<div class="blogsy-news-footer__bottom">
		<div class="pt-container">
			<div class="blogsy-news-footer__bottom-inner">
				<p class="blogsy-news-footer__copyright"><?php echo wp_kses_post( $blogsy_news_copyright ); ?></p>

				<?php
				if ( has_nav_menu( 'blogsy_news_footer_bottom' ) ) {
					wp_nav_menu(
						[
							'theme_location' => 'blogsy_news_footer_bottom',
							'container'      => false,
							'menu_class'     => 'blogsy-news-footer__bottom-menu',
							'depth'          => 1,
							'fallback_cb'    => false,
						]
					);
				}
				?>
			</div>
		</div>
	</div><!-- .blogsy-news-footer__bottom -->
</footer><!-- .blogsy-news-footer -->

<button type="button" class="blogsy-news-back-to-top" aria-label="<?php esc_attr_e( 'Back to top', 'blogsy-news' ); ?>">
	<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
		<path d="M18 15l-6-6-6 6"/>
	</svg>
</button>
