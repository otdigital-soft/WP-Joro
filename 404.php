<?php
/**
 * 404 Template
 */
get_header();
?>
<section class="error-404">
	<div class="container">
		<?php
		get_template_part_args(
			'template-parts/content-modules-text',
			array(
				'v'  => 'error_heading',
				't'  => 'h1',
				'tc' => 'error-404__heading a-up',
				'o'  => 'o',
			)
		);
		?>
		<?php
		get_template_part_args(
			'template-parts/content-modules-text',
			array(
				'v'  => 'error_content',
				't'  => 'h1',
				'tc' => 'error-404__content a-up a-delay-1',
				'o'  => 'o',
			)
		);
		?>
		<div class="line"></div>
		<div class="error-404__cta a-up a-delay-3">
			Head back <a href="<?php echo esc_url( home_url() ); ?>" class="link link-reverse">Home</a>
		</div>
	</div>
</section>
<?php
get_footer();
