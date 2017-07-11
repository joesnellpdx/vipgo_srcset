<?php
/**
 * Template Name: Photon Test
 *
 */

use __\Us_Weekly\Helpers;

get_header();
?>

	<div class="page-static">

		<?php while ( have_posts() ) : the_post() ?>

			<article class="page-static-main">

				<header class="hub-header" data-page-title="<?php echo the_title_attribute(); ?>">

					<div class="hub-header-text container-center">

						<?php the_title( '<h1>', '</h1>' ); ?>

					</div>

				</header>

				<div class="grid content">

					<div class="page-static-main-content">

						<div class="page-stat-main-content-inner">

							<?php

							$image_id = get_post_thumbnail_id( get_the_id() );
							Helpers\get_srcset( $image_id, 'landscape-3200x1800' ); ?>

							<p>This is the function the_rwd_image() with a "native" image size:</p><br>
							<?php Helpers\the_rwd_image( $image_id, true, '100vw', 'false', 'native-1600', 'native-1600' ); ?>
							<br><p>This is the function the_rwd_image() with a "fit" image size:</p><br>
							<?php Helpers\the_rwd_image( $image_id, true, '100vw', 'false', 'fit-1600x900', 'fit-1600x900' ); ?>
							<br><p>This is the function the_rwd_image() with a "landsape" image size:</p><br>
							<?php Helpers\the_rwd_image( $image_id, true, '100vw', 'false', 'landscape-1600x900', 'landscape-1600x900' ); ?>
							<br><p>This is the function the_img_fit_figure():</p><br>
							<?php Helpers\the_img_fit_figure( $image_id, 'img-figure-test', '100vw', 'landscape-3200x1800', 'landscape-1600x900' ); ?>
							<br><p>This is the function picture_art_direct():</p><br>
							<?php Helpers\picture_art_direct( $image_id, $image_id, 'square-200x200', 'landscape-2400x1350', '100vw', '100vw', 'square-800x800', 'landscape-2400x1350', '768', 'test-class', true ); ?>
							<br>
							<?php the_content(); ?>

						</div>

					</div>

				</div>

			</article>

		<?php endwhile; ?>

	</div>

<?php get_footer(); ?>