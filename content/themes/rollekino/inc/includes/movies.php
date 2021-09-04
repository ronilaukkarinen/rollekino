<?php
/**
 * Movie functions
 *
 * Functions for movies.
 *
 * @package rollekino
 */

namespace Air_Light;

use WP_Query;

function get_movie_filter( $object_name, $taxonomy, $args ) {
  if ( empty( $object_name ) || empty( $taxonomy ) ) {
    return;
  }

  $default_args = [
    'taxonomy'    => $taxonomy,
    'hide_empty'  => true,
    'per_page'    => 50,
  ];

  $args = wp_parse_args( $args, $default_args );

  $filters = get_terms( $args );

  return $filters;
} // end get_movie_filter

function get_initial_movies() {
  $default_movies = [];
  $args = [
    'post_type' => 'movie',
    'posts_per_page'  => 24,
  ];

  $movie_query = new \WP_Query( $args );
  if ( $movie_query->have_posts() ) {
    while ( $movie_query->have_posts() ) {
      $movie_query->the_post();

      $movie = [
        'id'    => get_the_ID(),
        'link'  => get_permalink(),
        'title' => [
          'rendered' => get_the_title(),
        ],
        'content' => [
          'rendered' => get_the_content(),
        ],
        'featured_media' => get_post_thumbnail_id(),
        'meta'           => get_movie_additional_fields( [ 'id' => get_the_ID() ] ),
      ];
      $default_movies[] = $movie;
    }

    wp_reset_postdata();
  }

  return [
    'movies' => $default_movies,
    'count' => $movie_query->found_posts,
    'pages' => $movie_query->max_num_pages,
  ];
}

/**
 * Get exceptions for current post
 *
 * @param int $post_id Current post id
 * @return array Key-value array of exceptions
 */
function get_movie_exceptions( $post_id ) {
  // Get ACF values for movie mix fields
  $movie_exceptions = [];

  foreach ( THEME_SETTINGS['movie_mix_fields'] as $field_slug ) {
    $field = get_field( $field_slug, $post_id );
    if ( ! is_array( $field ) || empty( $field ) ) {
      continue;
    }
    $movie_exceptions[ $field_slug ] = $field;
  }

  // Map exceptions as id => key pair array
  $exception_ids = [];

  foreach ( $movie_exceptions as $key => $ids ) {
    foreach ( $ids as $id ) {
      $exception_ids[ $id ] = $key;
    }
  }

  return $exception_ids;
}

/**
 * Build mixable movie query
 *
 * @param int $post_id Current post id
 * @return object WP Query object
 */
function mixable_movie_query( $post_id = 0 ) {
  return new WP_Query([
    'post_type' => 'movie',
    'post__not_in' => [ $post_id ],
    'posts_per_page' => 99,
    'orderby' => 'title',
    'order' => 'ASC',
    'meta_query' => [ // phpcs:ignore
      [
        'key' => 'can_be_mixed',
        'value' => 1,
        'compare' => '=',
      ],
    ],
  ]);
}

/**
 * Find related posts for movie
 *
 * @param int $movie_id Post id for the movie
 * @return array Array of related post id:s
 */
function get_related_posts_for_movie( $movie_id, $amount ) {
  if ( get_transient( 'related_movie_posts_' . $movie_id ) ) {
    return get_transient( 'related_movie_posts_' . $movie_id );
  }

  $args = [
    'post_type' => 'post',
    'posts_per_page' => $amount,
    'meta_query' => [ // phpcs:ignore
      [
        'key' => 'related_movies',
        'value' => $movie_id,
        'compare' => 'LIKE',
      ],
    ],
  ];
  $related_movie_query = new WP_Query( $args );

  $related_movies = $related_movie_query->have_posts() ? wp_list_pluck( $related_movie_query->posts, 'ID' ) : [];

  set_transient( 'related_movie_posts_' . $movie_id, $related_movies, HOUR_IN_SECONDS );

  return $related_movies;
}
