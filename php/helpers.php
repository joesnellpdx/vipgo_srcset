<?php

/**
 * Various helper functions for use on the ___ website.
 *
 * @package ___
 */

namespace ___\___\Helpers;

/**
 * Outputs a Responsive Image tag with optional link.
 *
 * @param  int $image_id The image attachment ID.
 * @param  bool $echo Whether to echo (true) or return (false) Default
 *                                     true.
 * @param  string $sizes The image size.
 * @param  boolean $use_link Link to attachment when true.
 * @param  string $desired_image_size The desired image size for the image.
 * @param  string $fallback The fallback image size.
 * @param  string $custom_link Custom link for image.
 * @param  string $custom_alt Custom alternate text for image.
 *
 * @return void
 */
function the_rwd_image( $image_id, $echo = true, $sizes = '100vw', $use_link = false, $desired_image_size = 'full', $fallback = 'native-2000', $custom_link = '', $custom_alt = '' ) {
	$img_src      = photon_get_attachment_image_url( $image_id, $desired_image_size );
	$img_fallback = photon_get_attachment_image_url( $image_id, $fallback );
	$srcset_value = get_srcset( $image_id, $desired_image_size )[0];
	$srcset       = $srcset_value ? ' srcset="' . esc_attr( $srcset_value ) . '"' : '';
	$alt          = $custom_alt ? $custom_alt : get_post_meta( $image_id, '_wp_attachment_image_alt', true );

	if ( ! $img_src ) {
		return;
	}

	$output = '';

	if ( $use_link && empty( $custom_alt ) ) {
		$output .= sprintf(
			'<a href="%s" rel="bookmark">',
			esc_url( get_permalink() )
		);
	} elseif ( $use_link && ! empty( $custom_link ) ) {
		$output .= sprintf(
			'<a href="%s">',
			esc_url( $custom_link )
		);
	}

	$output .= sprintf(
		'<img src="%s" %s sizes="%s" alt="%s" data-fallback-img="%s">',
		esc_url( $img_src ),
		$srcset,
		esc_attr( $sizes ),
		esc_attr( $alt ),
		esc_url( $img_fallback )
	);

	if ( true === $use_link ) {
		$output .= '</a>';
	}

	// Depending on echo param, echo or return.
	if ( true === $echo ) {
		echo $output; // xss okay.
	} else {
		return $output;
	}
}

/**
 * Outputs a Responsive Image fit figure with optional fallback.
 *
 * @param  int $image_id The image attachment ID.
 * @param  string $class The image class attribute.
 * @param  boolean $echo Whether to echo (true) or return (false)
 *                                      Default true.
 * @param  string $sizes The image size.
 * @param  string $desired_image_size The desired image size for the image
 * @param  boolean $use_link Link to attachment when true.
 * @param  boolean $use_caption The image caption.
 * @param  string $fallback The fallback image size.
 *
 * @return void
 */
function the_img_fit_figure( $image_id, $class, $sizes = '100vw', $desired_image_size = 'native-2000', $fallback = 'native-2000', $echo = true, $use_link = false, $use_caption = false, $img_fit = true ) {

	if ( empty( $image_id ) ) {
		return;
	}

	$img_src = wp_get_attachment_image_url( $image_id, 'native-400' );

	if ( empty( $img_src ) ) {
		return;
	}

	$img_post = get_post( $image_id );

	if ( true === $img_fit ) {
		$img_fit = 'img-fit';
	} else {
		$img_fit = '';
	}

	if ( true === $use_caption ) {
		$class .= ' wp-caption';
	}

	$figcaption = ( true === $use_caption ) ?
		ami_custom_caption( $image_id ) :
		'';

	$output = sprintf(
		'<figure class="%1$s %2$s" itemprop="image" itemscope itemtype="http://schema.org/ImageObject">
			%3$s
			%4$s
		</figure>',
		esc_attr( $class ),
		esc_attr( $img_fit ),
		the_rwd_image( $image_id, false, $sizes, $use_link, $fallback, $desired_image_size ),
		$figcaption
	);

	// Depending on echo param, echo or return.
	if ( true === $echo ) {
		echo $output; // xss okay.
	} else {
		return $output;
	}
}

