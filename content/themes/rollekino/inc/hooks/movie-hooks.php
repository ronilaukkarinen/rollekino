<?php
/**
 * @Author: Roni Laukkarinen
 * @Date:   2021-02-04 18:15:59
 * @Last Modified by:   Roni Laukkarinen
 * @Last Modified time: 2022-05-22 13:58:51
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
  // If the field 'skip_omdb' is ticked, don't do anything
  if ( get_field( 'skip_omdb', $post_id ) ) {
    return;
  }

  if ( 'post' !== get_post_type( $post_id ) || 'page' !== get_post_type( $post_id ) || 'movie' === get_post_type( $post_id ) ) {

    global $imdb_title, $imdb_id, $config, $result, $media_file_poster_path, $media_file_poster_url, $media_file_poster_id, $media_file_backdrop_path, $media_file_backdrop_url, $media_file_backdrop_id;

    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    // Unhook this function so it doesn't loop infinitely
    remove_action( 'save_post', __NAMESPACE__ . '\save_post_function_publish' );

    // Remove revisions to prevent double save
    remove_action( 'pre_post_update', __NAMESPACE__ . '\wp_save_post_revision' );

    // Get post meta
    $imdb_url = get_post_meta( $post_id, 'imdb_url', true );
    $imdb_year = get_post_meta( $post_id, '_imdb_year', true );
    $imdb_rating = get_post_meta( $post_id, '_imdb_rating', true );
    $imdb_release_date = get_post_meta( $post_id, '_imdb_release_date', true );
    $metascore_rating = get_post_meta( $post_id, '_metascore_rating', true );

    if ( ! metadata_exists( 'movie', $post_id, '_imdb_rating' ) ) {
      update_post_meta( $post_id, '_imdb_rating', $imdb_rating );
    }

    // Construct poster URL
    $tmdb_poster_url = $config['images']['base_url'] . $config['images']['poster_sizes'][3] . $result['movie_results'][0]['poster_path'];
    $media_file_poster_path = wp_upload_dir()['path'] . $result['movie_results'][0]['poster_path'];
    $media_file_poster_url = wp_upload_dir()['url'] . $result['movie_results'][0]['poster_path'];
    $media_file_poster_id = attachment_url_to_postid( $media_file_poster_url );

    // If not existing, do something
    if ( empty( $media_file_poster_id ) ) {

      if ( ! get_field( 'poster', $post_id ) ) {
        // First delete the file that is not linked to media library if it exists
        if ( file_exists( $media_file_poster_path ) ) {
          unlink( $media_file_poster_path );
        }

        // Then upload it
        $media_poster_description = 'Leffajuliste elokuvalle ' . $imdb_title;
        $media_sideload_image_poster = media_sideload_image( $tmdb_poster_url, $post_id, $media_poster_description, 'id' );

        // Set uploaded image to taxonomy image field
        update_field( 'poster', $media_sideload_image_poster, $post_id );
      }
    }

    // Construct backdrop URL if we have valid results
    if ( ! empty( $result['movie_results'][0]['backdrop_path'] ) ) {
      $tmdb_backdrop_url = $config['images']['base_url'] . $config['images']['backdrop_sizes'][2] . $result['movie_results'][0]['backdrop_path'];
      $media_file_backdrop_path = wp_upload_dir()['path'] . $result['movie_results'][0]['backdrop_path'];
      $media_file_backdrop_url = wp_upload_dir()['url'] . $result['movie_results'][0]['backdrop_path'];
      $media_file_backdrop_id = attachment_url_to_postid( $media_file_backdrop_url );
    }

    // Set featured image
    if ( file_exists( $media_file_backdrop_path ) ) {
      set_post_thumbnail( $post_id, $media_file_backdrop_id );
    }

    // If not existing, do something
    if ( empty( $media_file_backdrop_id ) ) {

      // If for some obscure reason file is found from uploads dir but not on media library
      if ( ! has_post_thumbnail( $post_id ) ) {

        // First delete the file that is not linked to media library if it exists
        if ( file_exists( $media_file_backdrop_path ) ) {
          unlink( $media_file_backdrop_path );
        }

        // Then reupload it if not already there
        $media_backdrop_description = 'Tausta elokuvalle ' . $imdb_title;

        if ( ! file_exists( $media_file_backdrop_path ) ) {
          $media_sideload_image_backdrop = media_sideload_image( $tmdb_backdrop_url, $post_id, $media_backdrop_description, 'id' );
        }

        // Set uploaded image as acf field image
        set_post_thumbnail( $post_id, $media_sideload_image_backdrop );
      }
    }

    // Update post
    wp_update_post(
      array(
        'ID' => $post_id,
      ),
      $wp_error = false,
      $fire_after_hooks = true
    );

    // Re-hook functions
    add_action( 'save_post', __NAMESPACE__ . '\save_post_function_publish' );
  }
}

function save_post_function( $data, $id ) {
  global $imdb_title, $imdb_id, $config, $result, $media_file_poster_path, $media_file_poster_url, $media_file_poster_id, $media_file_backdrop_path, $media_file_backdrop_url, $media_file_backdrop_id;
  $post_id = $id['ID'];

  // If the field 'skip_omdb' is ticked, don't do anything
  if ( get_field( 'skip_omdb', $post_id ) ) {
    return;
  }

  if ( 'post' !== get_post_type( $post_id ) || 'page' !== get_post_type( $post_id ) || 'movie' === $data['post_type'] ) {

    // Need to define at least IMDb URL or IMDb ID
    if ( ! metadata_exists( 'movie', $post_id, 'imdb_url' ) ) {
      $omdb = new \Rooxie\OMDb( getenv( 'OMDB_API_KEY' ) );
      $imdb_url = get_post_meta( $post_id, 'imdb_url', true );
      $imdb_id_match = preg_match_all( '/tt\\d{7,8}/', $imdb_url, $ids );

      if ( empty( $imdb_id_match ) ) {
        return $data;
      }

      $imdb_id = $ids[0][0];

      if ( ! isset( $imdb_id ) ) {
        return;
      }

      $movie = $omdb->getByImdbId( $imdb_id );

      // https://github.com/rooxie/omdb-php
      $imdb_title = $movie->getTitle();

      $imdb_year = $movie->getYear();
      $imdb_rating = $movie->getImdbRating();
      $imdb_release_date = $movie->getReleased();
      $imdb_genres = $movie->getGenre();
      $metascore_rating = $movie->getMetascore();
      $imdb_runtime_total_minutes = $movie->getRuntime();

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

      if ( ! metadata_exists( 'movie', $post_id, '_idmb_runtime' ) ) {
        update_post_meta( $id['ID'], '_idmb_runtime', $imdb_runtime_total_minutes );
      }

      // If using legacy own rating field (because of WP All Import doesn't support rating field) update real field based on that legacy field value
      $rating_legacy = get_post_meta( $post_id, 'rating_legacy', true );
      if ( ! empty( $rating_legacy ) ) {
        update_field( 'rating', $rating_legacy, $post_id );
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
        '/Reality-TV/',
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
        'Tosi-TV',
      );

      // Get Finnish genres
      $genres_finnish = preg_replace( $imdb_genres_originals, $imdb_genres_finnish, $imdb_genres );

      // Set genres
      wp_set_object_terms( $post_id, $genres_finnish, 'genre' );

      // Old school way of using TMDb API
      // Get poster and backdrop sizes
      $ca = curl_init(); // phpcs:ignore
      curl_setopt( $ca, CURLOPT_URL, 'http://api.themoviedb.org/3/configuration?api_key=' . getenv( 'TMDB_API_KEY' ) ); // phpcs:ignore
      curl_setopt( $ca, CURLOPT_RETURNTRANSFER, true ); // phpcs:ignore
      curl_setopt( $ca, CURLOPT_HEADER, false ); // phpcs:ignore
      curl_setopt( $ca, CURLOPT_HTTPHEADER, array( 'Accept: application/json' ) ); // phpcs:ignore
      $response = curl_exec( $ca ); // phpcs:ignore
      curl_close( $ca ); // phpcs:ignore
      $config = json_decode( $response, true );

      // Get poster and backdrop images
      $ch = curl_init(); // phpcs:ignore
      curl_setopt( $ch, CURLOPT_URL, 'http://api.themoviedb.org/3/find/' . $imdb_id . '?api_key=' . getenv( 'TMDB_API_KEY' ) . '&external_source=imdb_id' ); // phpcs:ignore
      curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true ); // phpcs:ignore
      curl_setopt( $ch, CURLOPT_HEADER, FALSE ); // phpcs:ignore
      curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Accept: application/json' ) ); // phpcs:ignore
      $response = curl_exec( $ch ); // phpcs:ignore
      curl_close( $ch ); // phpcs:ignore
      $result = json_decode( $response, true );

      // Get movie ID
      $tmdb_id = $result['movie_results'][0]['id'];

      // Get movie trailer
      // @link https://www.themoviedb.org/talk/5451ec02c3a3680245005e3c
      $trailer = curl_init(); // phpcs:ignore
      curl_setopt( $trailer, CURLOPT_URL, 'https://api.themoviedb.org/3/movie/' . $tmdb_id . '/videos?api_key=' . getenv( 'TMDB_API_KEY' ) ); // phpcs:ignore
      curl_setopt( $trailer, CURLOPT_RETURNTRANSFER, true ); // phpcs:ignore
      curl_setopt( $trailer, CURLOPT_HEADER, FALSE ); // phpcs:ignore
      curl_setopt( $trailer, CURLOPT_HTTPHEADER, array( 'Accept: application/json' ) ); // phpcs:ignore
      $response_trailer = curl_exec( $trailer ); // phpcs:ignore
      curl_close( $trailer ); // phpcs:ignore
      $result_trailer = json_decode( $response_trailer, true );

      // Initialize trailers array
      $trailers_array = [];

      // Loop through trailers to get the official trailer
      foreach ( $result_trailer['results'] as $trailers ) {
        if ( 'Trailer' === $trailers['type'] && 'YouTube' === $trailers['site'] ) {
          // Store directors to their own array to be used outside the loop
          $trailers_array[] = $trailers;
        }
      }

      // Only proceed if we have trailers
      if ( ! empty( $trailers_array ) ) {
        // Getting array keys amount, the number of trailers
        $trailers_amount = array_keys( $trailers_array );

        // Checking if there's more than one trailer
        if ( count( $trailers_amount ) > 1 ) {
          // There's more than one trailer, selecting the correct one (last one in iteration)
          $last_trailer_key = $trailers_amount[ count( $trailers_amount ) - 1 ];
          $selected_trailer_array = $trailers_array[ $last_trailer_key ];
        } else {
          // There's only one trailer, selecting the only one
          $selected_trailer_array = $trailers_array;
        }
      }

      // Get the one official trailer
      if ( ! empty( $selected_trailer_array ) ) {
        $official_trailer = array_splice( $trailers_array, 0, 1 );
        $trailer_youtube_key = $official_trailer[0]['key'];

        if ( ! metadata_exists( 'movie', $post_id, '_trailer_youtube_key' ) ) {
          update_post_meta( $id['ID'], '_trailer_youtube_key', $trailer_youtube_key );
        }
      }

      // Get movie cast
      // @link https://developers.themoviedb.org/3/movies/get-movie-credits
      $cast = curl_init(); // phpcs:ignore
      curl_setopt( $cast, CURLOPT_URL, 'https://api.themoviedb.org/3/movie/' . $tmdb_id . '/credits?api_key=' . getenv( 'TMDB_API_KEY' ) . '&language=en-US' ); // phpcs:ignore
      curl_setopt( $cast, CURLOPT_RETURNTRANSFER, true ); // phpcs:ignore
      curl_setopt( $cast, CURLOPT_HEADER, FALSE ); // phpcs:ignore
      curl_setopt( $cast, CURLOPT_HTTPHEADER, array( 'Accept: application/json' ) ); // phpcs:ignore
      $response_cast = curl_exec( $cast ); // phpcs:ignore
      curl_close( $cast ); // phpcs:ignore
      $result_cast = json_decode( $response_cast, true );
      $cast_array = $result_cast['cast'];
      $crew_array = $result_cast['crew'];

      if ( ! empty( $cast_array ) || ! empty( $crew_array ) ) {
      // Cast image URL base
      // @link https://developers.themoviedb.org/3/getting-started/images
      $cast_image_url_base = 'https://image.tmdb.org/t/p/w400/';

      // Loop through crew to get directors and writers
      foreach ( $crew_array as $crew ) {

        if ( 'Director' === $crew['job'] ) {
          // Store directors to their own array to be used outside the loop
          $directors_array[] = $crew;
        }

        if ( 'Writing' === $crew['department'] ) {
          // Store writers to their own array to be used outside the loop
          $writers_array[] = $crew;
        }

      }

      // Get writer names
      // Check for PHP 8.3, Argument #1 ($array) must be of type array, null given
      if ( ! empty( $writers_array ) ) {
        $writer_names = array_column( $writers_array, 'name' );

       // Get writer profile photo path
        $writer_profile_photo_path = array_splice( array_column( $writers_array, 'profile_path' ), 0, 5 );

        // Set writers in taxonomies
        wp_set_object_terms( $post_id, $writer_names, 'writer' );

        // Make sure $writer_profile_photo_path and $writer_names have same amount of elements in array
        if ( count( $writer_profile_photo_path ) > count( $writer_names ) ) {
          $writer_profile_photo_path = array_splice( $writer_profile_photo_path, 0, count( $writer_names ) );
        }

        if ( count( $writer_names ) > count( $writer_profile_photo_path ) ) {
          $writer_names = array_splice( $writer_names, 0, count( $writer_profile_photo_path ) );
        }

        // Merge writer names and photo paths together
        $merged_writer_profile_arrays = array_combine( $writer_names, $writer_profile_photo_path );

        // Loop through writer names and get their taxonomy IDs to update their meta data like poster
        foreach ( $merged_writer_profile_arrays as $merged_writer_name => $merged_writer_profile_path ) {

          $writer_taxonomy_id = get_term_by( 'name', $merged_writer_name, 'writer' )->term_id;

          // Writer profile photo TMDb URL
          $tmdb_writer_profile_photo_url = $cast_image_url_base . $merged_writer_profile_path;

          // Writer profile photo local path
          $media_file_writer_profile_photo_path = wp_upload_dir()['path'] . $merged_writer_profile_path;

          // Writer profile photo local URL
          $media_file_writer_profile_photo_url = wp_upload_dir()['url'] . $merged_writer_profile_path;

          // Upload image if not existing
          if ( ! file_exists( $media_file_writer_profile_photo_path ) ) {
            $media_sideload_image_writer_profile_photo = media_sideload_image( $tmdb_writer_profile_photo_url, 0, $merged_writer_name, 'id' );
          }

          // Get profile photo ID from media library based on URL
          if ( file_exists( $media_file_writer_profile_photo_path ) ) {
            $media_file_writer_profile_photo_id = attachment_url_to_postid( $media_file_writer_profile_photo_url );

            // Set uploaded image to taxonomy image field
            update_field( 'avatar', $media_file_writer_profile_photo_id, 'writer_' . $writer_taxonomy_id );
          }
        }
      }

      // Get director names
      $director_names = array_column( $directors_array, 'name' );

      // Get director profile photo path
      $director_profile_photo_path = array_splice( array_column( $directors_array, 'profile_path' ), 0, 6 );

      // Set directors in taxonomies
      wp_set_object_terms( $post_id, $director_names, 'director' );

      // Merge director names and photo paths together
      $merged_director_profile_arrays = array_combine( $director_names, $director_profile_photo_path );

      // Loop through director names and get their taxonomy IDs to update their meta data like poster
      foreach ( $merged_director_profile_arrays as $merged_director_name => $merged_director_profile_path ) {

        $director_taxonomy_id = get_term_by( 'name', $merged_director_name, 'director' )->term_id;

        // Director profile photo TMDb URL
        $tmdb_director_profile_photo_url = $cast_image_url_base . $merged_director_profile_path;

        // Director profile photo local path
        $media_file_director_profile_photo_path = wp_upload_dir()['path'] . $merged_director_profile_path;

        // Director profile photo local URL
        $media_file_director_profile_photo_url = wp_upload_dir()['url'] . $merged_director_profile_path;

        // Upload image if not existing
        if ( ! file_exists( $media_file_director_profile_photo_path ) ) {
          $media_sideload_image_director_profile_photo = media_sideload_image( $tmdb_director_profile_photo_url, 0, $merged_director_name, 'id' );
        }

        // Get profile photo ID from media library based on URL
        if ( file_exists( $media_file_director_profile_photo_path ) ) {
          $media_file_director_profile_photo_id = attachment_url_to_postid( $media_file_director_profile_photo_url );

          // Set uploaded image to taxonomy image field
          update_field( 'avatar', $media_file_director_profile_photo_id, 'director_' . $director_taxonomy_id );
        }
      }

      // Get actor names
      $actor_names = array_column( $cast_array, 'name' );
      $actor_names_five = array_splice( $actor_names, 0, 6 );

      // Get actor profile photo path
      $actor_profile_photo_path = array_splice( array_column( $cast_array, 'profile_path' ), 0, 6 );

      // Set cast in taxonomies
      wp_set_object_terms( $post_id, $actor_names_five, 'actor' );

      // Merge names and photo paths together
      $merged_profile_arrays = array_combine( $actor_names_five, $actor_profile_photo_path );

      // Loop through actor names and get their taxonomy IDs to update their meta data like poster
      foreach ( $merged_profile_arrays as $merged_actor_name => $merged_actor_profile_path ) {

        $actor_taxonomy_id = get_term_by( 'name', $merged_actor_name, 'actor' )->term_id;

        // Actor profile photo TMDb URL
        $tmdb_actor_profile_photo_url = $cast_image_url_base . $merged_actor_profile_path;

        // Actor profile photo local path
        $media_file_actor_profile_photo_path = wp_upload_dir()['path'] . $merged_actor_profile_path;

        // Actor profile photo local URL
        $media_file_actor_profile_photo_url = wp_upload_dir()['url'] . $merged_actor_profile_path;

        // Upload image if not existing
        if ( ! file_exists( $media_file_actor_profile_photo_path ) ) {
          $media_sideload_image_actor_profile_photo = media_sideload_image( $tmdb_actor_profile_photo_url, 0, $merged_actor_name, 'id' );
        }

        // Get profile photo ID from media library based on URL
        if ( file_exists( $media_file_actor_profile_photo_path ) ) {
          $media_file_actor_profile_photo_id = attachment_url_to_postid( $media_file_actor_profile_photo_url );

          // Set uploaded image to taxonomy image field
          update_field( 'avatar', $media_file_actor_profile_photo_id, 'actor_' . $actor_taxonomy_id );
        }
      }
    } // Cast and crew ends

    // Backdrop & Poster uploads are here
    // Construct poster URL
    $tmdb_poster_url = $config['images']['base_url'] . $config['images']['poster_sizes'][3] . $result['movie_results'][0]['poster_path'];
    $media_file_poster_path = wp_upload_dir()['path'] . $result['movie_results'][0]['poster_path'];
    $media_file_poster_url = wp_upload_dir()['url'] . $result['movie_results'][0]['poster_path'];
    $media_file_poster_id = attachment_url_to_postid( $media_file_poster_url );

    // If not existing, do something
    if ( empty( $media_file_poster_id ) ) {

      if ( ! get_field( 'poster', $post_id ) ) {
        // First delete the file that is not linked to media library if it exists
        if ( file_exists( $media_file_poster_path ) ) {
          unlink( $media_file_poster_path );
        }

        // Then upload it
        $media_poster_description = 'Leffajuliste elokuvalle ' . $imdb_title;
        $media_sideload_image_poster = media_sideload_image( $tmdb_poster_url, $post_id, $media_poster_description, 'id' );

        // Set uploaded image to taxonomy image field
        update_field( 'poster', $media_sideload_image_poster, $post_id );
      }
    }

    // Construct backdrop URL if we have valid results
    if ( ! empty( $result['movie_results'][0]['backdrop_path'] ) ) {
      $tmdb_backdrop_url = $config['images']['base_url'] . $config['images']['backdrop_sizes'][2] . $result['movie_results'][0]['backdrop_path'];
      $media_file_backdrop_path = wp_upload_dir()['path'] . $result['movie_results'][0]['backdrop_path'];
      $media_file_backdrop_url = wp_upload_dir()['url'] . $result['movie_results'][0]['backdrop_path'];
      $media_file_backdrop_id = attachment_url_to_postid( $media_file_backdrop_url );
    }

    // Set featured image
    if ( file_exists( $media_file_backdrop_path ) ) {
      set_post_thumbnail( $post_id, $media_file_backdrop_id );
    }

    // If not existing, do something
    if ( empty( $media_file_backdrop_id ) ) {

      // If for some obscure reason file is found from uploads dir but not on media library
      if ( ! has_post_thumbnail( $post_id ) ) {

        // First delete the file that is not linked to media library if it exists
        if ( file_exists( $media_file_backdrop_path ) ) {
          unlink( $media_file_backdrop_path );
        }

        // Then reupload it if not already there
        $media_backdrop_description = 'Tausta elokuvalle ' . $imdb_title;

        if ( ! file_exists( $media_file_backdrop_path ) ) {
          $media_sideload_image_backdrop = media_sideload_image( $tmdb_backdrop_url, $post_id, $media_backdrop_description, 'id' );
        }

        // Set uploaded image as acf field image
        set_post_thumbnail( $post_id, $media_sideload_image_backdrop );
      }
    }

    // Update the post's title.
    $data['post_title'] = $imdb_title;

    // Update the post's slug.
    $data['post_name'] = sanitize_title( $imdb_title );

    return $data;
  }

  } else {
    return $data;
  }
}

/**
 * Add custom HTML to Featured Image box
 */
