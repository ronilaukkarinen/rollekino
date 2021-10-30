<?php
/**
 * All hooks that are run in the theme are listed here
 *
 * @package rollekino
 */

namespace Air_Light;

/**
 * Breadcrumb
 */
// require get_theme_file_path( 'inc/hooks/breadcrumb.php' );

/**
 * General hooks
 */
require get_theme_file_path( 'inc/hooks/general.php' );
add_action( 'widgets_init', __NAMESPACE__ . '\widgets_init' );
add_filter( 'air_helper_custom_settings_post_ids', __NAMESPACE__ . '\custom_settings_post_ids' );
add_filter( 'term_link', __NAMESPACE__ . '\rewrite_term_link', 10, 3 );
add_filter( 'air_helper_disable_views_search', '__return_false' );
add_filter( 'air_helper_disable_views_archive', '__return_false' );
// add_action( 'save_post', __NAMESPACE__ . '\maybe_clear_transient_cache' );
// add_action( 'admin_bar_menu', __NAMESPACE__ . '\adminbar_add_cache_clear_link', 9999 );
// add_action( 'admin_init', __NAMESPACE__ . '\adminbar_maybe_clear_transient_cache' );

/**
 * Scripts and styles associated hooks
 */
require get_theme_file_path( 'inc/hooks/scripts-styles.php' );
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_polyfills' );
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_theme_scripts' );
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\movie_archive_scripts' );
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\localize_search' );

// NB! If you use ajax functionality in Gravity Forms, remove this line
// to prevent Uncaught ReferenceError: jQuery is not defined
add_action( 'wp_default_scripts', __NAMESPACE__ . '\move_jquery_into_footer' );

/**
 * Gutenberg associated hooks
 */
require get_theme_file_path( 'inc/hooks/gutenberg.php' );
add_filter( 'allowed_block_types', __NAMESPACE__ . '\allowed_block_types', 10, 2 );
add_filter( 'use_block_editor_for_post_type', __NAMESPACE__ . '\use_block_editor_for_post_type', 10, 2 );
add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\register_block_editor_assets' );
add_filter( 'block_editor_settings', __NAMESPACE__ . '\remove_gutenberg_inline_styles', 10, 2 );
add_action( 'after_setup_theme', __NAMESPACE__ . '\setup_editor_styles' );
add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\block_editor_title_input_styles' );

/**
 * ACF blocks
 */
require get_theme_file_path( 'inc/hooks/acf-blocks.php' );
add_filter( 'block_categories', __NAMESPACE__ . '\acf_blocks_add_category_in_gutenberg', 10, 2 );
add_action( 'acf/init', __NAMESPACE__ . '\acf_blocks_init' );

/**
 * Form related hooks
 */
require get_theme_file_path( 'inc/hooks/forms.php' );
add_action( 'gform_enqueue_scripts', __NAMESPACE__ . '\dequeue_gf_stylesheets', 999 );

/**
 * Movie hooks
 */
require get_theme_file_path( 'inc/hooks/movie-hooks.php' );

/**
 * WP All Import related hooks
 */
require get_theme_file_path( 'inc/hooks/wp-all-import-hooks.php' );

/**
 * REST API related
 */
require get_theme_file_path( 'inc/hooks/api.php' );
add_action( 'rest_api_init', __NAMESPACE__ . '\register_movie_api_fields' );
add_filter( 'rest_movie_query', __NAMESPACE__ . '\order_movie_query', 10, 2 );
require get_theme_file_path( 'inc/hooks/search-api.php' );
add_action( 'rest_api_init', __NAMESPACE__ . '\search_endpoint_init' );

/**
 * Validate HTML
 */
require get_theme_file_path( 'inc/hooks/validate-hooks.php' );

/**
 * Set max output results for search page
 * @link https://wordpress.stackexchange.com/questions/357650/adjust-the-results-quantity-for-search-results-page-pagination
 */
if ( ! is_admin() ) {
  add_filter( 'request', __NAMESPACE__ . '\search_results_pro_page' );
}

function search_results_pro_page( $queryvars ) {

    // Set only on search
    if ( isset( $_REQUEST['s'] ) ) { // phpcs:ignore
        // Adjust number of results shown
        $queryvars['posts_per_page'] = -1;
    }

    // Return amount search results
    return $queryvars;
}
