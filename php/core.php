<?php


/**
 * Adds custom image sizes to WP based on the needs of the theme.
 *
 * @return void
 */
function custom_image_sizes() {
	// custom image sizes
	add_image_size( 'thumbnail', 150, 150, true );
	add_image_size( 'fit-330x275', 330, 275 );
	add_image_size( 'fit-200x100', 200, 100 );

	// general responsive image size widths - maintains native aspect ratio
	add_image_size( 'native-200', 200 );
	add_image_size( 'native-400', 400 );
	add_image_size( 'native-800', 800 );
	add_image_size( 'native-1200', 1200 );
	add_image_size( 'native-1600', 1600 );
	add_image_size( 'native-2000', 2000 );
	add_image_size( 'native-2000', 2400 );
	add_image_size( 'native-3200', 3200 );
	add_image_size( 'native-4000', 4000 );

	// landscape image sizes -- cropped (16:9)
	add_image_size( 'landscape-200x113', 200, 113, true );
	add_image_size( 'landscape-400x225', 400, 225, true );
	add_image_size( 'landscape-800x450', 800, 450, true );
	add_image_size( 'landscape-1200x675', 1200, 675, true );
	add_image_size( 'landscape-1600x900', 1600, 900, true );
	add_image_size( 'landscape-2400x1350', 2400, 1350, true );
	add_image_size( 'landscape-3200x1800', 3200, 1800, true );
	add_image_size( 'landscape-4000x2250', 4000, 2250, true );

	// square image sizes -- cropped
	add_image_size( 'square-100x100', 100, 100, true );
	add_image_size( 'square-200x200', 200, 200, true );
	add_image_size( 'square-400x400', 400, 400, true );
	add_image_size( 'square-800x800', 800, 800, true );
	add_image_size( 'square-1200x1200', 1200, 1200, true );
	add_image_size( 'square-1600x1600', 1600, 1600, true );
	add_image_size( 'square-2400x2400', 2400, 2400, true );
	add_image_size( 'square-3200x3200', 3200, 3200, true );
	add_image_size( 'square-4000x4000', 4000, 4000, true );

	// portrait image sizes -- cropped (9:16)
	add_image_size( 'portrait-113x200', 113, 200, true );
	add_image_size( 'portrait-225x400', 225, 400, true );
	add_image_size( 'portrait-450x800', 450, 800, true );
	add_image_size( 'portrait-675x1200', 675, 1200, true );
	add_image_size( 'portrait-900x1600', 900, 1600, true );
	add_image_size( 'portrait-1350x2400', 1350, 2400, true );
	add_image_size( 'portrait-1800x3200', 1800, 3200, true );
	add_image_size( 'portrait-2250x4000', 2250, 4000, true );

	// fit responsive image sizes image sizes -- image will resize to fit dimensions and maintain aspect ratio
	add_image_size( 'fit-200x113', 200, 113 );
	add_image_size( 'fit-400x225', 400, 225 );
	add_image_size( 'fit-800x450', 800, 450 );
	add_image_size( 'fit-1200x675', 1200, 675 );
	add_image_size( 'fit-1600x900', 1600, 900 );
	add_image_size( 'fit-2400x1350', 2400, 1350 );
	add_image_size( 'fit-3200x1800', 3200, 1800 );
	add_image_size( 'fit-4000x2250', 4000, 2250 );
}

/**
 * Adds some of the custom image sizes into the names choose array.
 *
 * @param array $sizes The array or image size->names mapping.
 *
 * @return array The updated array of image size->names.
 */
function image_names( $sizes ) {
	$sizes['landscape-400x225']  = __( 'Small Landscape', 'us-weekly' );
	$sizes['landscape-1600x900'] = __( 'Large Landscape', 'us-weekly' );
	$sizes['square-400x400']     = __( 'Small Square', 'us-weekly' );
	$sizes['square-1600x1600']   = __( 'Large Square', 'us-weekly' );
	$sizes['portrait-225x400']   = __( 'Small Portrait', 'us-weekly' );
	$sizes['portrait-900x1600']  = __( 'Large Portrait', 'us-weekly' );

	return $sizes;
}