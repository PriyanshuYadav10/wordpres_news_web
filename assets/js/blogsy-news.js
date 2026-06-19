/**
 * Blogsy News — front-end interactions.
 *
 * - Back-to-top button (show on scroll, smooth scroll up)
 * - Footer newsletter form (front-end validation + status message)
 * - Top bar extras (relocate quick links + social handles into the top bar)
 *
 * @package Blogsy News
 * @since   1.0.2
 */
( function () {
	'use strict';

	/* ----------------------------------------------------------------
	 * Back to top
	 * ------------------------------------------------------------- */
	function initBackToTop() {
		var btn = document.querySelector( '.blogsy-news-back-to-top' );
		if ( ! btn ) {
			return;
		}

		var toggle = function () {
			if ( window.pageYOffset > 600 ) {
				btn.classList.add( 'is-visible' );
			} else {
				btn.classList.remove( 'is-visible' );
			}
		};

		var ticking = false;
		window.addEventListener( 'scroll', function () {
			if ( ! ticking ) {
				window.requestAnimationFrame( function () {
					toggle();
					ticking = false;
				} );
				ticking = true;
			}
		}, { passive: true } );

		btn.addEventListener( 'click', function () {
			window.scrollTo( { top: 0, behavior: 'smooth' } );
		} );

		toggle();
	}

	/* ----------------------------------------------------------------
	 * Newsletter form
	 *
	 * If no real ESP action URL is wired in (action is "#"), we handle it
	 * gracefully on the front end with a confirmation message. Set a real
	 * form action via the `blogsy_news_newsletter_action` PHP filter to POST
	 * to your provider instead.
	 * ------------------------------------------------------------- */
	function initNewsletter() {
		var forms = document.querySelectorAll( '.blogsy-news-newsletter' );

		Array.prototype.forEach.call( forms, function ( form ) {
			var msg = form.querySelector( '.blogsy-news-newsletter__msg' );
			var input = form.querySelector( '.blogsy-news-newsletter__input' );
			var action = form.getAttribute( 'action' ) || '#';

			form.addEventListener( 'submit', function ( e ) {
				var email = input ? input.value.trim() : '';
				var valid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test( email );

				if ( ! valid ) {
					e.preventDefault();
					if ( msg ) {
						msg.textContent = 'Please enter a valid email address.';
						msg.className = 'blogsy-news-newsletter__msg is-error';
					}
					if ( input ) {
						input.focus();
					}
					return;
				}

				// No real endpoint configured — confirm on the front end.
				if ( '#' === action || '' === action ) {
					e.preventDefault();
					if ( msg ) {
						msg.textContent = 'Thanks for subscribing! 🎉';
						msg.className = 'blogsy-news-newsletter__msg is-success';
					}
					form.reset();
				}
				// Otherwise let the form submit to its configured action.
			} );
		} );
	}

	/* ----------------------------------------------------------------
	 * Top bar extras (quick links + social handles)
	 *
	 * The parent theme renders the red top bar, so we server-render the
	 * extras into a hidden holder and relocate them into the bar's right
	 * side here. If no top bar is found, we render a matching strip at the
	 * very top of the page so the content is never lost.
	 * ------------------------------------------------------------- */
	function initTopBarExtras() {
		var holder = document.getElementById( 'blogsy-news-topbar-extras-holder' );
		if ( ! holder ) {
			return;
		}

		var content = holder.firstElementChild;
		if ( ! content ) {
			holder.parentNode.removeChild( holder );
			return;
		}

		// Candidate selectors for the parent theme's top bar, tried in priority
		// order so a specific match wins regardless of document order.
		var selectors = [
			'.pt-top-bar',
			'.pt-topbar',
			'.pt-header-top',
			'.blogsy-top-bar',
			'[class*="top-bar"]'
		];
		var bar = null;
		for ( var i = 0; i < selectors.length; i++ ) {
			bar = document.querySelector( selectors[ i ] );
			if ( bar ) {
				break;
			}
		}

		if ( bar ) {
			var container = bar.querySelector( '.pt-container' ) || bar;
			container.appendChild( content );
			bar.classList.add( 'has-blogsy-news-topbar-extras' );
		} else {
			// Fallback: build our own strip at the very top of the page.
			var strip = document.createElement( 'div' );
			strip.className = 'blogsy-news-topbar-fallback';

			var inner = document.createElement( 'div' );
			inner.className = 'pt-container';
			inner.appendChild( content );
			strip.appendChild( inner );

			document.body.insertBefore( strip, document.body.firstChild );
		}

		holder.parentNode.removeChild( holder );
	}

	function init() {
		initBackToTop();
		initNewsletter();
		initTopBarExtras();
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
} )();
