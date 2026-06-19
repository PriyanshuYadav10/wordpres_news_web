<?php
/**
 * Dynamic content pages for Blogsy News.
 *
 * Auto-creates the six pages linked from the top bar (Privacy Policy, FAQ's,
 * Contact Us, About Us, Location, Terms of Use) and powers them with dynamic,
 * per-request content via shortcodes — site name, contact email, current date,
 * live post/category counts, address & map, and an editable FAQ list. Page
 * layout is left to the (parent) theme; only the content is generated here.
 *
 * Everything is filterable so the real values can be set from a child theme or
 * a small plugin without touching this file.
 *
 * @package Blogsy News
 * @author  Peregrine Themes
 * @since   1.0.3
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ===========================================================================
 * 1. Data providers (filterable)
 * ======================================================================== */

/**
 * Business / contact details used across the dynamic pages.
 *
 * @since 1.0.3
 * @return array{email:string,phone:string,address:string,hours:string}
 */
function blogsy_news_business_info() {
	$defaults = [
		'email'   => get_option( 'admin_email' ),
		'phone'   => '+1 (555) 010-0199',
		'address' => '123 Example Street, Suite 100, New York, NY 10001',
		'hours'   => esc_html__( 'Mon – Fri, 9:00 AM – 6:00 PM', 'blogsy-news' ),
	];

	/**
	 * Filter the business/contact details.
	 *
	 * @param array $info Contact details.
	 */
	return (array) apply_filters( 'blogsy_news_business_info', $defaults );
}

/**
 * Frequently asked questions shown on the FAQ page.
 *
 * @since 1.0.3
 * @return array<int,array{q:string,a:string}>
 */
function blogsy_news_faqs() {
	$site = get_bloginfo( 'name' );

	$defaults = [
		[
			'q' => esc_html__( 'How often is new content published?', 'blogsy-news' ),
			'a' => sprintf(
				/* translators: %s: site name. */
				esc_html__( '%s publishes fresh stories daily. Subscribe to our newsletter to get the highlights delivered straight to your inbox.', 'blogsy-news' ),
				esc_html( $site )
			),
		],
		[
			'q' => esc_html__( 'Can I contribute or submit a story?', 'blogsy-news' ),
			'a' => esc_html__( 'Absolutely. Reach out through our Contact Us page with a short pitch and a few writing samples and our editorial team will get back to you.', 'blogsy-news' ),
		],
		[
			'q' => esc_html__( 'How do I report a correction?', 'blogsy-news' ),
			'a' => esc_html__( 'Accuracy matters to us. Email the editorial team with the article link and the detail that needs reviewing, and we will update it promptly.', 'blogsy-news' ),
		],
		[
			'q' => esc_html__( 'Is my personal data safe?', 'blogsy-news' ),
			'a' => esc_html__( 'Yes. We only collect what we need and never sell your data. See our Privacy Policy for the full details.', 'blogsy-news' ),
		],
	];

	/**
	 * Filter the FAQ entries.
	 *
	 * @param array $faqs List of [ 'q' => question, 'a' => answer ].
	 */
	return (array) apply_filters( 'blogsy_news_faqs', $defaults );
}

/* ===========================================================================
 * 2. Dynamic content shortcodes
 * ======================================================================== */

add_shortcode(
	'blogsy_news_site_name',
	function () {
		return esc_html( get_bloginfo( 'name' ) );
	}
);

add_shortcode(
	'blogsy_news_site_url',
	function () {
		return '<a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html( wp_parse_url( home_url(), PHP_URL_HOST ) ) . '</a>';
	}
);

add_shortcode(
	'blogsy_news_year',
	function () {
		return esc_html( date_i18n( 'Y' ) );
	}
);

add_shortcode(
	'blogsy_news_email',
	function () {
		$info = blogsy_news_business_info();
		$mail = sanitize_email( $info['email'] );
		return '<a href="' . esc_attr( 'mailto:' . $mail ) . '">' . esc_html( $mail ) . '</a>';
	}
);

/**
 * "Last updated" line — reflects the page's real modified date.
 */
add_shortcode(
	'blogsy_news_updated',
	function () {
		$date = get_the_modified_date( get_option( 'date_format' ) );
		if ( ! $date ) {
			$date = date_i18n( get_option( 'date_format' ) );
		}
		return esc_html( $date );
	}
);

