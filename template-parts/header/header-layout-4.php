<?php
/**
 * The template for displaying header layout 1.
 *
 * @package Blogsy
 * @author Peregrine Themes
 * @since   1.0.0
 */

?>

<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="pt-logo-container">
	<div class="pt-container">
		<div class="pt-header-container">
			<?php do_action( 'blogsy_header_widget_location', [ 'left' ] ); ?>
			<?php blogsy_header_logo_template(); ?>
			<?php do_action( 'blogsy_header_widget_location', [ 'right' ] ); ?>

			<div class="pt-header-element pt-mobile-nav"><?php get_template_part( 'template-parts/header/mobile', 'navigation' ); ?></div>
		</div><!-- END .pt-header-container -->
	</div><!-- END .pt-container -->
</div><!-- END .pt-logo-container -->
<div class="pt-nav-container">
	<div class="pt-container">
		<div class="pt-header-container">
			<?php do_action( 'blogsy_header_navigation_widget_location', [ 'left' ] ); ?>
			<?php blogsy_main_navigation_template(); ?>
			<?php do_action( 'blogsy_header_navigation_widget_location', [ 'right' ] ); ?>
		</div><!-- END .pt-header-container -->
	</div><!-- END .pt-container -->
</div><!-- END .pt-nav-container -->
