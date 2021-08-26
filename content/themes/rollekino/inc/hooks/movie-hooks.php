<?php
/**
 * @Author: Roni Laukkarinen
 * @Date:   2021-02-04 18:15:59
 * @Last Modified by:   Roni Laukkarinen
 * @Last Modified time: 2021-08-27 00:05:01
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
  global $imdb_title, $imdb_id, $config, $result, $media_file_path, $media_file_url, $media_file_id;

  if ( 'movie' === get_post_type( $post_id ) ) {

    // If post exists, bail
    if ( post_exists( $imdb_title ) ) {
      return;
    }

    // Unhook this function so it doesn't loop infinitely
    remove_action( 'save_post', __NAMESPACE__ . '\save_post_function_publish' );

    // Get post meta
    $imdb_url = get_post_meta( $post_id, 'imdb_url', true );
    $imdb_year = get_post_meta( $post_id, '_imdb_year', true );
    $imdb_rating = get_post_meta( $post_id, '_imdb_rating', true );
    $imdb_release_date = get_post_meta( $post_id, '_imdb_release_date', true );
    $metascore_rating = get_post_meta( $post_id, '_metascore_rating', true );

    // Construct poster URL
    $tmdb_poster_url = $config['images']['base_url'] . $config['images']['poster_sizes'][3] . $result['movie_results'][0]['poster_path'];
    $media_file_path = wp_upload_dir()['path'] . $result['movie_results'][0]['poster_path'];
    $media_file_url = wp_upload_dir()['url'] . $result['movie_results'][0]['poster_path'];

    if ( file_exists( $media_file_path ) ) {
      $media_file_id = attachment_url_to_postid( $media_file_url );
    }

    // Upload image if not existing
    if ( ! file_exists( $media_file_path ) ) {
      $media_description = 'Leffajuliste elokuvalle ' . $imdb_title;
      $media_sideload_image = media_sideload_image( $tmdb_poster_url, $post_id, $media_description, 'id' );
      set_post_thumbnail( $post_id, $media_sideload_image );
    }

    // Set featured image
    if ( file_exists( $media_file_path ) ) {
      set_post_thumbnail( $post_id, $media_file_id );
    }

    // Update post
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
  global $imdb_title, $imdb_id, $config, $result, $media_file_path, $media_file_url, $media_file_id;
  $post_id = $id['ID'];

  if ( 'movie' === $data['post_type'] ) {

    // Need to define at least IMDb URL or IMDb ID
    if ( ! metadata_exists( 'movie', $post_id, 'imdb_url' ) ) {
      $omdb = new \Rooxie\OMDb( getenv( 'OMDB_API_KEY' ) );
      $imdb_url = get_post_meta( $post_id, 'imdb_url', true );
      $imdb_id_match = preg_match_all( '/tt\\d{7,8}/', $imdb_url, $ids );

      if ( empty( $imdb_id_match ) ) {
        return $data;
      }

      $imdb_id = $ids[0][0];
      $movie = $omdb->getByImdbId( $imdb_id );

      // https://github.com/rooxie/omdb-php
      $imdb_title = $movie->getTitle();

      // If post exists, bail
      if ( post_exists( $imdb_title ) ) {
        return;
      }

      $imdb_year = $movie->getYear();
      $imdb_rating = $movie->getImdbRating();
      $imdb_release_date = $movie->getReleased();
      $imdb_genres = $movie->getGenre();
      $metascore_rating = $movie->getMetascore();

      // Save things in post meta
      if ( ! metadata_exists( 'movie', $post_id, '_imdb_year' ) ) {
        update_post_meta( $id['ID'], '_imdb_year', $imdb_year );
      }

      if ( ! metadata_exists( 'movie', $post_id, '_imdb_rating' ) ) {
        update_post_meta( $id['ID'], '_imdb_rating', $imdb_rating );
      }

      if ( ! metadata_exists( 'movie', $post_id, '_imdb_release_date' ) ) {
        update_post_meta( $id['ID'], '_imdb_release_date', $imdb_release_date );
      }

      if ( ! metadata_exists( 'movie', $post_id, '_metascore_rating' ) ) {
        update_post_meta( $id['ID'], '_metascore_rating', $metascore_rating );
      }

      // Get genres
      $imdb_genres_originals = array(
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

      $imdb_genres_finnish = array(
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

      // Get Finnish genres
      $genres_finnish = preg_replace( $imdb_genres_originals, $imdb_genres_finnish, $imdb_genres );

      // Set genres
      wp_set_object_terms( $post_id, $genres_finnish, 'genre' );

      // Old school way of using TMDb API
      $ca = curl_init(); // phpcs:ignore
      curl_setopt( $ca, CURLOPT_URL, 'http://api.themoviedb.org/3/configuration?api_key=' . getenv( 'TMDB_API_KEY' ) ); // phpcs:ignore
      curl_setopt( $ca, CURLOPT_RETURNTRANSFER, true ); // phpcs:ignore
      curl_setopt( $ca, CURLOPT_HEADER, false ); // phpcs:ignore
      curl_setopt( $ca, CURLOPT_HTTPHEADER, array( 'Accept: application/json' ) ); // phpcs:ignore
      $response = curl_exec( $ca ); // phpcs:ignore
      curl_close( $ca ); // phpcs:ignore
      $config = json_decode( $response, true );

      $ch = curl_init(); // phpcs:ignore
      curl_setopt( $ch, CURLOPT_URL, 'http://api.themoviedb.org/3/find/' . $imdb_id . '?api_key=' . getenv( 'TMDB_API_KEY' ) . '&external_source=imdb_id' ); // phpcs:ignore
      curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true ); // phpcs:ignore
      curl_setopt( $ch, CURLOPT_HEADER, FALSE ); // phpcs:ignore
      curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Accept: application/json' ) ); // phpcs:ignore
      $response = curl_exec( $ch ); // phpcs:ignore
      curl_close( $ch ); // phpcs:ignore
      $result = json_decode( $response, true );

      // Construct poster URL
      $tmdb_poster_url = $config['images']['base_url'] . $config['images']['poster_sizes'][3] . $result['movie_results'][0]['poster_path'];
      $media_file_path = wp_upload_dir()['path'] . $result['movie_results'][0]['poster_path'];
      $media_file_url = wp_upload_dir()['url'] . $result['movie_results'][0]['poster_path'];

      if ( file_exists( $media_file_path ) ) {
        $media_file_id = attachment_url_to_postid( $media_file_url );
      }

      // Upload image if not existing
      if ( ! file_exists( $media_file_path ) ) {
        $media_description = 'Leffajuliste elokuvalle ' . $imdb_title;
        $media_sideload_image = media_sideload_image( $tmdb_poster_url, $post_id, $media_description, 'id' );
        set_post_thumbnail( $post_id, $media_sideload_image );
      }

      // Set featured image
      if ( file_exists( $media_file_path ) ) {
        set_post_thumbnail( $post_id, $media_file_id );
      }

      // Update the post's title.
      $data['post_title'] = $imdb_title;

      return $data;
    }

  } else {
    return $data;
  }
}
