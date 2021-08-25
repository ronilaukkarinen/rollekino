<?php
/**
 * @Author: Roni Laukkarinen
 * @Date:   2021-02-04 18:15:59
 * @Last Modified by:   Roni Laukkarinen
 * @Last Modified time: 2021-08-25 21:05:28
 *
 * @package rollekino
 */

namespace Air_Light;

/**
 * Update movie title when saving article
 */
 add_filter( 'wp_insert_post_data', __NAMESPACE__ . '\save_post_function', 10, 2 );

  function save_post_function( $data, $id ) {
    if ( 'movie' === $data['post_type'] ) {

      // Need to define at least IMDb URL or IMDb ID
      if ( ! metadata_exists( 'movie', $id['ID'], 'imdb_url' ) ) {
        $omdb = new \Rooxie\OMDb( getenv( 'OMDB_API_KEY' ) );
        $imdb_url = get_post_meta( $id['ID'], 'imdb_url', true );

        $imdb_id_match = preg_match_all( '/tt\\d{7,8}/', $imdb_url, $ids );
        $imdb_id = $ids[0][0];
        $movie = $omdb->getByImdbId( $imdb_id );

        // Save things in post meta
        if ( ! metadata_exists( 'movie', $id['ID'], '_imdb_rating' ) ) {
          update_post_meta( $id['ID'], '_imdb_rating', '6.7' );
        }

      // Update the post's title.
      $data['post_title'] = $movie->getTitle();
      return $data;
    }

   } else {
     return $data;
   }
 }