add_action( 'add_meta_boxes', __NAMESPACE__ . '\add_featured_image', 10, 2 );
function add_featured_image( $post_type, $post ) {
  $post_types = get_post_types();
  $post_id = $post->ID;

  if ( 'post' === get_post_type( $post_id ) )
  return;

  // Need to define at least IMDb URL or IMDb ID
  if ( ! metadata_exists( 'movie', $post_id, 'imdb_url' ) ) {
    foreach ( $post_types as $p ) {

      // Remove original featured image metabox
      remove_meta_box( 'postimagediv', $p, 'side' );

      // Add our customized metabox
      add_meta_box( 'postimagediv', 'Featured Image', function( $post ) {

        $post_id = $post->ID;
        $thumbnail_id = get_post_meta( $post->ID, '_thumbnail_id', true );

        $omdb = new \Rooxie\OMDb( getenv( 'OMDB_API_KEY' ) );
        $imdb_url = get_post_meta( $post_id, 'imdb_url', true );
        $imdb_id_match = preg_match_all( '/tt\\d{7,8}/', $imdb_url, $ids );

        // Get IMDb ID
        $imdb_id = $ids[0][0];

        // Connect to API
        $ch = curl_init(); // phpcs:ignore
        curl_setopt( $ch, CURLOPT_URL, 'http://api.themoviedb.org/3/find/' . $imdb_id . '?api_key=' . getenv( 'TMDB_API_KEY' ) . '&external_source=imdb_id' ); // phpcs:ignore
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true ); // phpcs:ignore
        curl_setopt( $ch, CURLOPT_HEADER, FALSE ); // phpcs:ignore
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Accept: application/json' ) ); // phpcs:ignore
        $response = curl_exec( $ch ); // phpcs:ignore
        curl_close( $ch ); // phpcs:ignore
        $result = json_decode( $response, true );

        // Get movie ID
        $tmdb_id = $result['movie_results'][0]['id'];

        // Your HTML Goes here
        echo '<p class="description"><strong><a href="https://www.themoviedb.org/movie/' . $tmdb_id . '/images/backdrops">Jos kuva ei miellytä, täällä lisää &rarr;</a></strong></p>'; // phpcs:ignore

        // Your Image is printed here
        echo _wp_post_thumbnail_html( $thumbnail_id, $post->ID ); // phpcs:ignore
      }, $p, 'side', 'low');
    }
  }
}

