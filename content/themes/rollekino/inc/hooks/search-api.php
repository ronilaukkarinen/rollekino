<?php
/**
 * @Author: Timi Wahalahti
 * @Date:   2021-06-11 13:17:44
 * @Last Modified by:   Roni Laukkarinen
 * @Last Modified time: 2021-09-04 23:36:44
 * @package rollekino
 */

namespace Air_Light;

// Add our REST API endpoint for search.
function search_endpoint_init() {
  register_rest_route( 'rollekino/v1',
    '/search',
      array(
      'methods'   => 'GET',
      'callback'  => __NAMESPACE__ . '\search_endpoint_callback',
    )
  );
} // end wpfi_rest_api_search_init

// REST API search endpoint callback.
function search_endpoint_callback( $request ) {
  $data = [];

  // bail if no search param.
  if ( ! isset( $_GET['s'] ) ) { // phpcs:ignore
    return $data;
  }

  // used later to prepare post object
  $rest_controller = new \WP_REST_Post_Types_Controller();

  $search_phrase = sanitize_text_field( $_GET['s'] ); // phpcs:ignore

  // try to load from cache
  if ( false !== wp_cache_get( $search_phrase, 'rollekinosearch' ) ) {
    return wp_cache_get( $search_phrase, 'rollekinosearch' );
  }

  // Build default args
  $default_args = array(
    'WP_Query' => [
      's'                      => $search_phrase,
      'posts_status'           => 'publish',
      'has_password'           => false,
      'no_found_rows'          => true,
      'cache_results'          => true,
      'update_post_term_cache' => false,
      'posts_per_page'         => 999, // phpcs:ignore
    ],
  );

  $queries = [
    'post' => [
      'type'  => 'WP_Query',
      'title' => 'Sisältö',
      'args'  => [
        'post_type' => [ 'post', 'page', 'movie' ],
      ],
    ],
  ];

  foreach ( $queries as $key => $settings ) {
    $classname = $settings['type'];
    $query = new $classname( array_merge( $default_args[ $classname ], $settings['args'] ) );
    $data[ $key ]['title'] = $settings['title'];

    relevanssi_do_query( $query );

    // Loop results if WP_Query
    if ( 'WP_Query' === $settings['type'] && $query->have_posts() ) {
      $data[ $key ]['count'] = $query->post_count;

      while ( $query->have_posts() ) {
        $query->the_post();

        // add result to response
        $item = $rest_controller->prepare_response_for_collection( $query->post );

        ob_start();
        if ( 'movie' === $key ) {
          include get_template_part( 'template-parts/movie/movie-listing-single', '', [ 'post_id' => $item->ID ] );
        } else {
          include get_template_part( 'template-parts/loops', 'post', [ 'post_id' => $item->ID ] );
        }

        $html = ob_get_clean();
        $data[ $key ]['items'][] = [
          'id'    => $item->ID,
          'html'  => $html,
        ];
        // continue;

        // Remove some un-necssary data
        unset( $item->post_content );
        unset( $item->post_password );
        unset( $item->to_ping );
        unset( $item->pinged );
        unset( $item->post_content_filtered );
        unset( $item->post_mime_type );
        unset( $item->filter );
        unset( $item->menu_order );

        $data[ $key ]['items'][] = $item;
      }
    }
  }

  // wp_cache_add( $search_phrase, $data, 'rollekinosearch', HOUR_IN_SECONDS );

  return $data;
} // end search_endpoint_callback
