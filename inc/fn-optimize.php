<?php

/* OPTIMIZATION SCRIPTS */

/**
 * Disable the emoji's
 */
function disable_emojis() {
 remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
 remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
 remove_action( 'wp_print_styles', 'print_emoji_styles' );
 remove_action( 'admin_print_styles', 'print_emoji_styles' ); 
 remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
 remove_filter( 'comment_text_rss', 'wp_staticize_emoji' ); 
 remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
 add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
 add_filter( 'wp_resource_hints', 'disable_emojis_remove_dns_prefetch', 10, 2 );
}
add_action( 'init', 'disable_emojis' );

/**
 * Filter function used to remove the tinymce emoji plugin.
 * 
 * @param array $plugins 
 * @return array Difference betwen the two arrays
 */
function disable_emojis_tinymce( $plugins ) {
 if ( is_array( $plugins ) ) {
 return array_diff( $plugins, array( 'wpemoji' ) );
 } else {
 return array();
 }
}

/**
 * Remove emoji CDN hostname from DNS prefetching hints.
 *
 * @param array $urls URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed for.
 * @return array Difference betwen the two arrays.
 */
function disable_emojis_remove_dns_prefetch( $urls, $relation_type ) {
 if ( 'dns-prefetch' == $relation_type ) {
 /** This filter is documented in wp-includes/formatting.php */
 $emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' );

$urls = array_diff( $urls, array( $emoji_svg_url ) );
 }

return $urls;
}


add_action('admin_init', function () {
    // Redirect any user trying to access comments page
    global $pagenow;
    
    if ($pagenow === 'edit-comments.php') {
        wp_redirect(admin_url());
        exit;
    }

    // Remove comments metabox from dashboard
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');

    // Disable support for comments and trackbacks in post types
    foreach (get_post_types() as $post_type) {
        if (post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
});

// Close comments on the front-end
add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open', '__return_false', 20, 2);

// Hide existing comments
add_filter('comments_array', '__return_empty_array', 10, 2);

// Remove comments page in menu
add_action('admin_menu', function () {
    remove_menu_page('edit-comments.php');
});

// Remove comments links from admin bar
add_action('init', function () {
    if (is_admin_bar_showing()) {
        remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
    }
});

function disable_embed(){
wp_dequeue_script( 'wp-embed' );
}
add_action( 'wp_footer', 'disable_embed' );

function disable_embeds_code_init() {

 // Remove the REST API endpoint.
 remove_action( 'rest_api_init', 'wp_oembed_register_route' );

 // Turn off oEmbed auto discovery.
 add_filter( 'embed_oembed_discover', '__return_false' );

 // Don't filter oEmbed results.
 remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );

 // Remove oEmbed discovery links.
 remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );

 // Remove oEmbed-specific JavaScript from the front-end and back-end.
 remove_action( 'wp_head', 'wp_oembed_add_host_js' );
 add_filter( 'tiny_mce_plugins', 'disable_embeds_tiny_mce_plugin' );

 // Remove all embeds rewrite rules.
 add_filter( 'rewrite_rules_array', 'disable_embeds_rewrites' );

 // Remove filter of the oEmbed result before any HTTP requests are made.
 remove_filter( 'pre_oembed_result', 'wp_filter_pre_oembed_result', 10 );
}

add_action( 'init', 'disable_embeds_code_init', 9999 );

function disable_embeds_tiny_mce_plugin($plugins) {
    return array_diff($plugins, array('wpembed'));
}

function disable_embeds_rewrites($rules) {
    foreach($rules as $rule => $rewrite) {
        if(false !== strpos($rewrite, 'embed=true')) {
            unset($rules[$rule]);
        }
    }
    return $rules;
}

//add_filter('xmlrpc_enabled', '__return_false');

remove_action( 'wp_head', 'rsd_link' ) ;

remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

remove_action( 'wp_head', 'wp_generator' ) ;

remove_action( 'wp_head', 'wlwmanifest_link' ) ;

add_filter('use_block_editor_for_post', '__return_false', 10);

/**
 * Disable WooCommerce block styles (front-end).
 */
function themesharbor_disable_woocommerce_block_styles() {
  wp_dequeue_style( 'wc-blocks-style' );
}
add_action( 'wp_enqueue_scripts', 'themesharbor_disable_woocommerce_block_styles' );

