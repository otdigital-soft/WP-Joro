<?php
/**
 * Template Name: Home
 * Template Post Type: page
 */
get_header();
?>
<?php
$image        = get_field( 's1_image' );
$video        = get_field( 's1_video' );
$mobile_video = get_field( 's1_mobile_video' );
?>
<!-- Hero -->
<section class="hero">
	<div class="hero-bg bg-cover">
		<?php
		get_template_part(
			'template-parts/content-modules',
			'video',
			array(
				'image'        => $image,
				'video'        => $video,
				'mobile_video' => $mobile_video,
			)
		);
		?>
	</div>
	<div class="container">
		<?php
		get_template_part_args(
			'template-parts/content-modules-text',
			array(
				'v'  => 's1_heading',
				't'  => 'h1',
				'tc' => 'hero-heading split-text',
				'o'  => 'f',
			)
		);
		?>
	</div>
</section>
<?php if ( have_rows( 'block_images' ) ) : ?>
<!-- Block Images -->
<section class="block-images" id="about">
	<svg class="block-images__bg">
		<filter id='noiseFilter'>
			<feTurbulence type='fractalNoise'
		baseFrequency='0.4'
			num0ctaves='3' stitchTiles='stitch'/>
		</filter>
	</svg>
	<div class="container">
		<?php
		while ( have_rows( 'block_images' ) ) :
			the_row();
			$image_sizes = array( 'block-image-lg', 'block-image-sm', 'block-image-sm' );
			$image_size  = $image_sizes[ get_row_index() - 1 ];
			$htag        = get_row_index() == 1 ? 'h2' : 'h3';
			$video       = get_sub_field( 'video' );
			?>
			<div class="block-image">
				<?php
				if ( $video ) {
					?>
				<div class="block-image__img bg-cover a-up a-op a-delay-1">
					<video src="<?php echo esc_url( $video ); ?>" muted playsinline autoplay loop preload="metadata">
						<source src="<?php echo esc_url( $video ); ?>" type="video/mp4">
						<source src="<?php echo esc_url( $mobile_video ); ?>" type="video/mp4" media="(max-width: 767px)">
					</video>
				</div>
					<?php
				} else {
					get_template_part_args(
						'template-parts/content-modules-image',
						array(
							'v'     => 'image',
							'v2x'   => false,
							'is'    => $image_size,
							'is_2x' => $image_size . '-2x',
							'w'     => 'div',
							'wc'    => 'block-image__img bg-cover a-up a-op a-delay-1',
						)
					);
				}
				?>
				<div class="block-image__content">
					<?php
					get_template_part_args(
						'template-parts/content-modules-text',
						array(
							'v'  => 'sub_title',
							't'  => 'h6',
							'tc' => 'block-image__subtitle a-up',
						)
					);
					?>
					<?php
					get_template_part_args(
						'template-parts/content-modules-text',
						array(
							'v'  => 'title',
							't'  => $htag,
							'tc' => 'block-image__title a-up a-delay-1',
						)
					);
					?>
					<?php
					get_template_part_args(
						'template-parts/content-modules-text',
						array(
							'v'  => 'content',
							't'  => 'div',
							'tc' => 'block-image__content default-editor a-up a-delay-2',
						)
					);
					?>
				</div>
			</div>
		<?php endwhile; ?>
	</div>