/**
 * Get available image srcset
 *
 * @param $image_id             The image ID
 * @param $desired_img_size     Desired image size (i.e. square-800x800)
 *
 * @return bool|string
 */
function get_srcset_fallback( $image_id, $desired_img_size ) {

	$img_src    = wp_get_attachment_image_src( $image_id, $desired_img_size );
	$image_meta = wp_get_attachment_metadata( $image_id );

	$image_type = substr( $desired_img_size, 0, strpos( $desired_img_size, '-' ) );
	if ( empty ( $image_type ) ) {
		$image_type = 'full';
	}

	// override falling back to 'pri' image ratios if specific size doesn't exist
	if ( 'square' === $image_type ) {
		$size_array = array(
			400,
			400
		);
	} elseif ( 'landscape' === $image_type ) {
		$size_array = array(
			400,
			225
		);
	} elseif ( 'portrait' === $image_type ) {
		$size_array = array(
			225,
			400
		);
	} else {
		$size_array = array(
			absint( $img_src[1] ),
			absint( $img_src[2] )
		);
	}

	$img_srcset = wp_calculate_image_srcset( $size_array, $img_src[0], $image_meta, $image_id );

	// Get largest existing image size by type
	$img_srcset_raw = wp_get_attachment_image_srcset( $image_id, $desired_img_size );

	if ( empty ( $img_srcset_raw ) ) {

		$size_array = get_image_size_array( $desired_img_size );

		foreach ( $size_array as $sizes => $value ) {

			$img_srcset_raw = wp_get_attachment_image_srcset( $image_id, $sizes );
			if ( ! empty( $img_srcset_raw ) ) {
				break;
			}
		}

		if ( empty ( $img_srcset_raw ) ) {
			$desired_img_size = 'full';
			$img_srcset_raw   = wp_get_attachment_image_srcset( $image_id, $desired_img_size );
		}
	}

	if ( empty ( $img_srcset ) ) {
		$img_srcset = $img_srcset_raw;
	}

	return array( $img_srcset, $desired_img_size );
}

/**
 * Get RWD image size array
 *
 * @param   $desired_img_size   Desired image size (i.e. square-800x800)
 */
function get_image_size_array( $desired_img_size ) {

	$image_type = substr( $desired_img_size, 0, strpos( $desired_img_size, '-' ) );
	if ( empty ( $image_type ) ) {
		$image_type = 'native';
	}

	$size_array = '';

	if ( 'native' === $image_type ) :
		$size_array = array(
			'native-200'  => 200,
			'native-400'  => 400,
			'native-800'  => 800,
			'native-1200' => 1200,
			'native-1600' => 1600,
			'native-2400' => 2400,
			'native-3200' => 3200,
			'native-4000' => 4000
		);
	else :
		if ( 'landscape' === $image_type ) :
			$size_array = array(
				'landscape-200x113'   => array( 200, 113 ),
				'landscape-400x225'   => array( 400, 225 ),
				'landscape-800x450'   => array( 800, 450 ),
				'landscape-1200x675'  => array( 1200, 675 ),
				'landscape-1600x900'  => array( 1600, 900 ),
				'landscape-2400x1350' => array( 2400, 1350 ),
				'landscape-3200x1800' => array( 3200, 1800 ),
				'landscape-4000x2250' => array( 4000, 2250 )
			);
		elseif ( 'square' === $image_type ) :
			$size_array = array(
				'square-100x100'   => array( 100, 100 ),
				'square-400x400'   => array( 400, 400 ),
				'square-800x800'   => array( 800, 800 ),
				'square-1200x1200' => array( 1200, 1200 ),
				'square-1600x1600' => array( 1600, 1600 ),
				'square-2400x2400' => array( 2400, 2400 ),
				'square-3200x3200' => array( 3200, 3200 ),
				'square-4000x4000' => array( 4000, 4000 )
			);
		elseif ( 'portrait' === $image_type ) :
			$size_array = array(
				'portrait-113x200'   => array( 113, 200 ),
				'portrait-225x400'   => array( 225, 400 ),
				'portrait-450x800'   => array( 450, 800 ),
				'portrait-675x1200'  => array( 675, 1200 ),
				'portrait-900x1600'  => array( 900, 1600 ),
				'portrait-1350x2400' => array( 1350, 2400 ),
				'portrait-1800x3200' => array( 1800, 3200 ),
				'portrait-2250x4000' => array( 2250, 4000 )
			);
		else :
			$size_array = array(
				'fit-200x133'   => array( 200, 113 ),
				'fit-400x225'   => array( 400, 225 ),
				'fit-800x450'   => array( 800, 450 ),
				'fit-1200x675'  => array( 1200, 675 ),
				'fit-1600x900'  => array( 1600, 900 ),
				'fit-2400x1350' => array( 2400, 1350 ),
				'fit-3200x1800' => array( 3200, 1800 ),
				'fit-4000x2250' => array( 4000, 2250 )
			);
		endif;
	endif;

	return $size_array;
}

