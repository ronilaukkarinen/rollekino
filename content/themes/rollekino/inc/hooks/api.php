<?php
/**
 * @Author: Roni Laukkarinen
 * @Date: 2021-08-04 16:33:47
 * @Last Modified by:   Roni Laukkarinen
 * @Last Modified time: 2021-09-13 23:25:00
 *
 * @package rollekino
 */

namespace Air_Light;

/**
 * Register API fields for movies
 */
function register_movie_api_fields() {
  register_rest_field(
    'movie',
    'meta',
    [
     'get_callback' => __NAMESPACE__ . '\get_movie_additional_fields',
     'schema'       => null,
    ]
  );
}

/**
 * Get movie fields
 */
function get_movie_additional_fields( $movie ) {
  // Load single listing template for returning
  ob_start();
  get_template_part( 'template-parts/movie/movie-listing-single', '', [ 'post_id' => $movie['id'] ] );
  $rendered_listing = ob_get_clean();
  return [
    'rendered_listing' => $rendered_listing,
  ];
}

/**
 * Order movies by release date or title
 */
function order_movie_query( $query_vars, $request ) {
  wp_reset_postdata();
  $orderby = $request->get_param( 'orderby' );

  if ( ! isset( $orderby ) ) {
    return $query_vars;
  }

  if ( 'date' === $orderby ) {
    /**
     * Use menu order first.
     * This if for next calls to paginate correctly,
     * because otherwise those don't take featured category
     * into consideration and order goes different.
     */
    $query_vars['orderby']   = 'date';
    $query_vars['order']   = 'DESC';
  }

  if ( 'title' === $orderby ) {
    $query_vars['orderby'] = 'title';
    $query_vars['order']   = 'ASC';
  }

  if ( 'meta_value_num' === $orderby ) {
    $query_vars['orderby'] = 'meta_value_num';
    $query_vars['order']   = 'DESC';
    $query_vars['meta_key'] = 'rating';
  }

  return $query_vars;
} // end order_movie_query

// Add sort by meta_value_num to WP REST API
add_filter( 'rest_endpoints', function ( $routes ) {
  // Modifying multiple types here, you won't need the loop if you're just doing posts
  foreach ( [ 'movie' ] as $type ) {
    if ( ! ( $route =& $routes[ '/wp/v2/' . $type ] ) ) { // phpcs:ignore
        continue;
    }

    // Allow ordering by my meta value
    $route[0]['args']['orderby']['enum'][] = 'meta_value_num';

    // Allow only the meta keys that I want
    // $route[0]['args']['meta_key'] = array(
    //   'description'       => 'The meta key to query.',
    //   // 'type'              => 'string',
    //   'enum'              => [ 'rating' ],
    //   'validate_callback' => 'rest_validate_request_arg',
    // );
  }

  return $routes;
});