</section>
<?php endif; ?>
<!-- Team -->
<section class="team" id="team">
	<div class="container">
		<?php
		get_template_part_args(
			'template-parts/content-modules-text',
			array(
				'v'  => 's3_heading',
				't'  => 'h2',
				'tc' => 'team-heading a-up',
				'o'  => 'f',
			)
		);
		?>
		<?php
		get_template_part_args(
			'template-parts/content-modules-text',
			array(
				'v'  => 's3_content',
				't'  => 'div',
				'tc' => 'team-content default-editor a-up a-delay-1',
				'o'  => 'f',
			)
		);
		?>
		<div class="team-image d-md-only a-up a-delay-2">
			<?php
			get_template_part_args(
				'template-parts/content-modules-image',
				array(
					'v'     => 's3_image',
					'v2x'   => false,
					'is'    => 'team-image',
					'is_2x' => 'team-image-2x',
					'c'     => 'team-image__bg',
					'o'     => 'f',
				)
			);
			?>
			<?php if ( have_rows( 'people' ) ) : ?>
				<div class="team-image__dots">
					<?php
					while ( have_rows( 'people' ) ) :
						the_row();
						$x          = get_sub_field( 'x' ) ? get_sub_field( 'x' ) . '%' : 0;
						$y          = get_sub_field( 'y' ) ? get_sub_field( 'y' ) . '%' : 0;
						$name       = get_sub_field( 'name' );
						$first_name = explode( ' ', $name )[0];
						?>
						<button class="team-image__dot team-popup__btn"
							data-target="#team-popup-<?php echo esc_attr( get_row_index() ); ?>"
							style="top: <?php echo esc_attr( $y ); ?>; left: <?php echo esc_attr( $x ); ?>;"
							aria-label="<?php __( 'Team Member Popup', 'am' ); ?>"><?php echo esc_html( $first_name ); ?></button>
					<?php endwhile; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<?php if ( have_rows( 'people' ) ) : ?>
		<div class="team-carousel d-sm-only a-up a-delay-2">
			<?php
			while ( have_rows( 'people' ) ) :
				the_row();
				?>
				<div class="team-slide team-popup__btn" data-target="#team-popup-<?php echo esc_attr( get_row_index() ); ?>">
					<?php
					get_template_part_args(
						'template-parts/content-modules-image',
						array(
							'v'     => 'image',
							'v2x'   => false,
							'is'    => 'team-slide',
							'is_2x' => 'team-slide-2x',
							'c'     => 'team-slide__img',
						)
					);
					?>
					<?php
					get_template_part_args(
						'template-parts/content-modules-text',
						array(
							'v'  => 'name',
							't'  => 'h3',
							'tc' => 'team-slide__heading',
						)
					);
					?>
					<?php
					get_template_part_args(
						'template-parts/content-modules-text',
						array(
							'v'  => 'role',
							't'  => 'p',
							'tc' => 'team-slide__role',
						)
					);
					?>
				</div>
			<?php endwhile; ?>
		</div>
		<div class="team-popups">
			<?php
			while ( have_rows( 'people' ) ) :
				the_row();
				?>
				<div class="team-popup" id="team-popup-<?php echo esc_attr( get_row_index() ); ?>">
					<button class="team-popup__close" aria-label="<?php __( 'Close Team Popup', 'am' ); ?>">
						<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/icon-close.svg' ); ?>" alt="">
					</button>
					<div class="team-popup__left">
						<?php
						get_template_part_args(
							'template-parts/content-modules-image',
							array(
								'v'     => 'image',
								'v2x'   => false,
								'is'    => 'team-member',
								'is_2x' => 'team-member-2x',
								'c'     => 'team-popup__image bg-cover',
							)
						);
						?>
						<?php
						get_template_part_args(
							'template-parts/content-modules-text',
							array(
								'v'  => 'name',
								't'  => 'p',
								'tc' => 'h2 team-popup__name',
							)
						);
						?>
						<?php
						get_template_part_args(
							'template-parts/content-modules-text',
							array(
								'v'  => 'role',
								't'  => 'p',
								'tc' => 'h6 team-popup__role',
							)
						);
						?>
					</div>
					<?php
					get_template_part_args(
						'template-parts/content-modules-text',
						array(
							'v'  => 'bio',
							't'  => 'div',
							'tc' => 'team-popup__bio default-editor',
						)
					);
					?>
				</div>
			<?php endwhile; ?>
		</div>
	<?php endif; ?>
</section>
<!-- Ethos -->
<section class="ethos" id="ethos">
	<div class="container-fluid">
		<?php
		get_template_part_args(
			'template-parts/content-modules-text',
			array(
				'v'  => 's4_heading',
				't'  => 'h2',
				'tc' => 'ethos-heading a-up',
				'o'  => 'f',
			)
		);
		?>
		<?php
		get_template_part_args(
			'template-parts/content-modules-text',
			array(
				'v'  => 's4_content',
				't'  => 'div',
				'tc' => 'ethos-content default-editor a-up a-delay-1',
				'o'  => 'f',
			)
		);
		?>
		<?php if ( have_rows( 's4_blocks' ) ) : ?>
			<div class="ethos-blocks">
				<?php
				while ( have_rows( 's4_blocks' ) ) :
					the_row();
					?>
					<div class="ethos-block a-op a-delay-<?php echo esc_attr( get_row_index() ); ?>">
						<div class="ethos-block__main">
							<?php
							get_template_part_args(
								'template-parts/content-modules-image',
								array(
									'v'     => 'image',
									'v2x'   => false,
									'is'    => false,
									'is_2x' => false,
									'c'     => 'inline-svg',
									'w'     => 'div',
									'wc'    => 'ethos-block__icon',
								)
							);
							?>
							<?php
							get_template_part_args(
								'template-parts/content-modules-text',
								array(
									'v'  => 'heading',
									't'  => 'h4',
									'tc' => 'ethos-block__heading text-lg',
								)
							);
							?>
							<?php
							get_template_part_args(
								'template-parts/content-modules-text',
								array(
									'v'  => 'content',
									't'  => 'div',
									'tc' => 'ethos-block__desc',
								)
							);
							?>
						</div>
					</div>
				<?php endwhile; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
<?php
$image        = get_field( 's5_image' );
$video        = get_field( 's5_video' );
$mobile_video = get_field( 's5_mobile_video' );
?>
<!-- CTA -->
<section class="cta">
	<div class="cta-bg bg-cover">
		<?php
		get_template_part(
			'template-parts/content-modules',
			'video',
			array(
				'image'        => $image,
				'video'        => $video,
				'mobile_video' => $mobile_video,
			)
		);
		?>
	</div>
	<div class="container">
		<?php
		get_template_part_args(
			'template-parts/content-modules-text',
			array(
				'v'  => 's5_heading',
				't'  => 'h2',
				'tc' => 'cta-heading',
				'o'  => 'f',
			)
		);
		?>
	</div>
</section>
<?php
get_footer();