/**
 * Get srcset via photon / Jetpack
 *
 * @param $image_id             The image ID
 * @param $desired_img_size     Desired image size (i.e. square-800x800)
 *
 * @return bool|string
 */

function get_srcset( $image_id, $desired_img_size = 'full' ) {
	// @TODO Move jetpack debug conditionals into core do / add action
	if ( ( ! defined( 'JETPACK_DEV_DEBUG' ) || ! ( JETPACK_DEV_DEBUG ) ) && function_exists( 'jetpack_photon_url' ) ) :

		$image_url = wp_get_attachment_image_url( $image_id, 'full' );

		$image_type = substr( $desired_img_size, 0, strpos( $desired_img_size, '-' ) );
		if ( empty ( $image_type ) ) {
			$image_type = 'native';
		}

		$size_array = get_image_size_array( $desired_img_size );

		$img_srcset = array();


		foreach ( $size_array as $sizes => $size ) {
			if ( 'native' === $image_type ) :
				$args  = array(
					'w'       => $size,
					'quality' => '85'
				);
				$width = ' ' . $size . 'w';
			elseif ( 'fit' === $image_type ) :
				$args  = array(
					'fit'     => $size,
					'quality' => '85'
				);
				$width = $size[0] . 'w';
			else :
				$args  = array(
					'resize'  => $size,
					'quality' => '85'
				);
				$width = $size[0] . 'w';
			endif;

			$img_srcset[] = jetpack_photon_url( $image_url, $args ) . ' ' . $width;
			if ( $sizes === $desired_img_size ) {
				break;
			}
		}

		$img_srcset = esc_attr( implode( ", ", $img_srcset ) );

	else:
		$img_srcset       = get_srcset_fallback( $image_id, $desired_img_size )[0];
		$desired_img_size = get_srcset_fallback( $image_id, $desired_img_size )[1];
	endif;

	return array( $img_srcset, $desired_img_size );
}

/**
 * Get photon attachment image url
 *
 * @param  integer $image_id The image ID.
 * @param  string $desired_img_size Desired image size (i.e. square-800x800).
 *
 * @return false|string
 */
