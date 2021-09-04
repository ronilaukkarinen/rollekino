<?php
/**
 * Single movie in listing
 *
 * @package rollekino
 */

namespace Air_Light;

$post_id = ! empty( $args ) && isset( $args['post_id'] ) ? $args['post_id'] : get_the_ID();
$movie_cat = get_primary_category( $post_id, 'genre' );
$movie_thumbnail_id = has_post_thumbnail( $post_id ) ? get_post_thumbnail_id( $post_id ) : false;

// Metadata
$poster_id = get_post_meta( $post_id, 'poster', true );
$poster_url = wp_get_attachment_image_url( $poster_id, 'full' );
?>

  <a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>" class="global-link">
    <span class="screen-reader-text">
      <?php echo esc_html( get_the_title( $post_id ) ); ?>
    </span>
  </a>

  <p class="meta">
    <span class="cat-single">
      <?php if ( $movie_cat ) : ?>
        <a href="<?php echo esc_url( get_term_link( $movie_cat ) ); ?>">
          <?php echo esc_html( $movie_cat->name ); ?>
        </a>
      <?php endif; ?>
    </span>
  </p>

  <div class="movie-image">
    <div class="movie-poster-wrapper movie-poster-wrapper-small has-link">
      <a aria-label="Elokuvan <?php the_title(); ?> arvioon" class="global-link" href="<?php echo esc_url( get_the_permalink() ); ?>"></a>
        <div class="movie-poster">
          <img src="<?php echo esc_url( $poster_url ); ?>" alt="<?php echo esc_html( get_the_title( $poster_id ) ); ?>" />
          <div class="frame" aria-hidden="true"></div>
        </div>
    </div>
  </div>

  <h2><?php echo esc_html( get_the_title( $post_id ) ); ?></h2>