/**
 * Custom admin CSS: Hiding legacy ACF field used only for WP All Import
 */
add_action( 'admin_head', __NAMESPACE__ . '\custom_admin_head' );
function custom_admin_head() {
  echo '<style type="text/css">
  .acf-field-612a7a4cfc2eb {
    display: none !important;
  }
  </style>';
}

/**
 * Show rating as stars
 */

function rating_stars() {
  $rating = get_post_meta( get_the_ID(), 'rating', true ) / 2; ?>

  <span class="star-rating">
    <?php
    if ( 5 === $rating ) {
      include get_theme_file_path( '/svg/star.svg' );
      include get_theme_file_path( '/svg/star.svg' );
      include get_theme_file_path( '/svg/star.svg' );
      include get_theme_file_path( '/svg/star.svg' );
      include get_theme_file_path( '/svg/star.svg' );
      echo '<span class="star-rating-text">Voittajaehdokas</span>';
    } elseif ( 4.5 === $rating ) {
      include get_theme_file_path( '/svg/star.svg' );
      include get_theme_file_path( '/svg/star.svg' );
      include get_theme_file_path( '/svg/star.svg' );
      include get_theme_file_path( '/svg/star.svg' );
      echo '<span class="half">';
      include get_theme_file_path( '/svg/star-half.svg' );
      include get_theme_file_path( '/svg/star-bg.svg' );
      echo '</span>';
      echo '<span class="star-rating-text">Ällistyttävä</span>';
    } elseif ( 4 === $rating ) {
      include get_theme_file_path( '/svg/star.svg' );
      include get_theme_file_path( '/svg/star.svg' );
      include get_theme_file_path( '/svg/star.svg' );
      include get_theme_file_path( '/svg/star.svg' );
      include get_theme_file_path( '/svg/star-empty.svg' );
      echo '<span class="star-rating-text">Loistava</span>';
    } elseif ( 3.5 === $rating ) {
      include get_theme_file_path( '/svg/star.svg' );
      include get_theme_file_path( '/svg/star.svg' );
      include get_theme_file_path( '/svg/star.svg' );
      echo '<span class="half">';
      include get_theme_file_path( '/svg/star-half.svg' );
      include get_theme_file_path( '/svg/star-bg.svg' );
      echo '</span>';
      include get_theme_file_path( '/svg/star-empty.svg' );
      echo '<span class="star-rating-text">Erittäin hyvä</span>';
    } elseif ( 3 === $rating ) {
      include get_theme_file_path( '/svg/star.svg' );
      include get_theme_file_path( '/svg/star.svg' );
      include get_theme_file_path( '/svg/star.svg' );
      include get_theme_file_path( '/svg/star-empty.svg' );
      include get_theme_file_path( '/svg/star-empty.svg' );
      echo '<span class="star-rating-text">Hyvä</span>';
    } elseif ( 2.5 === $rating ) {
      include get_theme_file_path( '/svg/star.svg' );
      include get_theme_file_path( '/svg/star.svg' );
      echo '<span class="half">';
      include get_theme_file_path( '/svg/star-half.svg' );
      include get_theme_file_path( '/svg/star-bg.svg' );
      echo '</span>';
      include get_theme_file_path( '/svg/star-empty.svg' );
      include get_theme_file_path( '/svg/star-empty.svg' );
      echo '<span class="star-rating-text">Kelvollinen</span>';
    } elseif ( 2 === $rating ) {
      include get_theme_file_path( '/svg/star.svg' );
      include get_theme_file_path( '/svg/star.svg' );
      include get_theme_file_path( '/svg/star-empty.svg' );
      include get_theme_file_path( '/svg/star-empty.svg' );
      include get_theme_file_path( '/svg/star-empty.svg' );
      echo '<span class="star-rating-text">Siedettävä</span>';
    } elseif ( 1.5 === $rating ) {
      include get_theme_file_path( '/svg/star.svg' );
      echo '<span class="half">';
      include get_theme_file_path( '/svg/star-half.svg' );
      include get_theme_file_path( '/svg/star-bg.svg' );
      echo '</span>';
      include get_theme_file_path( '/svg/star-empty.svg' );
      include get_theme_file_path( '/svg/star-empty.svg' );
      include get_theme_file_path( '/svg/star-empty.svg' );
      echo '<span class="star-rating-text">Tuskin siedettävä</span>';
    } elseif ( 1 === $rating ) {
      include get_theme_file_path( '/svg/star.svg' );
      include get_theme_file_path( '/svg/star-empty.svg' );
      include get_theme_file_path( '/svg/star-empty.svg' );
      include get_theme_file_path( '/svg/star-empty.svg' );
      include get_theme_file_path( '/svg/star-empty.svg' );
      echo '<span class="star-rating-text">Huono</span>';
    } elseif ( 0.5 === $rating ) {
      echo '<span class="half">';
      include get_theme_file_path( '/svg/star-half.svg' );
      include get_theme_file_path( '/svg/star-bg.svg' );
      echo '</span>';
      include get_theme_file_path( '/svg/star-empty.svg' );
      include get_theme_file_path( '/svg/star-empty.svg' );
      include get_theme_file_path( '/svg/star-empty.svg' );
      include get_theme_file_path( '/svg/star-empty.svg' );
      echo '<span class="star-rating-text">Kauhea</span>';
    } ?>
  </span>

  <?php
}