// add_action( 'init', 'stop_heartbeat', 1 );
// function stop_heartbeat() {
// global $pagenow;
// if ( $pagenow != 'post.php' && $pagenow != 'post-new.php' )
// wp_deregister_script('heartbeat');
// wp_enqueue_script('heartbeat', get_template_directory_uri().'/includes/js/heartbeat.js');
// }

/****DISABLE WC FILTERS****/
add_filter( 'woocommerce_background_image_regeneration', '__return_false' );
//add_filter( 'woocommerce_helper_suppress_admin_notices', '__return_true' );
add_filter( 'woocommerce_allow_marketplace_suggestions', '__return_false' );
add_filter( 'wp_lazy_loading_enabled', '__return_false' );
add_filter( 'woocommerce_menu_order_count', 'false' );
//add_filter( 'woocommerce_enable_nocache_headers', '__return_false' );
//add_filter( 'woocommerce_admin_disabled', '__return_true' );
add_filter( 'woocommerce_include_processing_order_count_in_menu', '__return_false' );


add_action( 'template_redirect', 'bt_remove_woocommerce_styles_scripts', 999 );

// Remove WooCommerce Marketing Hub & Analytics Menu from the sidebar - for WooCommerce v4.3+
add_filter( 'woocommerce_admin_features', function( $features ) {
    /**
     * Filter the list of features and get rid of the features not needed.
     * 
     * array_values() are being used to ensure that the filtered array returned by array_filter()
     * does not preserve the keys of initial $features array. As key preservation is a default feature 
     * of array_filter().
     */
    return array_values(
        array_filter( $features, function($feature) {
            return ! in_array( $feature, [ 'analytics', 'analytics-dashboard', 'analytics-dashboard/customizable' ] );
        } ) 
    );
} );
/**
 * Remove Woo Styles and Scripts from non-Woo Pages
 * @link https://gist.github.com/DevinWalker/7621777#gistcomment-1980453
 * @since 1.7.0
 */
function bt_remove_woocommerce_styles_scripts() {

        // Skip Woo Pages
		if ( class_exists( 'WooCommerce' ) ) {
			if ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) {
					return;
			}
		}
        // Otherwise...
        remove_action('wp_enqueue_scripts', [WC_Frontend_Scripts::class, 'load_scripts']);
        remove_action('wp_print_scripts', [WC_Frontend_Scripts::class, 'localize_printed_scripts'], 5);
        remove_action('wp_print_footer_scripts', [WC_Frontend_Scripts::class, 'localize_printed_scripts'], 5);
}


add_filter( 'author_link', 'my_author_link' );
 
function my_author_link() {
    return home_url();
}

// Hide stuff in WP Dashboard
function ts_wp_dashboard_setup() {
        remove_action('welcome_panel', 'wp_welcome_panel');
        remove_meta_box('dashboard_primary',       'dashboard', 'side');
        remove_meta_box('dashboard_secondary',     'dashboard', 'side');
        remove_meta_box('dashboard_quick_press',   'dashboard', 'side');
        remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side');
        remove_meta_box('dashboard_php_nag',           'dashboard', 'normal');
        remove_meta_box('dashboard_browser_nag',       'dashboard', 'normal');
        remove_meta_box('health_check_status',         'dashboard', 'normal');
        remove_meta_box('dashboard_activity',          'dashboard', 'normal');
        remove_meta_box('dashboard_right_now',         'dashboard', 'normal');
        remove_meta_box('network_dashboard_right_now', 'dashboard', 'normal');
        remove_meta_box('dashboard_recent_comments',   'dashboard', 'normal');
        remove_meta_box('dashboard_incoming_links',    'dashboard', 'normal');
        remove_meta_box('dashboard_plugins',           'dashboard', 'normal');

}

add_action('wp_dashboard_setup', 'ts_wp_dashboard_setup');

//disable ACF shortcode

add_action( 'acf/init', 'set_acf_settings' );
function set_acf_settings() {
    acf_update_setting( 'enable_shortcode', false );
}

function custom_wp_remove_global_css() {
   remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
   remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );
}
add_action( 'init', 'custom_wp_remove_global_css' );