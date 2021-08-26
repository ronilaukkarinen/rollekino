<?php
/**
 * @Author: Roni Laukkarinen
 * @Date:   2021-02-04 18:15:59
 * @Last Modified by:   Roni Laukkarinen
 * @Last Modified time: 2021-08-26 08:31:32
 *
 * @package rollekino
 */

namespace Air_Light;

/**
 * Update movie title when saving article
 */
add_filter( 'wp_insert_post_data', __NAMESPACE__ . '\save_post_function', 10, 2 );
add_action( 'save_post', __NAMESPACE__ . '\save_post_function_publish' );

function save_post_function_publish( $post_id ) {

  if ( 'movie' === get_post_type( $post_id ) ) {

    // Unhook this function so it doesn't loop infinitely
    remove_action( 'save_post', __NAMESPACE__ . '\save_post_function_publish' );

    // Get post meta
    $imdb_url = get_post_meta( $post_id, 'imdb_url', true );
    $imdb_year = get_post_meta( $post_id, '_imdb_year', true );
    $imdb_rating = get_post_meta( $post_id, '_imdb_rating', true );
    $imdb_release_date = get_post_meta( $post_id, '_imdb_release_date', true );
    $metascore_rating = get_post_meta( $post_id, '_metascore_rating', true );

    wp_update_post(
      array(
        'ID' => $post_id,
      )
    );

    // Re-hook this function
    add_action( 'save_post', __NAMESPACE__ . '\save_post_function_publish' );
  }

}

function save_post_function( $data, $id ) {
  if ( 'movie' === $data['post_type'] ) {

    // Need to define at least IMDb URL or IMDb ID
    if ( ! metadata_exists( 'movie', $id['ID'], 'imdb_url' ) ) {
      $omdb = new \Rooxie\OMDb( getenv( 'OMDB_API_KEY' ) );
      $imdb_url = get_post_meta( $id['ID'], 'imdb_url', true );
      $imdb_id_match = preg_match_all( '/tt\\d{7,8}/', $imdb_url, $ids );

      if ( empty( $imdb_id_match ) ) {
        return $data;
      }

      $imdb_id = $ids[0][0];
      $movie = $omdb->getByImdbId( $imdb_id );

      // https://github.com/rooxie/omdb-php
      $imdb_title = $movie->getTitle();
      $imdb_year = $movie->getYear();
      $imdb_rating = $movie->getImdbRating();
      $imdb_release_date = $movie->getReleased();
      $metascore_rating = $movie->getMetascore();

      // Save things in post meta
      if ( ! metadata_exists( 'movie', $id['ID'], '_imdb_year' ) ) {
        update_post_meta( $id['ID'], '_imdb_year', $imdb_year );
      }

      if ( ! metadata_exists( 'movie', $id['ID'], '_imdb_rating' ) ) {
        update_post_meta( $id['ID'], '_imdb_rating', $imdb_rating );
      }

      if ( ! metadata_exists( 'movie', $id['ID'], '_imdb_release_date' ) ) {
        update_post_meta( $id['ID'], '_imdb_release_date', $imdb_release_date );
      }

      if ( ! metadata_exists( 'movie', $id['ID'], '_metascore_rating' ) ) {
        update_post_meta( $id['ID'], '_metascore_rating', $metascore_rating );
      }

      // Get genres
      $imdb_genre_originals = array(
        '/,/',
        '/Action/',
        '/Crime/',
        '/Drama/',
        '/Sci-Fi/',
        '/Documentary/',
        '/Comedy/',
        '/Biography/',
        '/Sport/',
        '/Fantasy/',
        '/Mystery/',
        '/Adventure/',
        '/\b(Music)\b/i',
        '/\b(Musical)\b/i',
        '/Thriller/',
        '/Horror/',
        '/Family/',
        '/Animation/',
        '/News/',
        '/Romance/',
        '/War/',
        '/History/',
        '/Short/',
        '/Western/',
      );

      $imdb_genre_finnish = array(
        '',
        'Toiminta',
        'Rikos',
        'Draama',
        'Scifi',
        'Dokumentti',
        'Komedia',
        'Elämänkerta',
        'Urheilu',
        'Fantasia',
        'Mysteeri',
        'Seikkailu',
        'Musiikki',
        'Musikaali',
        'Jännitys',
        'Kauhu',
        'Perhe-elokuva',
        'Animaatio',
        'Uutiset',
        'Romantiikka',
        'Sota',
        'Historia',
        'Lyhytelokuva',
        'Länkkäri',
      );

      // $genre = preg_replace( $imdb_genre_originals, $imdb_genre_finnish, $info['Genre'] );

      // Update the post's title.
      $data['post_title'] = $imdb_title;

      return $data;
    }

  } else {
    return $data;
  }
}
