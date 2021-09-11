<?php
/**
* @Author: Roni Laukkarinen
* @Date: 2021-08-24 15:45:19
* @Last Modified by:   Roni Laukkarinen
* @Last Modified time: 2021-09-11 14:37:48
*
* @package flux
*/

namespace Air_Light;

if ( ! empty( $args ) ) {
 $movie = $args['movie'];
} else {
 $movie = get_field( 'movie' );
}

if ( empty( $movie ) )
return;

// Set locale
date_default_timezone_set( 'Europe/Helsinki' ); // phpcs:ignore
setlocale( LC_ALL, array( 'fi_FI.UTF-8', 'fi_FI@euro', 'fi_FI', 'Finnish' ) );
setlocale( LC_TIME, array( 'fi_FI.UTF-8', 'fi_FI@euro', 'fi_FI', 'Finnish' ) );

// Meta data
$post_id = $movie->ID;
$backdrop_url = esc_url( wp_get_attachment_url( get_post_thumbnail_id() ) );
$poster_id = get_post_meta( $post_id, 'poster', true );
$poster_url = wp_get_attachment_image_url( $poster_id, 'full' );
$rating = get_post_meta( $post_id, 'rating', true );
$imdb_rating = get_post_meta( $post_id, '_imdb_rating', true );
$imdb_url = get_post_meta( $post_id, 'imdb_url', true );
$imdb_year = get_post_meta( $post_id, '_imdb_year', true );
$imdb_release_date = get_post_meta( $post_id, '_imdb_release_date', true );
$imdb_release_date_readable = strftime( '%e. %B', strtotime( $imdb_release_date ) ) . 'ta ' . strftime( '%Y', strtotime( $imdb_release_date ) );
$metascore_rating = get_post_meta( $post_id, '_metascore_rating', true );
$imdb_runtime_total_minutes = get_post_meta( $post_id, '_idmb_runtime', true );
$idmb_runtime_hours = floor( $imdb_runtime_total_minutes / 60 );
$imdb_runtime_minutes = $imdb_runtime_total_minutes % 60;
$runtime_human_readable = $idmb_runtime_hours . ' tuntia, ' . $imdb_runtime_minutes . ' minuuttia';
$trailer_youtube_key = get_post_meta( $post_id, '_trailer_youtube_key', true );
$metascore_url = 'https://www.metacritic.com/movie/' . sanitize_title( get_the_title( $post_id ) );

if ( 60 <= $metascore_rating ) {
  $metascore_class = 'positive';
} elseif ( 40 <= $metascore_rating ) {
  $metascore_class = 'mixed';
} elseif ( 40 >= $metascore_rating ) {
  $metascore_class = 'negative';
} else {
  $metascore_class = 'tbd';
}
?>

<section class="block block-picked-movie block-hero-movies block-hero-movies-single has-dark-bg">

    <div class="container">
        <div class="movie-meta-data">

          <div class="movie-meta-data-box movie-meta-data-box-large">
            <div class="movie-poster-wrapper movie-poster-wrapper-large">
              <div class="movie-poster">
                <img src="<?php echo esc_url( $poster_url ); ?>" alt="<?php echo esc_html( get_the_title( $poster_id ) ); ?>" />
                <div class="frame" aria-hidden="true"></div>
              </div>
            </div>

            <div class="movie-meta-data-content">

              <div class="buttons">
                <?php if ( ! empty( $trailer_youtube_key ) ) : ?>
                  <a type="button" class="mediabox no-external-link-indicator" href="https://www.youtube.com/watch?v=<?php echo esc_html( $trailer_youtube_key ); ?>">
                    <?php include get_theme_file_path( '/svg/youtube.svg' ); ?>
                    <span>Katso traileri</span>
                  </a>
                <?php endif; ?>
              </div>

              <ul class="poster-information">
                <?php
                  $terms = get_the_terms( $post_id, 'director' ); ?>
                  <?php if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) : ?>
                    <li class="directed">
                      <span class="screen-reader-text">Ohjannut</span>
                      <span class="crew" aria-hidden="true">k</span>
                      <?php foreach ( $terms as $term ) : ?>
                        <span class="name"><?php echo esc_html( $term->name ); ?></span>
                      <?php endforeach; ?>
                    </li>
                <?php endif; ?>

                <?php
                  $terms = get_the_terms( $post_id, 'writer' ); ?>
                  <?php if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) : ?>
                    <li class="writer">
                      <span class="screen-reader-text">Käsikirjoitus</span>
                      <span class="crew" aria-hidden="true">a</span>
                      <?php foreach ( $terms as $term ) : ?>
                        <span class="name"><?php echo esc_html( $term->name ); ?></span>
                      <?php endforeach; ?>
                    </li>
                <?php endif; ?>

                <?php
                  $terms = get_the_terms( $post_id, 'actor' ); ?>
                  <?php if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) : ?>
                    <li class="casting">
                      <span class="screen-reader-text">Pääosissa</span>
                      <?php foreach ( $terms as $term ) : ?>
                        <span class="name"><?php echo esc_html( $term->name ); ?></span>
                      <?php endforeach; ?>
                    </li>
                <?php endif; ?>
              </ul>

              <h2 class="movie-meta-data-title">
                <?php echo esc_html( $movie->post_title ); ?> <span class="release-year"><?php echo esc_html( $imdb_year ); ?></span>
              </h2>
              <ul class="movie-meta-data-list">
                <li><?php rating_stars_by_id( $post_id ); ?></li>
                <li class="imdb">
                  <a href="<?php echo esc_url( $imdb_url ); ?>" class="no-external-link-indicator">
                    <span class="screen-reader-text">IMDb-pisteet:</span>
                    <?php include get_theme_file_path( '/svg/imdb.svg' ); ?>
                    <span class="rating">
                      <?php echo esc_html( $imdb_rating ); ?>
                    </span>
                  </a>
                </li>
                <?php if ( ! empty( $metascore_rating ) ) : ?>
                  <li class="metascore">
                    <a href="<?php echo esc_url( $metascore_url ); ?>" class="no-external-link-indicator">
                      <span class="screen-reader-text">Metascore-pisteet:</span>
                      <span class="rating <?php echo esc_html( $metascore_class ); ?>">
                        <?php echo esc_html( $metascore_rating ); ?>
                      </span>
                    </a>
                  </li>
                <?php endif; ?>
              </ul>
            </div>
          </div>

        </div>

</section>
