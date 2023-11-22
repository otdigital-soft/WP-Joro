<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
	<meta name="format-detection" content="telephone=no">
	<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php endif; ?>

	<?php wp_head(); ?>

	<?php
	get_template_part_args(
		'template-parts/content-modules-text',
		array(
			'v' => 'tracking_head',
			'o' => 'o',
		)
	);
	?>
</head>

<body <?php body_class(); ?>>
	<?php
	get_template_part_args(
		'template-parts/content-modules-text',
		array(
			'v' => 'tracking_body',
			'o' => 'o',
		)
	);
	?>
	<?php
	$logo = get_field( 'logo', 'options' ) ? get_field( 'logo', 'options' ) : get_template_directory_uri() . '/assets/img/logo.svg';

	$header_theme = 'header--light';
	if ( is_page_template( 'page-templates/general-content.php' ) ) :
		$header_theme = 'header--dark';
	endif;
	?>
	<!-- Header -->
	<header class="header <?php echo esc_attr( $header_theme ); ?>">
		<div class="container-fluid">
			<button class="hamburger">
				<span></span>
			</button>
			<a href="<?php echo esc_url( home_url() ); ?>" class="header-logo">
				<img src="<?php echo esc_url( $logo ); ?>" alt="<?php echo esc_url( bloginfo() ); ?>">
			</a>
			<?php
			get_template_part_args(
				'template-parts/content-modules-cta',
				array(
					'v' => 'header_cta',
					'c' => 'link link-reverse header-link',
					'o' => 'o',
				)
			);
			?>
		</div>
		<div class="header-nav">
			<?php
			$hamburger = get_field( 'hamburger', 'option' );
			if ( $hamburger ) :
				?>
			<div class="header-nav__menus">
				<button class="header-nav__close" aria-label="<?php __( 'Close Header Popup', 'am' ); ?>">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/icon-close.svg' ); ?>" alt="">
				</button>
				<ul class="header-nav__hamburger">
					<?php foreach ( $hamburger as $menu ) : ?>
					<li class="header-nav__hamburger__item">
						<a href="<?php echo esc_url( $menu['menu']['url'] ); ?>" class="menu-link"><?php echo esc_attr( $menu['menu']['title'] ); ?></a>
					</li>
					<?php endforeach; ?>
				</ul>
				<?php endif; ?>
				<?php
				wp_nav_menu(
					array(
						'menu'       => 'Main Menu',
						'container'  => false,
						'menu_class' => 'header-nav__menu',
					)
				);
				?>
			</div>
			<div class="header-nav__back">
				<?php $hamback = get_field( 'hamburger_image', 'option' ); ?>
				<img src="<?php echo esc_url( $hamback['url'] ); ?>" alt="">
			</div>
		</div>
		<div class="header-conversation">
			<div class="header-conversation__back">
				<?php $converback = get_field( 'conversation_image', 'option' ); ?>
				<img src="<?php echo esc_url( $converback['url'] ); ?>" alt="">
			</div>
			<div class="header-conversation__main">
				<button class="header-conversation__close" aria-label="<?php __( 'Close Header Popup', 'am' ); ?>">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/icon-close.svg' ); ?>" alt="">
				</button>
				<?php
				get_template_part_args(
					'template-parts/content-modules-text',
					array(
						'v'  => 'conversation_header',
						't'  => 'h2',
						'o'  => 'o',
						'tc' => 'header-conversation__header',
					)
				);
				?>
				<?php
				get_template_part_args(
					'template-parts/content-modules-text',
					array(
						'v'  => 'conversation_content',
						't'  => 'p',
						'o'  => 'o',
						'tc' => 'header-conversation__content',
					)
				);
				?>
				<h5 class="header-conversation__contact">Contact</h5>
				<div class="header-conversation__social">
					<?php if ( have_rows( 'social_links', 'options' ) ) : ?>
						<ul class="header-conversation__socials">
							<?php
							while ( have_rows( 'social_links', 'options' ) ) :
								the_row();
								get_template_part_args(
									'template-parts/content-modules-cta',
									array(
										'v' => 'link',
										'c' => 'link link-reverse header-social',
										'w' => 'li',
									)
								);
							endwhile;
							?>
						</ul>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</header>
	<!-- Main -->
	<main class="main">