/**
 * Live site statistics (posts, categories, authors).
 */
add_shortcode(
	'blogsy_news_stats',
	function () {
		$posts   = (int) wp_count_posts()->publish;
		$cats    = (int) wp_count_terms( [ 'taxonomy' => 'category', 'hide_empty' => false ] );
		$authors = count_users();
		$authors = isset( $authors['total_users'] ) ? (int) $authors['total_users'] : 0;

		$items = [
			[ number_format_i18n( $posts ), esc_html__( 'Stories published', 'blogsy-news' ) ],
			[ number_format_i18n( $cats ), esc_html__( 'Topics covered', 'blogsy-news' ) ],
			[ number_format_i18n( $authors ), esc_html__( 'Contributors', 'blogsy-news' ) ],
		];

		$out = '<div class="blogsy-news-stats">';
		foreach ( $items as $item ) {
			$out .= '<div class="blogsy-news-stats__item"><span class="blogsy-news-stats__num">' . esc_html( $item[0] ) . '</span><span class="blogsy-news-stats__label">' . esc_html( $item[1] ) . '</span></div>';
		}
		$out .= '</div>';

		return $out;
	}
);

/**
 * FAQ accordion (no-JS, native <details>).
 */
add_shortcode(
	'blogsy_news_faq',
	function () {
		$faqs = blogsy_news_faqs();
		if ( empty( $faqs ) ) {
			return '';
		}

		$out = '<div class="blogsy-news-faq">';
		foreach ( $faqs as $faq ) {
			if ( empty( $faq['q'] ) ) {
				continue;
			}
			$out .= '<details class="blogsy-news-faq__item">';
			$out .= '<summary class="blogsy-news-faq__q">' . esc_html( $faq['q'] ) . '</summary>';
			$out .= '<div class="blogsy-news-faq__a">' . wp_kses_post( wpautop( $faq['a'] ?? '' ) ) . '</div>';
			$out .= '</details>';
		}
		$out .= '</div>';

		return $out;
	}
);

/**
 * Location block — address details + a live Google Maps embed.
 */
add_shortcode(
	'blogsy_news_location',
	function () {
		$info    = blogsy_news_business_info();
		$address = trim( (string) ( $info['address'] ?? '' ) );

		$out = '<div class="blogsy-news-location">';

		if ( $address ) {
			$map = add_query_arg(
				[
					'q'      => rawurlencode( $address ),
					'output' => 'embed',
				],
				'https://www.google.com/maps'
			);

			$out .= '<div class="blogsy-news-location__map">';
			$out .= '<iframe title="' . esc_attr__( 'Location map', 'blogsy-news' ) . '" src="' . esc_url( $map ) . '" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>';
			$out .= '</div>';
		}

		$out .= '<ul class="blogsy-news-location__details">';
		if ( $address ) {
			$out .= '<li><strong>' . esc_html__( 'Address', 'blogsy-news' ) . ':</strong> ' . esc_html( $address ) . '</li>';
		}
		if ( ! empty( $info['phone'] ) ) {
			$out .= '<li><strong>' . esc_html__( 'Phone', 'blogsy-news' ) . ':</strong> <a href="' . esc_attr( 'tel:' . preg_replace( '/[^0-9+]/', '', $info['phone'] ) ) . '">' . esc_html( $info['phone'] ) . '</a></li>';
		}
		if ( ! empty( $info['hours'] ) ) {
			$out .= '<li><strong>' . esc_html__( 'Hours', 'blogsy-news' ) . ':</strong> ' . esc_html( $info['hours'] ) . '</li>';
		}
		$out .= '</ul>';
		$out .= '</div>';

		return $out;
	}
);

/**
 * Contact block — details plus a working contact form (wp_mail).
 */
add_shortcode( 'blogsy_news_contact', 'blogsy_news_sc_contact' );

/**
 * Render the contact details and form.
 *
 * @since 1.0.3
 * @return string
 */