function photon_get_attachment_image_url( $image_id, $desired_img_size = 'native-1600' ) {

	$img_url = wp_get_attachment_image_url( $image_id, 'full' );

	// @TODO Move jetpack debug conditionals into core do / add action
	if (
		( ! defined( 'JETPACK_DEV_DEBUG' ) || ! JETPACK_DEV_DEBUG ) &&
		function_exists( 'jetpack_photon_url' )
	) {
		$image_type = substr( $desired_img_size, 0, strpos( $desired_img_size, '-' ) );
		if ( ! $image_type ) {
			$image_type = 'native';
		}

		$size_array = [];
		if ( 'native' === $image_type ) {
			$args = [
				'w'       => $size_array[ $desired_img_size ],
				'quality' => '85',
			];
		} elseif ( 'fit' === $image_type ) {
			$args = [
				'fit'     => $size_array[ $desired_img_size ],
				'quality' => '85',
			];
		} else {
			$args = [
				'resize'  => $size_array[ $desired_img_size ],
				'quality' => '85',
			];
		}
		$img_url = jetpack_photon_url( $img_url, $args );
	} else {
		$img_url = wp_get_attachment_image_url( $image_id, $desired_img_size );
	}

	return $img_url;

}

/**
 * ___ <picuture> element
 *
 * Output an art directed picture element that allows different image sizes / images
 * at a particular breakpoint
 *
 * @param int $img_start_id The image id prior to breakpoint
 * @param int $img_end_id The image id post breakpoint
 * @param string $start_size The image size parameter for the starting image (i.e. 'square-1600x1600')
 * @param string $full_size The image size parameter for the ending image (i.e. 'landscape-2400x1350')
 * @param string $start_sizes Optimimum browser image start sizes (i.e. (min-width: 600px) 50vw, 100vw )
 * @param string $full_sizes Optimimum browser image end sizes (i.e. (min-width: 800px) 50vw, 100vw )
 * @param string $start_fallback The start image size for object fit fallback (i.e. 'square-1600x1600')
 * @param string $full_fallback The end image size for object fit fallback (i.e. 'landscape-2400x1350')
 * @param string $breakpoint The breakpoint to switch images (i.e. 768)
 * @param string $class The <picture> class
 * @param bool $echo Echo or return
 *
 * @return void
 */
function picture_art_direct( $img_start_id, $img_end_id, $start_size = 'square-200x200', $full_size = 'landscape-1600x900', $start_sizes = '200px', $full_sizes = '200px', $start_fallback = 'square-800x800', $full_fallback = 'landscape-1600x900', $breakpoint = '1023', $class = '', $echo = true ) {

	if ( empty ( $img_start_id ) || empty( $img_end_id ) ) {
		return;
	}

	$alt                = get_post_meta( $img_end_id, '_wp_attachment_image_alt', true );
	$img_full_srcset    = get_srcset( $img_end_id, $full_size )[0];
	$img_cropped_srcset = get_srcset( $img_start_id, $start_size )[0];
	$img_full_src       = photon_get_attachment_image_url( $img_end_id, $full_fallback );
	$img_start_src      = photon_get_attachment_image_url( $img_start_id, $start_fallback );

	if ( $img_full_srcset && $img_cropped_srcset ) {
		$output = sprintf(
			'<picture class="%1$s">
				<source
					media="(min-width: %2$spx)"
					srcset="%3$s"
					sizes="%4$s" />
				<img srcset="%5$s"
					alt="%6$s"
					sizes="%7$s" data-fallback-img="%8$s" data-fallback-img-sm="%9$s" data-mq="%10$s"/>
			</picture>',
			esc_attr( $class ),
			esc_attr( $breakpoint ),
			esc_attr( $img_full_srcset ),
			esc_attr( $full_sizes ),
			esc_attr( $img_cropped_srcset ),
			esc_attr( $alt ),
			esc_attr( $start_sizes ),
			esc_attr( $img_full_src ),
			esc_attr( $img_start_src ),
			esc_attr( $breakpoint )
		);
	} else {
		$output = sprintf(
			'<picture class="ms-card__img">%1$s</picture>',
			the_rwd_image( $img_end_id, false )
		);
	}

	// Depending on echo param, echo or return.
	if ( true === $echo ) {
		echo $output;
	} else {
		return $output;
	}
}
