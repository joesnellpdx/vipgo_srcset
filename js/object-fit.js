/**
 * Fallback for css object-fit declaration
 *
 * uses data attribute value 'data-fallback-img' to place background image on parent
 *
 */

( function( $ ) {
	let objectFit = '';

	if ( 'object-fit' in document.body.style ) {
		objectFit = true;
	}

	const elems = {imgfit: document.querySelectorAll( '.img-fit' )};

	/**
	 * Has Class helper function.
	 * @param {object} el element object
	 * @param {string} cls desired class
	 * @returns {boolean} true if has class
	 */
	const msHasClass = function( el, cls ) {
		return el.className &&
			new RegExp( `(\\s|^)${cls}(\\s|$)` ).test( el.className );
	};

	/**
	 * Fallback for object-fit styles.
	 * Adds background image to container if object-fit not supported.
	 * Uses data attribute value 'data-fallback-img' to place background image on parent.
	 * @returns {void}
	 */
	const imgFit = function() {
		if ( !objectFit ) {
			const elements = elems.imgfit;

			for ( let i = 0; i < elements.length; i++ ) {
				if ( msHasClass( elements[i], 'compat-object-fit' ) ) {
					// do nothing
				} else {
					const el = elements[i];
					const fbimg = $( elements[i] ).find( 'img' ).attr( 'data-fallback-img' );
					const fbimgsm = $( elements[i] ).find( 'img' ).attr( 'data-fallback-img-sm' );
					const fbmq = $( elements[i] ).find( 'img' ).attr( 'data-mq' );

					if ( '' !== fbimgsm && fbimgsm &&
						window.matchMedia( `(max-width: ${fbmq
							})` ).matches ) {
						el.classList.add( 'compat-object-fit' );
						el.style.backgroundImage = `url( "${fbimgsm}" )`;
					} else if ( '' !== fbimg ) {
						el.classList.add( 'compat-object-fit' );
						el.style.backgroundImage = `url( "${fbimg}" )`;
					}
				}
			}
		}
	};

	imgFit();
} )( jQuery, document );
