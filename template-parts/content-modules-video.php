<?php
$image        = isset( $args['image'] ) ? $args['image'] : false;
$video        = isset( $args['video'] ) ? $args['video'] : false;
$mobile_video = isset( $args['mobile_video'] ) ? $args['mobile_video'] : false;
if ( $video ) :
	?>
	<video src="<?php echo esc_url( $video ); ?>" muted playsinline autoplay loop preload="metadata" <?php echo $image ? 'poster="' . esc_url( $image ) . '"' : ''; ?>">
		<source src="<?php echo esc_url( $video ); ?>" type="video/mp4">
		<source src="<?php echo esc_url( $mobile_video ); ?>" type="video/mp4" media="(max-width: 767px)">
	</video>
<?php else : ?>
	<picture>
		<img src="<?php echo esc_url( $image ); ?>" alt="" loading="lazy">
	</picture>
	<?php
endif;