function blogsy_news_sc_contact() {
	$info   = blogsy_news_business_info();
	$status = isset( $_GET['contact'] ) ? sanitize_key( wp_unslash( $_GET['contact'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only status flag.

	ob_start();
	?>
	<div class="blogsy-news-contact">
		<ul class="blogsy-news-contact__details">
			<?php if ( ! empty( $info['email'] ) ) : ?>
				<li><strong><?php esc_html_e( 'Email', 'blogsy-news' ); ?>:</strong> <a href="<?php echo esc_attr( 'mailto:' . sanitize_email( $info['email'] ) ); ?>"><?php echo esc_html( sanitize_email( $info['email'] ) ); ?></a></li>
			<?php endif; ?>
			<?php if ( ! empty( $info['phone'] ) ) : ?>
				<li><strong><?php esc_html_e( 'Phone', 'blogsy-news' ); ?>:</strong> <?php echo esc_html( $info['phone'] ); ?></li>
			<?php endif; ?>
			<?php if ( ! empty( $info['address'] ) ) : ?>
				<li><strong><?php esc_html_e( 'Address', 'blogsy-news' ); ?>:</strong> <?php echo esc_html( $info['address'] ); ?></li>
			<?php endif; ?>
		</ul>

		<?php if ( 'sent' === $status ) : ?>
			<p class="blogsy-news-contact__msg is-success"><?php esc_html_e( 'Thanks for reaching out — we’ll be in touch soon.', 'blogsy-news' ); ?></p>
		<?php elseif ( 'error' === $status ) : ?>
			<p class="blogsy-news-contact__msg is-error"><?php esc_html_e( 'Please fill in all fields with a valid email and try again.', 'blogsy-news' ); ?></p>
		<?php endif; ?>

		<form class="blogsy-news-contact__form" action="<?php echo esc_url( add_query_arg( [] ) ); ?>" method="post">
			<?php wp_nonce_field( 'blogsy_news_contact', 'blogsy_news_contact_nonce' ); ?>
			<p>
				<label for="bn-contact-name"><?php esc_html_e( 'Name', 'blogsy-news' ); ?></label>
				<input type="text" id="bn-contact-name" name="bn_name" required>
			</p>
			<p>
				<label for="bn-contact-email"><?php esc_html_e( 'Email', 'blogsy-news' ); ?></label>
				<input type="email" id="bn-contact-email" name="bn_email" required>
			</p>
			<p>
				<label for="bn-contact-message"><?php esc_html_e( 'Message', 'blogsy-news' ); ?></label>
				<textarea id="bn-contact-message" name="bn_message" rows="5" required></textarea>
			</p>
			<p>
				<button type="submit" name="blogsy_news_contact_submit" value="1" class="blogsy-news-contact__btn"><?php esc_html_e( 'Send message', 'blogsy-news' ); ?></button>
			</p>
		</form>
	</div>
	<?php
	return (string) ob_get_clean();
}

/**
 * Process the contact form submission (PRG: redirect after POST).
 *
 * @since 1.0.3
 * @return void
 */
function blogsy_news_handle_contact() {
	if ( empty( $_POST['blogsy_news_contact_submit'] ) ) {
		return;
	}

	$redirect = wp_get_referer() ? wp_get_referer() : home_url( '/' );

	if (
		! isset( $_POST['blogsy_news_contact_nonce'] )
		|| ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['blogsy_news_contact_nonce'] ) ), 'blogsy_news_contact' )
	) {
		wp_safe_redirect( add_query_arg( 'contact', 'error', $redirect ) );
		exit;
	}

	$name    = isset( $_POST['bn_name'] ) ? sanitize_text_field( wp_unslash( $_POST['bn_name'] ) ) : '';
	$email   = isset( $_POST['bn_email'] ) ? sanitize_email( wp_unslash( $_POST['bn_email'] ) ) : '';
	$message = isset( $_POST['bn_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['bn_message'] ) ) : '';

	if ( '' === $name || ! is_email( $email ) || '' === $message ) {
		wp_safe_redirect( add_query_arg( 'contact', 'error', $redirect ) );
		exit;
	}

	$info    = blogsy_news_business_info();
	$to      = sanitize_email( $info['email'] );
	$subject = sprintf(
		/* translators: %s: site name. */
		esc_html__( '[%s] New contact message', 'blogsy-news' ),
		get_bloginfo( 'name' )
	);
	$body    = sprintf( "Name: %s\nEmail: %s\n\n%s", $name, $email, $message );
	$headers = [ 'Reply-To: ' . $name . ' <' . $email . '>' ];

	wp_mail( $to, $subject, $body, $headers );

	wp_safe_redirect( add_query_arg( 'contact', 'sent', $redirect ) );
	exit;
}
add_action( 'template_redirect', 'blogsy_news_handle_contact' );

/* ===========================================================================
 * 3. Page content builders
 * ======================================================================== */

/**
 * Definitions for the auto-created pages.
 *
 * @since 1.0.3
 * @return array<string,array{title:string,content:string}>
 */
function blogsy_news_page_definitions() {
	$defs = [
		'about-us'       => [
			'title'   => esc_html__( 'About Us', 'blogsy-news' ),
			'content' => blogsy_news_page_content_about(),
		],
		'contact-us'     => [
			'title'   => esc_html__( 'Contact Us', 'blogsy-news' ),
			'content' => blogsy_news_page_content_contact(),
		],
		'faq'            => [
			'title'   => esc_html__( "FAQ's", 'blogsy-news' ),
			'content' => blogsy_news_page_content_faq(),
		],
		'location'       => [
			'title'   => esc_html__( 'Location', 'blogsy-news' ),
			'content' => blogsy_news_page_content_location(),
		],
		'privacy-policy' => [
			'title'   => esc_html__( 'Privacy Policy', 'blogsy-news' ),
			'content' => blogsy_news_page_content_privacy(),
		],
		'terms-of-use'   => [
			'title'   => esc_html__( 'Terms of Use', 'blogsy-news' ),
			'content' => blogsy_news_page_content_terms(),
		],
	];

	/**
	 * Filter the auto-created page definitions.
	 *
	 * @param array $defs Map of slug => [ title, content ].
	 */
	return (array) apply_filters( 'blogsy_news_page_definitions', $defs );
}

/**
 * About Us page content.
 *
 * @return string
 */
function blogsy_news_page_content_about() {
	return implode(
		"\n\n",
		[
			'<p>' . esc_html__( 'Welcome to', 'blogsy-news' ) . ' [blogsy_news_site_name] — ' . esc_html__( 'your destination for fresh perspectives, trusted reporting, and the stories that matter, delivered daily.', 'blogsy-news' ) . '</p>',
			'[blogsy_news_stats]',
			'<h2>' . esc_html__( 'Our Mission', 'blogsy-news' ) . '</h2>',
			'<p>' . esc_html__( 'We believe quality journalism should be clear, fair, and accessible to everyone. Our team works around the clock to bring you accurate news and thoughtful analysis across the topics you care about.', 'blogsy-news' ) . '</p>',
			'<h2>' . esc_html__( 'Get in Touch', 'blogsy-news' ) . '</h2>',
			'<p>' . esc_html__( 'Have a tip, a question, or feedback? Email us at', 'blogsy-news' ) . ' [blogsy_news_email] ' . esc_html__( 'or visit our Contact Us page.', 'blogsy-news' ) . '</p>',
		]
	);
}

/**
 * Contact Us page content.
 *
 * @return string
 */
function blogsy_news_page_content_contact() {
	return implode(
		"\n\n",
		[
			'<p>' . esc_html__( 'We’d love to hear from you. Use the details below or send us a message and we’ll get back to you as soon as we can.', 'blogsy-news' ) . '</p>',
			'[blogsy_news_contact]',
		]
	);
}

/**
 * FAQ page content.
 *
 * @return string
 */
function blogsy_news_page_content_faq() {
	return implode(
		"\n\n",
		[
			'<p>' . esc_html__( 'Answers to the questions we hear most often. Still stuck? Reach out via our Contact Us page.', 'blogsy-news' ) . '</p>',
			'[blogsy_news_faq]',
		]
	);
}

/**
 * Location page content.
 *
 * @return string
 */
function blogsy_news_page_content_location() {
	return implode(
		"\n\n",
		[
			'<p>' . esc_html__( 'Find us at the address below — drop by during our office hours or get in touch first to schedule a visit.', 'blogsy-news' ) . '</p>',
			'[blogsy_news_location]',
		]
	);
}

/**
 * Privacy Policy page content.
 *
 * @return string
 */
function blogsy_news_page_content_privacy() {
	return implode(
		"\n\n",
		[
			'<p>' . esc_html__( 'This Privacy Policy explains how', 'blogsy-news' ) . ' [blogsy_news_site_name] (' . esc_html__( '“we”, “us”, “our”', 'blogsy-news' ) . ') ' . esc_html__( 'collects, uses, and safeguards your information when you visit', 'blogsy-news' ) . ' [blogsy_news_site_url].</p>',
			'<h2>' . esc_html__( 'Information We Collect', 'blogsy-news' ) . '</h2>',
			'<p>' . esc_html__( 'We collect information you provide directly — such as when you subscribe to our newsletter or contact us — along with standard technical data (like browser type and pages visited) gathered automatically to improve the site.', 'blogsy-news' ) . '</p>',
			'<h2>' . esc_html__( 'How We Use Your Information', 'blogsy-news' ) . '</h2>',
			'<p>' . esc_html__( 'Your information helps us deliver content, respond to enquiries, send updates you opt into, and keep the site secure. We never sell your personal data.', 'blogsy-news' ) . '</p>',
			'<h2>' . esc_html__( 'Cookies', 'blogsy-news' ) . '</h2>',
			'<p>' . esc_html__( 'We use cookies to remember preferences and understand how the site is used. You can disable cookies in your browser settings at any time.', 'blogsy-news' ) . '</p>',
			'<h2>' . esc_html__( 'Contact', 'blogsy-news' ) . '</h2>',
			'<p>' . esc_html__( 'Questions about this policy? Email us at', 'blogsy-news' ) . ' [blogsy_news_email].</p>',
			'<p><em>' . esc_html__( 'Last updated:', 'blogsy-news' ) . ' [blogsy_news_updated]</em></p>',
		]
	);
}

/**
 * Terms of Use page content.
 *
 * @return string
 */
function blogsy_news_page_content_terms() {
	return implode(
		"\n\n",
		[
			'<p>' . esc_html__( 'By accessing', 'blogsy-news' ) . ' [blogsy_news_site_url], ' . esc_html__( 'you agree to these Terms of Use. Please read them carefully.', 'blogsy-news' ) . '</p>',
			'<h2>' . esc_html__( 'Use of Content', 'blogsy-news' ) . '</h2>',
			'<p>' . esc_html__( 'All content published on', 'blogsy-news' ) . ' [blogsy_news_site_name] ' . esc_html__( 'is for general information. You may share it with attribution, but you may not reproduce it commercially without permission.', 'blogsy-news' ) . '</p>',
			'<h2>' . esc_html__( 'User Conduct', 'blogsy-news' ) . '</h2>',
			'<p>' . esc_html__( 'You agree not to misuse the site, attempt to disrupt its operation, or post unlawful or harmful material where contributions are allowed.', 'blogsy-news' ) . '</p>',
			'<h2>' . esc_html__( 'Disclaimer', 'blogsy-news' ) . '</h2>',
			'<p>' . esc_html__( 'Content is provided “as is” without warranties of any kind. We are not liable for decisions made based on information found on this site.', 'blogsy-news' ) . '</p>',
			'<h2>' . esc_html__( 'Contact', 'blogsy-news' ) . '</h2>',
			'<p>' . esc_html__( 'Questions about these terms? Email us at', 'blogsy-news' ) . ' [blogsy_news_email].</p>',
			'<p><em>' . esc_html__( 'Last updated:', 'blogsy-news' ) . ' [blogsy_news_updated]</em></p>',
		]
	);
}

/* ===========================================================================
 * 4. Auto-create the pages (idempotent)
 * ======================================================================== */

/**
 * Create any of the defined pages that don't already exist.
 *
 * Matches by slug, so it never duplicates a page a user already made.
 *
 * @since 1.0.3
 * @return void
 */
function blogsy_news_create_pages() {
	foreach ( blogsy_news_page_definitions() as $slug => $def ) {
		$existing = get_page_by_path( $slug );
		if ( $existing instanceof WP_Post ) {
			continue;
		}

		wp_insert_post(
			[
				'post_title'   => $def['title'],
				'post_name'    => $slug,
				'post_content' => $def['content'],
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'post_author'  => 1,
				'comment_status' => 'closed',
			]
		);
	}
}

// Create on theme activation.
add_action( 'after_switch_theme', 'blogsy_news_create_pages' );

// Safety net for installs where the theme is already active: run once.
add_action(
	'admin_init',
	function () {
		if ( get_option( 'blogsy_news_pages_created' ) ) {
			return;
		}
		blogsy_news_create_pages();
		update_option( 'blogsy_news_pages_created', 1 );
	}
);
