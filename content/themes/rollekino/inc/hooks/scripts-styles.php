<?php
/**
 * Enqueue and localize theme scripts and styles
 *
 * @Author: Niku Hietanen
 * @Date: 2020-02-20 13:46:50
 * @Last Modified by:   Roni Laukkarinen
 * @Last Modified time: 2021-09-05 15:52:46
 *
 * @package rollekino
 */

namespace Air_Light;

/**
 * Move jQuery to footer
 */
function move_jquery_into_footer( $wp_scripts ) {
  if ( ! is_admin() ) {
    $wp_scripts->add_data( 'jquery',         'group', 1 );
    $wp_scripts->add_data( 'jquery-core',    'group', 1 );
    $wp_scripts->add_data( 'jquery-migrate', 'group', 1 );
  }
} // end rollekino_move_jquery_into_footer

/**
 * Enqueue scripts and styles.
 */
function enqueue_theme_scripts() {

  // Enqueue global.css
  wp_enqueue_style( 'styles',
    get_theme_file_uri( get_asset_file( 'global.css' ) ),
    [],
    filemtime( get_theme_file_path( get_asset_file( 'global.css' ) ) )
  );

  // Enqueue jquery and front-end.js
  wp_enqueue_script( 'jquery-core' );
  wp_enqueue_script( 'scripts',
    get_theme_file_uri( get_asset_file( 'front-end.js' ) ),
    [],
    filemtime( get_theme_file_path( get_asset_file( 'front-end.js' ) ) ),
    true
  );

  // Search application
  wp_enqueue_script( 'search',
    get_theme_file_uri( get_asset_file( 'search.js' ) ),
    [],
    filemtime( get_theme_file_path( get_asset_file( 'search.js' ) ) ),
    true
  );

  wp_localize_script( 'search', 'rollekino_searchLocalization', [
    'apiUrl'        => get_rest_url(),
    'inputLabel'    => 'Hae',
    'noResults'     => 'Ei hakutuloksia haulla',
    'instructions'  => 'Voit hakea elokuvia nimen, ohjaajan, genren tai minkä tahansa hakusanan kuten "pelottava" perusteella. Haku hakee niin nimestä, tiedoista kuin arvionkin tekstistä.',
  ] );

  // Required comment-reply script
  if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
    wp_enqueue_script( 'comment-reply' );
  }

  wp_localize_script( 'scripts', 'rollekino_screenReaderText', [
    'expand'          => get_default_localization( 'Open child menu' ),
    'collapse'        => get_default_localization( 'Close child menu' ),
    'expand_for'      => get_default_localization( 'Open child menu for' ),
    'collapse_for'    => get_default_localization( 'Close child menu for' ),
    'expand_toggle'   => get_default_localization( 'Open main menu' ),
    'collapse_toggle' => get_default_localization( 'Close main menu' ),
    'external_link'   => get_default_localization( 'External site:' ),
    'target_blank'    => get_default_localization( 'opens in a new window' ),
  ] );

  // Add domains/hosts to disable external link indicators
  wp_localize_script( 'scripts', 'rollekino_externalLinkDomains', [
      'localhost:3000',
      'airdev.test',
      'airwptheme.com',
  ] );

  wp_localize_script( 'scripts', 'rollekino_apiURL', esc_url( get_home_url() ) );
} // end rollekino_scripts

/**
 * Load polyfills for legacy browsers
 */
function enqueue_polyfills() {
  // Include polyfills
  $script = '
  var supportsES6 = (function () {
  try {
    new Function("(a = 0) => a");
    return true;
  } catch (err) {
    return false;
  }
  }());
  var legacyScript ="' . esc_url( get_theme_file_uri( get_asset_file( 'legacy.js' ) ) ) . '";
  if (!supportsES6) {
    var script = document.createElement("script");
    script.src = legacyScript;
    document.head.appendChild(script);
  }';

  if ( file_exists( get_theme_file_path( get_asset_file( 'legacy.js' ) ) ) ) {
    wp_register_script( 'rollekino_legacy', '', [], filemtime( get_theme_file_path( get_asset_file( 'legacy.js' ) ) ), false );
    wp_enqueue_script( 'rollekino_legacy' );
    wp_add_inline_script( 'rollekino_legacy', $script, true );
  }
} // end enqueue_polyfills

/**
 * Returns the built asset filename and path depending on
 * current environment.
 *
 * @param string $filename File name with the extension
 * @return string file and path of the asset file
 */
function get_asset_file( $filename ) {
  $env = 'development' === wp_get_environment_type() && ! isset( $_GET['load_production_builds'] ) ? 'dev' : 'prod'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

  $filetype = pathinfo( $filename )['extension'];

  return "${filetype}/${env}/${filename}";
} // end get_asset_file

/**
 * Localize data to movies archive
 */
function movie_archive_scripts() {
  if ( ! is_post_type_archive( 'movie' ) ) {
		return;
  }

  wp_register_script( 'movies', get_theme_file_uri( get_asset_file( 'movies.js' ) ), array(), filemtime( get_theme_file_uri( get_asset_file( 'movies.js' ) ) ), true );

  $movie_filters = [
    'movieGenre' => [
      'taxonomy' => 'genre',
      'args' => [],
      'title' => 'Genre',
      'type' => 'checkbox',
    ],
  ];

  foreach ( $movie_filters as $object_name => $taxonomy ) {
    $movie_filters[ $object_name ]['filters'] = get_movie_filter( $object_name, $taxonomy['taxonomy'], $taxonomy['args'] );
    $taxonomy = get_taxonomy( $taxonomy['taxonomy'] );
    $movie_filters[ $object_name ]['name'] = $taxonomy->rewrite['slug'] ? $taxonomy->rewrite['slug'] : $taxonomy->name;
  }

  wp_localize_script( 'movies', 'rollekino_movieFilters', $movie_filters );

  $default_content = get_initial_movies();

  wp_localize_script( 'scripts', 'rollekino_defaultMovies', $default_content['movies'] ? $default_content['movies'] : [] );
  wp_localize_script( 'scripts', 'rollekino_moviePages', $default_content['pages'] ? (string) $default_content['pages'] : '0' );
  wp_localize_script( 'scripts', 'rollekino_movieCount', $default_content['count'] ? (string) $default_content['count'] : '0' );

  wp_localize_script( 'movies', 'rollekino_movieLocalization', [
    'pagination' => [
      'selectPage'   => 'Avaa sivu',
      'nextPage'     => 'Seuraava sivu',
      'previousPage' => 'Edellinen sivu',
    ],
    'filters' => [
      'remove' => 'Poista valinta',
    ],
    'orderBy' => [
      'default' => 'Oletus',
      'date'    => 'Viimeksi katsottu ensin',
      'meta_value_num' => 'Pisteytys',
      'title'   => 'Aakkosjärjestys',
    ],
    'movieList' => [
      'inTotal' => 'Yhteensä',
      'movies'   => 'elokuvaa',
      'movie'    => 'elokuva',
    ],
    'moviesPerPage'  => [
      'label'   => 'Näytä sivulla',
      'prepend' => 'elokuvaa',
    ],
  ] );

  wp_enqueue_script( 'movies' );
}
