<?php
// Image Sizes
add_image_size( 'block-image-lg', 438, 611, true );
add_image_size( 'block-image-lg-2x', 876, 1222, true );
add_image_size( 'block-image-sm', 438, 331, true );
add_image_size( 'block-image-sm-2x', 876, 662, true );
add_image_size( 'team-image', 1124, 624, true );
add_image_size( 'team-image-2x', 2248, 1248, true );
add_image_size( 'team-member', 323, 466, true );
add_image_size( 'team-member-2x', 646, 932, true );
add_image_size( 'team-slide', 251, 362, true );
add_image_size( 'team-slide-2x', 502, 724, true );


/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Hades
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function theme_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	return $classes;
}
add_filter( 'body_class', 'theme_body_classes' );

//Styling login form
function my_login_stylesheet() {
    wp_enqueue_style( 'custom-login', get_stylesheet_directory_uri() . '/assets/css/style-login.css' );
    // wp_enqueue_script( 'custom-login', get_stylesheet_directory_uri() . '/style-login.js' );
}
add_action( 'login_enqueue_scripts', 'my_login_stylesheet' );


// disable for post types
// add_filter('use_block_editor_for_post_type', '__return_false', 10);
// add_action('init', 'my_remove_editor_from_post_type');
// function my_remove_editor_from_post_type() {
// 	remove_post_type_support( 'page', 'editor' );
// }

add_post_type_support( 'page', 'excerpt' );

//** *Enable upload for webp image files.*/
function webp_upload_mimes($existing_mimes) {
	$existing_mimes['webp'] = 'image/webp';
	return $existing_mimes;
}
add_filter('mime_types', 'webp_upload_mimes');

//Wp ajax init
add_action( 'wp_head', 'my_wp_ajaxurl' );
function my_wp_ajaxurl() {
	$url = parse_url( home_url( ) );
	if( $url['scheme'] == 'https' ){
	   $protocol = 'https';
	}        
	else{
	    $protocol = 'http';
	}
    ?>
    <?php global $wp_query; ?>
    <script type="text/javascript">
        var ajaxurl = '<?php echo admin_url( 'admin-ajax.php', $protocol ); ?>';
    </script>
    <?php
}
/* Disable WordPress Admin Bar for all users */
// add_filter( 'show_admin_bar', '__return_false' );

/**
 * Like get_template_part() put lets you pass args to the template file
 * Args are available in the tempalte as $template_args array
 *
 * @param string $file template file url
 * @param mixed  $template_args style argument list
 * @param mixed  $cache_args cache args
 *  https://wordpress.stackexchange.com/questions/176804/passing-a-variable-to-get-template-part
 */
function get_template_part_args( $file, $template_args = array(), $cache_args = array() ) {
	$template_args = wp_parse_args( $template_args );
	$cache_args    = wp_parse_args( $cache_args );
	if ( $cache_args ) {
		foreach ( $template_args as $key => $value ) {
			if ( is_scalar( $value ) || is_array( $value ) ) {
				$cache_args[ $key ] = $value;
			} elseif ( is_object( $value ) && method_exists( $value, 'get_id' ) ) {
				$cache_args[ $key ] = call_user_func( 'get_id', $value );
			}
		}
		// phpcs:disabled WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize
		$cache = wp_cache_get( $file, serialize( $cache_args ) );
		if ( false !== $cache ) {
			if ( ! empty( $template_args['return'] ) ) {
				return $cache;
			}
			// phpcs:disabled WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $cache;
			return;
		}
	}
	$file_handle = $file;
	do_action( 'start_operation', 'hm_template_part::' . $file_handle );
	if ( file_exists( get_stylesheet_directory() . '/' . $file . '.php' ) ) {
		$file = get_stylesheet_directory() . '/' . $file . '.php';
	} elseif ( file_exists( get_template_directory() . '/' . $file . '.php' ) ) {
		$file = get_template_directory() . '/' . $file . '.php';
	}
	ob_start();
	$return = require $file;
	$data   = ob_get_clean();
	do_action( 'end_operation', 'hm_template_part::' . $file_handle );
	if ( $cache_args ) {
		wp_cache_set( $file, $data, serialize( $cache_args ), 3600 );
	}
	if ( ! empty( $template_args['return'] ) ) {
		if ( false === $return ) {
			return false;
		} else {
			return $data;
		}
	}
	echo $data;
}

/**
 * Get child menu items
 * @param id $parent_id parent menu id
 * @param array $nav_menu_items nav menu items array
 * @param boolean $depth
 */
