<?php
/**
 * The template for displaying Hero Three — News Banner.
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

$main_query                 = $sections['main']['query'];
$main_query_label           = $sections['main']['label'];
$main_slider_settings       = $args['main_slider_settings'] ?? [];
$trending_query_label       = $sections['trending']['label'];
$trending_carousel_settings = $args['trending_carousel_settings'] ?? [];

$divider_style = Helper::get_option( 'divider_style' );
?>

<div class="pt-container pt-hero-type-three">
	<div class="pt-row pt-g-2">
		<!-- Tabs Navigation -->
		<div class="pt-col-md-3 pt-col-12">
			<div class="blogsy-post-elastic-widget hero-tabs">
				<div class="hero-tabs-inner">
					<?php
					foreach ( $sections as $section => $args ) :
						if ( in_array( $section, [ 'latest', 'popular', 'update' ] ) ) :
							?>
						<button class="tab-btn <?php echo $section === 'latest' ? 'active' : ''; ?>" data-tab="<?php echo esc_attr( $section ); ?>">
							<h5>
								<?php if ( 'latest' === $section ) : ?>
									<?php echo \Blogsy\Icon::get_svg( 'clock', '', [ 'aria-hidden' => 'true' ] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<?php elseif ( 'popular' === $section ) : ?>
									<?php echo \Blogsy\Icon::get_svg( 'bolt', '', [ 'aria-hidden' => 'true' ] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<?php elseif ( 'update' === $section ) : ?>
									<?php echo \Blogsy\Icon::get_svg( 'fire', '', [ 'aria-hidden' => 'true' ] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<?php endif; ?>
								<?php echo esc_html( $args['label'] ); ?>
							</h5>
						</button>
							<?php
						endif;
					endforeach;
					?>
				</div>
				<div class="blogsy-posts-wrapper layout-grid">
					<?php
					foreach ( $sections as $section => $args ) :
						if ( in_array( $section, [ 'latest', 'popular', 'update' ] ) ) :
							?>
						<div class="tab-content <?php echo $section === 'latest' ? 'active' : ''; ?>" data-tab-content="<?php echo esc_attr( $section ); ?>">
							<?php if ( $args['query'] && $args['query']->have_posts() ) : ?>
								<?php
								while ( $args['query']->have_posts() ) :
									$args['query']->the_post();
									?>
									<div class="post-item">
										<article class="post-wrapper mini-layout">
											<div class="post-inner">
												<div class="image-outer-wrapper">
													<div class="image-wrapper">
														<a class="blogsy-position-cover" href="<?php the_permalink(); ?>" aria-label="Item Link"></a>
														<?php the_post_thumbnail( 'thumbnail', [ 'title' => get_the_title() ] ); ?>
													</div>
												</div>
												<div class="content-wrapper">
													<div class="content-inner">
														<h6 class="title">
														<a href="<?php the_permalink(); ?>" class="title-animation-underline" title="<?php the_title_attribute(); ?>"> <?php the_title(); ?> </a>
														</h6>
														<div class="meta-wrapper">
															<?php echo blogsy_entry_meta_date( [ 'show_date_icon' => true ], true ); ?>
															<div class="comments-wrapper">
																<?php echo \Blogsy\Icon::get_svg( 'comment', '', [ 'aria-hidden' => 'true' ] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
																<span class="comments">
																	<a href="<?php comments_link(); ?>"><?php echo esc_html( get_comments_number() ); ?></a>
																</span>
															</div>
														</div>
													</div>
												</div>
											</div>
										</article>
									</div>
								<?php endwhile; ?>
								<?php wp_reset_postdata(); ?>
							<?php endif; ?>
						</div>
							<?php
						endif;
					endforeach;
					?>
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
						<div class="carousel-nav-wrapper blogsy-position-top-right nav-style-1">
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

		<!-- Hero trending -->
		<div class="pt-col-md-3 pt-col-12">
			<div class="blogsy-post-elastic-widget hero-trending">
				<?php if ( ! empty( $trending_query_label ) ) : ?>
				<div class="blogsy-section-heading pt-mb-1">
					<div class="blogsy-divider-heading divider-style-<?php echo esc_attr( $divider_style ); ?>">
						<div class="divider divider-1"></div>
						<div class="divider divider-2"></div>
						<h4 class="title">
							<span class="title-inner">
								<span class="title-text"><?php echo esc_html( $trending_query_label ); ?></span>
							</span>
						</h4>
						<div class="divider divider-3"></div>
						<div class="divider divider-4"></div>
					</div>
				</div>
				<?php endif; ?>
				<div class="blogsy-posts-wrapper layout-carousel">
					<div id="blogsy-hero-elastic-carousel" class="blogsy-posts-carousel-wrapper" data-settings='<?php echo esc_attr( wp_json_encode( $trending_carousel_settings ) ); ?>'>
						<div class="swiper">
							<div class="swiper-wrapper">
								<?php
								while ( $sections['trending']['query']->have_posts() ) :
									$sections['trending']['query']->the_post();
									?>
								<div class="post-item swiper-slide">
									<article class="post-wrapper mini-layout">
										<div class="post-inner">
											<div class="image-outer-wrapper">
												<div class="post-counter-wrap counter-inside-image blogsy-position-bottom-right">
													<span class="post-counter"></span>
												</div>
												<div class="image-wrapper">
													<a class="blogsy-position-cover" href="<?php the_permalink(); ?>" aria-label="Item Link"></a>
													<?php the_post_thumbnail( 'thumbnail', [ 'title' => get_the_title() ] ); ?>
												</div>
											</div>
											<div class="content-wrapper">
												<div class="content-inner">
													<h6 class="title">
													<a href="<?php the_permalink(); ?>" class="title-animation-underline" title="<?php the_title_attribute(); ?>"> <?php the_title(); ?> </a>
													</h6>
													<div class="meta-wrapper">
														<?php echo blogsy_entry_meta_date( [ 'show_date_icon' => true ], true ); ?>
														<div class="comments-wrapper">
															<?php echo \Blogsy\Icon::get_svg( 'comment', '', [ 'aria-hidden' => 'true' ] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
															<span class="comments">
																<a href="<?php comments_link(); ?>"><?php echo esc_html( get_comments_number() ); ?></a>
															</span>
														</div>
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
						<div class="carousel-nav-wrapper blogsy-position-top-right nav-style-1">
							<a href="javascript:void(0);" class="carousel-nav-prev" tabindex="0" role="button" aria-label="Previous slide" aria-controls="swiper-wrapper-hero">
								<?php echo \Blogsy\Icon::get_svg( 'chevron-down', '', [ 'aria-hidden' => 'true' ] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</a>
							<a href="javascript:void(0);" class="carousel-nav-next" tabindex="0" role="button" aria-label="Next slide" aria-controls="swiper-wrapper-hero">
								<?php echo \Blogsy\Icon::get_svg( 'chevron-up', '', [ 'aria-hidden' => 'true' ] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	document.addEventListener("DOMContentLoaded", function () {
		// Hero Tabs functionality.
		const buttons = document.querySelectorAll(".hero-tabs .hero-tabs-inner .tab-btn");
		const contents = document.querySelectorAll(".tab-content");
		buttons.forEach(btn => {
			btn.addEventListener("click", function () {
				const tab = this.getAttribute("data-tab");
				// active button
				buttons.forEach(b => b.classList.remove("active"));
				this.classList.add("active");
				// show correct content
				contents.forEach(c => {
					c.classList.remove("active");
					if (c.getAttribute("data-tab-content") === tab) {
						c.classList.add("active");
					}
				});
			});
		});
	});
</script>