/**
 * Show rating as stars
 */
function rating_stars_by_id( $post_id ) {
  if ( null === $post_id ) {
    $post_id = get_the_ID();
  }

  $rating = get_post_meta( $post_id, 'rating', true ) / 2; ?>

  <span class="star-rating">
    <?php
    if ( 5 === $rating ) {
      include get_theme_file_path( '/svg/star.svg' );
      include get_theme_file_path( '/svg/star.svg' );
      include get_theme_file_path( '/svg/star.svg' );
      include get_theme_file_path( '/svg/star.svg' );
      include get_theme_file_path( '/svg/star.svg' );
    } elseif ( 4.5 === $rating ) {
      include get_theme_file_path( '/svg/star.svg' );
      include get_theme_file_path( '/svg/star.svg' );
      include get_theme_file_path( '/svg/star.svg' );
      include get_theme_file_path( '/svg/star.svg' );
      echo '<span class="half">';
      include get_theme_file_path( '/svg/star-half.svg' );
      include get_theme_file_path( '/svg/star-bg.svg' );
      echo '</span>';
    } elseif ( 4 === $rating ) {
      include get_theme_file_path( '/svg/star.svg' );
      include get_theme_file_path( '/svg/star.svg' );
      include get_theme_file_path( '/svg/star.svg' );
      include get_theme_file_path( '/svg/star.svg' );
      include get_theme_file_path( '/svg/star-empty.svg' );
    } elseif ( 3.5 === $rating ) {
      include get_theme_file_path( '/svg/star.svg' );
      include get_theme_file_path( '/svg/star.svg' );
      include get_theme_file_path( '/svg/star.svg' );
      echo '<span class="half">';
      include get_theme_file_path( '/svg/star-half.svg' );
      include get_theme_file_path( '/svg/star-bg.svg' );
      echo '</span>';
      include get_theme_file_path( '/svg/star-empty.svg' );
    } elseif ( 3 === $rating ) {
      include get_theme_file_path( '/svg/star.svg' );
      include get_theme_file_path( '/svg/star.svg' );
      include get_theme_file_path( '/svg/star.svg' );
      include get_theme_file_path( '/svg/star-empty.svg' );
      include get_theme_file_path( '/svg/star-empty.svg' );
    } elseif ( 2.5 === $rating ) {
      include get_theme_file_path( '/svg/star.svg' );
      include get_theme_file_path( '/svg/star.svg' );
      echo '<span class="half">';
      include get_theme_file_path( '/svg/star-half.svg' );
      include get_theme_file_path( '/svg/star-bg.svg' );
      echo '</span>';
      include get_theme_file_path( '/svg/star-empty.svg' );
      include get_theme_file_path( '/svg/star-empty.svg' );
    } elseif ( 2 === $rating ) {
      include get_theme_file_path( '/svg/star.svg' );
      include get_theme_file_path( '/svg/star.svg' );
      include get_theme_file_path( '/svg/star-empty.svg' );
      include get_theme_file_path( '/svg/star-empty.svg' );
      include get_theme_file_path( '/svg/star-empty.svg' );
    } elseif ( 1.5 === $rating ) {
      include get_theme_file_path( '/svg/star.svg' );
      echo '<span class="half">';
      include get_theme_file_path( '/svg/star-half.svg' );
      include get_theme_file_path( '/svg/star-bg.svg' );
      echo '</span>';
      include get_theme_file_path( '/svg/star-empty.svg' );
      include get_theme_file_path( '/svg/star-empty.svg' );
      include get_theme_file_path( '/svg/star-empty.svg' );
    } elseif ( 1 === $rating ) {
      include get_theme_file_path( '/svg/star.svg' );
      include get_theme_file_path( '/svg/star-empty.svg' );
      include get_theme_file_path( '/svg/star-empty.svg' );
      include get_theme_file_path( '/svg/star-empty.svg' );
      include get_theme_file_path( '/svg/star-empty.svg' );
    } elseif ( 0.5 === $rating ) {
      echo '<span class="half">';
      include get_theme_file_path( '/svg/star-half.svg' );
      include get_theme_file_path( '/svg/star-bg.svg' );
      echo '</span>';
      include get_theme_file_path( '/svg/star-empty.svg' );
      include get_theme_file_path( '/svg/star-empty.svg' );
      include get_theme_file_path( '/svg/star-empty.svg' );
      include get_theme_file_path( '/svg/star-empty.svg' );
    } ?>
  </span>

  <?php
}
