<?php
/**
 * Single movie in listing
 *
 * @package rollekino
 */

namespace Air_Light;

$post_id = ! empty( $args ) && isset( $args['post_id'] ) ? $args['post_id'] : get_the_ID();

// Metadata
$poster_id = get_post_meta( $post_id, 'poster', true );
$poster_url = wp_get_attachment_image_url( $poster_id, 'full' );
$rating = get_post_meta( $post_id, 'rating', true );
$imdb_rating = get_post_meta( $post_id, '_imdb_rating', true );
$imdb_year = get_post_meta( $post_id, '_imdb_year', true );
?>

<div class="movie-meta-data has-link">
  <div class="movie-meta-data-box">
    <a aria-label="Elokuvan <?php echo esc_html( get_the_title( $post_id ) ); ?> arvioon" class="global-link" href="<?php echo esc_url( get_the_permalink() ); ?>"></a>
      <?php if ( ! empty( $poster_id ) ) : ?>
        <div class="movie-poster-wrapper movie-poster-wrapper-small">
          <div class="movie-poster">
            <img src="<?php echo esc_url( $poster_url ); ?>" alt="<?php echo esc_html( get_the_title( $poster_id ) ); ?>" />
            <div class="frame" aria-hidden="true"></div>
          </div>
        </div>
    <?php endif; ?>

    <div class="movie-meta-data-content">
      <h3 class="movie-meta-data-title"><?php the_title(); ?> <span class="release-year"><?php echo esc_html( $imdb_year ); ?></span></h3>
      <ul class="movie-meta-data-list">
        <li class="movie-meta-data-watched">Katsottu <?php echo get_the_date(); ?></li>
        <li><?php rating_stars(); ?></li>
      </ul>
    </div>
  </div>
</div>
