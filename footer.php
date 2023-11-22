	</main>
	<!-- Footer -->
	<footer class="footer">
		<div class="container-fluid">
			<div class="footer-top">
				<div class="footer-widget">
					<?php
					get_template_part_args(
						'template-parts/content-modules-text',
						array(
							'v'  => 'footer_heading',
							't'  => 'h3',
							'tc' => 'footer-heading',
							'o'  => 'o',
						)
					);
					?>
				</div>
				<div class="footer-widget">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'footermenu',
							'container'      => false,
							'menu_class'     => 'footer-menu',
						)
					);
					?>
				</div>
				<div class="footer-widget">
					<?php if ( have_rows( 'social_links', 'options' ) ) : ?>
						<ul class="footer-socials">
							<?php
							while ( have_rows( 'social_links', 'options' ) ) :
								the_row();
								get_template_part_args(
									'template-parts/content-modules-cta',
									array(
										'v' => 'link',
										'c' => 'link link-reverse footer-social',
										'w' => 'li',
									)
								);
							endwhile;
							?>
						</ul>
					<?php endif; ?>
				</div>
				<div class="footer-widget">
					<?php
					$partners = get_field( 'partner_images', 'options' );
					if ( $partners ) :
						?>
						<div class="footer-partners">
							<?php foreach ( $partners as $partner ) : ?>
								<img src="<?php echo esc_url( $partner['url'] ); ?>" alt="<?php echo esc_attr( $partner['alt'] ); ?>">
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<div class="footer-bottom">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footerbottommenu',
						'container'      => false,
						'menu_class'     => 'footer-bottom-menu',
					)
				);
				?>
				<?php
				$copyright = get_field( 'copyright', 'options' );
				if ( $copyright ) :
					$copyright = str_replace( '{year}', date( 'Y' ), $copyright );
					?>
					<p class="footer-copyright"><?php echo esc_html( $copyright ); ?></p>
				<?php endif; ?>
			</div>
		</div>
	</footer>
	<?php wp_footer(); ?>
	<?php
	get_template_part_args(
		'template-parts/content-modules-text',
		array(
			'v' => 'tracking_footer',
			'o' => 'o',
		)
	);
	?>
</body>
</html>
