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
