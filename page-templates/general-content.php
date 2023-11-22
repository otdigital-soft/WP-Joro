<?php
/**
 * Template Name: General Content
 * Template Post Type: page
 */
get_header();
?>
<section class="general-content">
	<div class="container">
		<div class="general-content__top">
			<?php
			get_template_part_args(
				'template-parts/content-modules-text',
				array(
					'v'  => 'heading',
					't'  => 'h1',
					'tc' => 'general-content__heading a-up',
					'o'  => 'f',
				)
			);
			?>
			<?php
			get_template_part_args(
				'template-parts/content-modules-text',
				array(
					'v'  => 'description',
					't'  => 'div',
					'tc' => 'general-content__content default-editor a-up a-delay-1',
					'o'  => 'f',
				)
			);
			?>
		</div>
		<?php if ( have_rows( 'contents' ) ) : ?>
			<div class="general-content__blocks">
				<?php
				while ( have_rows( 'contents' ) ) :
					the_row();
					?>
					<div class="general-content__block a-up">
						<?php
						get_template_part_args(
							'template-parts/content-modules-text',
							array(
								'v'  => 'heading',
								't'  => 'p',
								'tc' => 'general-content__block-heading',
							)
						);
						?>
						<?php
						get_template_part_args(
							'template-parts/content-modules-text',
							array(
								'v'  => 'content',
								't'  => 'div',
								'tc' => 'general-content__block-content default-editor',
							)
						);
						?>
					</div>
				<?php endwhile; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