function get_nav_menu_item_children( $parent_id, $nav_menu_items, $depth = true ) {
    $nav_menu_item_list = array();
    foreach ( (array) $nav_menu_items as $nav_menu_item ) {
        if ( $nav_menu_item->menu_item_parent == $parent_id ) {
            $nav_menu_item_list[] = $nav_menu_item;
            if ( $depth ) {
                if ( $children = get_nav_menu_item_children( $nav_menu_item->ID, $nav_menu_items ) ){
                    $nav_menu_item_list = array_merge( $nav_menu_item_list, $children );
                }
            }
        }
    }
    return $nav_menu_item_list;
}

if ( function_exists( 'custom_mega_menu' ) ) {
	/**
	 * Create custom mega menu
	 * @param string $theme_location Theme location
	 */
	function custom_mega_menu($theme_location) {

		if ( ($theme_location) && ( $locations = get_nav_menu_locations() ) && isset($locations[$theme_location]) ) {

			$menu_list = '';
			$post_id = get_the_ID();

			$menu = get_term($locations[$theme_location], 'nav_menu');
			$menu_items = wp_get_nav_menu_items($menu->term_id);

			foreach ( $menu_items as $menu_item ) {
				$id = get_post_meta( $menu_item->ID, '_menu_item_object_id', true );

				$cols = get_field('cols', $menu_item);
				$css_class = get_field('cssclass', $menu_item);

				if ( !$menu_item->menu_item_parent ) {
					$curr_id = $menu_item->ID;
					$menu_items2 = get_nav_menu_item_children( $curr_id, $menu_items );

					if ( $menu_items2 ) {
						$menu_list .= '<li class="menu-item-has-children ' . $css_class . '">' . "\n";
					} else {
						$menu_list .= '<li class="' . ( ( $id == $post_id ) ? 'current-item ' : '' ) . $css_class . '">' . "\n";
					}

					$menu_list .= '<a href="' . $menu_item->url . '">' . $menu_item->title . '</a>' . "\n";

					if ( $menu_items2 ) {
						$menu_list .= '<div class="drop ' . ( ( $cols == 2 ) ? 'drop-v2' : 'drop-v3' ) . '"><div class="drop-box"><ul class="drop-nav">' . "\n";
					
						foreach ( $menu_items2 as $menu_item2 ) {
							if ( $menu_item2->menu_item_parent == $curr_id ) {
								$curr_id2 = $menu_item2->ID;

								$menu_list .= '<li>';
								$menu_list .= '<a href="' . $menu_item2->url . '">' . $menu_item2->title . '</a>';

								$menu_items3 = get_nav_menu_item_children( $curr_id2, $menu_items );

								if ( $menu_items3 ) {
									$menu_list .= '<ul>';

									foreach ( $menu_items3 as $menu_item3 ) {
										$menu_list .= '<li>';
										$menu_list .= '<a href="' . $menu_item3->url . '">' . $menu_item3->title . '</a>';

										$subheading = get_field('subheading', $menu_item3);
										if ( $subheading ) {
											$menu_list .= '<span>' . $subheading . '</span>';
										}

										$menu_list .= '</li>';
									}

									$menu_list .= '</ul>';
								}

								$menu_list .= '</li>' . "\n";
							}
						}

						$menu_list .= '</ul>';

						$image = get_field('image', $menu_item);
						$heading = get_field('heading', $menu_item);
						$content = get_field('content', $menu_item);
						$link_text = get_field('link_text', $menu_item);
						$link_url = get_field('link_url', $menu_item);
						$link_target = get_field('open_new_window', $menu_item) ? '_blank' : '';

						$menu_list .= '<div class="drop-card">';
						if ( $image ) {
							$menu_list .= '<div class="drop-card-image"><a href="' . $link_url . '" target="' . $link_target . '"><img src="' . $image['url'] . '" alt="' . $image['alt'] . '" /></a></div>';
						}
						if ( $heading ) {
							$menu_list .= '<h4 class="drop-card-heading">' . $heading . '</h4>';
						}
						if ( $content ) {
							$menu_list .= '<div class="drop-card-content">' . $content . '</div>';
						}
						if ( $link_text && $link_url ) {
							$menu_list .= '<div class="drop-card-link"><a href="' . $link_url . '" target="' . $link_target . '">' . $link_text . '<span class="link-accent-arrow"></span></a></div>';
						}
						$menu_list .= '</div>';


						$menu_list .= '</div></div>' . "\n";
					}

					$menu_list .= '</li>' . "\n";

				}
				
			}
		}
		echo $menu_list;
	}
}