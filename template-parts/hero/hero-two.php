<?php
/**
 * The template for displaying Hero Two — News Banner.
 *
 * @package     Blogsy_News
 * @author      Peregrine Themes
 * @since       1.0.0
 */

use Blogsy\Helper;

$sections = $args['sections'] ?? [];

if ( empty( $sections ) ) {
	return;
}

$editor_query_label   = $sections['editor']['label'];
$editor_query         = $sections['editor']['query'] ?? null;
$main_query_label     = $sections['main']['label'];
$main_query           = $sections['main']['query'] ?? null;
$main_slider_settings = $args['main_slider_settings'] ?? [];

$divider_style = Helper::get_option( 'divider_style' );
?>

<div class="pt-container pt-hero-type-two">
	<div class="pt-row pt-g-2">
		<!-- Hero editor -->
		<div class="pt-col-md-6 pt-col-12">
			<div class="blogsy-post-nexo-widget hero-editor">
				<?php if ( ! empty( $editor_query_label ) ) : ?>
				<div class="blogsy-section-heading pt-mb-1">
					<div class="blogsy-divider-heading divider-style-<?php echo esc_attr( $divider_style ); ?>">
						<div class="divider divider-1"></div>
						<div class="divider divider-2"></div>
						<h4 class="title">
							<span class="title-inner">
								<span class="title-text"><?php echo esc_html( $editor_query_label ); ?></span>
							</span>
						</h4>
						<div class="divider divider-3"></div>
						<div class="divider divider-4"></div>
					</div>
				</div>
				<?php endif; ?>
				<div class="blogsy-posts-wrapper layout-grid">
					<?php
					while ( $editor_query->have_posts() ) :
						$editor_query->the_post();
						?>
					<div class="post-item">
						<article class="post-wrapper">
							<a class="item-link blogsy-position-cover" aria-label="Item Link" href="<?php the_permalink(); ?>"></a>
							<div class="image-wrapper">
								<?php the_post_thumbnail( 'blogsy-small' ); ?>
							</div>
							<div class="content-wrapper blogsy-position-bottom style-2">
								<div class="content-wrapper-inner">
									<div class="terms-wrapper">
										<?php blogsy_entry_meta_category( ' ', false, apply_filters( 'blogsy_hero_two_right_category_limit', 1 ) ); ?>
									</div>
									<h4 class="title">
										<a href="<?php the_permalink(); ?>" class="title-animation-underline" title="<?php the_title_attribute(); ?>"> <?php the_title(); ?> </a>
									</h4>
									<div class="meta-wrapper">
										<?php blogsy_entry_meta_author(); ?>
										<div class="comments-wrapper">
											<?php echo \Blogsy\Icon::get_svg( 'comment', '', [ 'aria-hidden' => 'true' ] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
											<span class="comments">
												<a href="<?php comments_link(); ?>"><?php echo esc_html( get_comments_number() ); ?></a>
											</span>
										</div>
									</div>
								</div>
							</div>
						</article>
					</div>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
				</div>
			</div>
		</div>

		<!-- Hero slider -->
		<div class="pt-col-md-6 pt-col-12">
			<div class="pt-hero-slider blogsy-post-nexo-widget hero-slider blogsy-carousel-effect-fade">
				<?php if ( ! empty( $main_query_label ) ) : ?>
				<div class="blogsy-section-heading pt-mb-1">
					<div class="blogsy-divider-heading divider-style-<?php echo esc_attr( $divider_style ); ?>">
						<div class="divider divider-1"></div>
						<div class="divider divider-2"></div>
						<h4 class="title">
							<span class="title-inner">
								<span class="title-text"><?php echo esc_html( $main_query_label ); ?></span>
							</span>
						</h4>
						<div class="divider divider-3"></div>
						<div class="divider divider-4"></div>
					</div>
				</div>
				<?php endif; ?>
				<div class="blogsy-posts-wrapper layout-carousel">
					<div id="blogsy-hero-nexo-slider" class="blogsy-posts-carousel-wrapper" data-settings='<?php echo esc_attr( wp_json_encode( $main_slider_settings ) ); ?>'>
						<div class="swiper main-slider">
							<div class="swiper-wrapper">
								<?php if ( $main_query && $main_query->have_posts() ) : ?>
									<?php
									while ( $main_query->have_posts() ) :
										$main_query->the_post();
										?>
									<div class="post-item swiper-slide">
										<article class="post-wrapper">
											<a class="item-link blogsy-position-cover" aria-label="<?php the_title_attribute(); ?>" href="<?php the_permalink(); ?>"></a>
											<div class="image-wrapper">
												<?php the_post_thumbnail( 'blogsy-wide' ); ?>
											</div>
											<div class="content-wrapper blogsy-position-bottom style-1">
												<div class="content-wrapper-inner">
													<div class="terms-wrapper">
														<?php blogsy_entry_meta_category( ' ', false, apply_filters( 'blogsy_hero_one_category_limit', 5 ) ); ?>
													</div>
													<h3 class="title"><a class="title-animation-underline" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
													<div class="meta-wrapper">
														<?php echo blogsy_entry_meta_author( true ); ?>
														<?php echo blogsy_entry_meta_date( [ 'show_date_icon' => true ], true ); ?>
													</div>
												</div>
											</div>
										</article>
									</div>
									<?php endwhile; ?>
									<?php wp_reset_postdata(); ?>
								<?php endif; ?>
							</div>
						</div>
						<div class="carousel-nav-wrapper blogsy-position-default show-on-hover nav-style-1 blogsy-hide-mobile-tablet">
							<a href="javascript:void(0);" class="carousel-nav-prev" tabindex="0" role="button" aria-label="Previous slide" aria-controls="swiper-wrapper-hero">
								<?php echo \Blogsy\Icon::get_svg( 'arrow-right-long', '', [ 'aria-hidden' => 'true' ] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</a>
							<a href="javascript:void(0);" class="carousel-nav-next" tabindex="0" role="button" aria-label="Next slide" aria-controls="swiper-wrapper-hero">
								<?php echo \Blogsy\Icon::get_svg( 'arrow-right-long', '', [ 'aria-hidden' => 'true' ] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</a>
						</div>
						<div class="carousel-pagination-wrapper type-bullets blogsy-position-center-right style-1 blogsy-hide-mobile">
							<div class="carousel-pagination"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
