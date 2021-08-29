<?php
/**
 * The template for displaying front page
 *
 * Contains the closing of the #content div and all content after.
 * Initial styles for front page template.
 *
 * @Date:   2019-10-15 12:30:02
 * @Last Modified by:   Roni Laukkarinen
 * @Last Modified time: 2020-03-03 14:38:06
 *
 * @package rollekino
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 */

namespace Air_Light;

get_header();
?>

<main class="site-main">

  <section class="block block-hero" style="display: none;">
    <div class="container">
      <h1>Rollen kotiteatterissa on katsottu ja arvioitu <?php echo esc_attr( wp_count_posts( 'movie' )->publish ); ?> elokuvaa vuodesta 2005.</h1>
    </div>
  </section>

  <?php
  $movies = [];
  $post_type = 'movie';
  $args = [
    'orderby' => 'date',
    'order' => 'DESC',
    'post_type' => $post_type,
    'posts_per_page' => 81,
  ];

  $query = new \WP_Query( $args );
  if ( ! empty( $query->posts ) ) : ?>
  <section class="block block-movies-latest">
    <div class="container">
      <h2>Viimeksi katsotut elokuvat</h2>

      <div class="movie-grid">
        <?php while ( $query->have_posts() ) :
          $query->the_post();

          // Metadata
          $poster_id = get_post_meta( get_the_ID(), 'poster', true );
          $poster_url = wp_get_attachment_image_url( $poster_id, 'full' );
          ?>

          <?php if ( ! empty( $poster_id ) ) : ?>
            <div class="movie-poster-wrapper movie-poster-wrapper-small">
              <div class="movie-poster">
                <img src="<?php echo esc_url( $poster_url ); ?>" alt="<?php echo esc_html( get_the_title( $poster_id ) ); ?>" />
                <div class="frame" aria-hidden="true"></div>
              </div>
            </div>
          <?php endif; ?>

        <?php endwhile; ?>
      </div>

    </div>
  </section>
  <?php endif; ?>

  <?php
  wp_reset_postdata();
  $movies = [];
  $post_type = 'movie';
  $args = [
    'orderby' => 'date',
    'order' => 'DESC',
    'post_type' => $post_type,
    'posts_per_page' => 1,
  ];

  $query = new \WP_Query( $args );

  if ( ! empty( $query->posts ) ) : ?>
    <section class="block block-movies" style="display: none;">

        <?php while ( $query->have_posts() ) :
          $query->the_post();

          // Meta data
          $backdrop_url = esc_url( wp_get_attachment_url( get_post_thumbnail_id() ) );
          $poster_id = get_post_meta( get_the_ID(), 'poster', true );
          $poster_url = wp_get_attachment_image_url( $poster_id, 'full' );
          $rating = get_post_meta( get_the_ID(), 'rating', true );
          $imdb_rating = get_post_meta( get_the_ID(), '_imdb_rating', true );
          $imdb_year = get_post_meta( get_the_ID(), '_imdb_year', true );
          $imdb_release_date = get_post_meta( get_the_ID(), '_imdb_release_date', true );
          $metascore_rating = get_post_meta( get_the_ID(), '_metascore_rating', true );
          $imdb_runtime_total_minutes = get_post_meta( get_the_ID(), '_idmb_runtime', true );
          $trailer_youtube_key = get_post_meta( get_the_ID(), '_trailer_youtube_key', true );
          ?>

          <div class="backdrop">
            <div class="lazy" style="background-image: url('<?php echo esc_url( $backdrop_url ); ?>'); ?>"></div>
            <?php // vanilla_lazyload_div( get_post_thumbnail_id() ); ?>

            <div class="video js-video">
              <div
                class="youtube-player"
                data-video-id="<?php echo esc_html( $trailer_youtube_key ); ?>"
                data-play-button="play-<?php echo esc_html( $trailer_youtube_key ); ?>">
              </div>

              <div aria-hidden="true" class="video-preview lazy" data-bg="<?php echo esc_url( $backdrop_url ); ?>"></div>
              <button
                aria-label="Toista traileri"
                class="play"
                id="play-<?php echo esc_html( $trailer_youtube_key ); ?>"
                type="button">
                <?php include get_theme_file_path( '/svg/play.svg' ); ?>
              </button>
            </div>

          </div>


          <div class="container">

          <img src="<?php echo esc_url( $poster_url ); ?>" alt="" />

          <h2><?php the_title(); ?></h2>

          <p>IMDb: <?php echo esc_html( $imdb_rating ); ?></p>
          <p>Omat pisteet: <?php echo esc_html( $rating ); ?></p>
          <p>Vuosi: <?php echo esc_html( $imdb_year ); ?></p>
          <p>Julkaisuajankohta: <?php echo esc_html( $imdb_release_date ); ?></p>
          <p>Metascore: <?php echo esc_html( $metascore_rating ); ?></p>

          <?php
          // Get the number of whole hours
          $idmb_runtime_hours = floor( $imdb_runtime_total_minutes / 60 );
          $imdb_runtime_minutes = $imdb_runtime_total_minutes % 60;
          $runtime_human_readable = $idmb_runtime_hours . ' tuntia, ' . $imdb_runtime_minutes . ' minuuttia';
          ?>

          <p><?php echo esc_html( $runtime_human_readable ); ?></p>
          <p>YouTube: <?php echo esc_html( $trailer_youtube_key ); ?></p>

          <h3>Pääosissa</h3>

          <?php
          $terms = get_the_terms( $post->ID, 'actor' );

          if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) : ?>
            <ul>
              <?php foreach ( $terms as $term ) :
                $avatar_url = get_field( 'avatar', 'actor_' . $term->term_id )['url'];
                ?>
                <li><?php echo esc_html( $term->name ); ?>
                <div style="width: 80px; height: 80px; border-radius: 50%; background-position: center; background-size: cover; background-image: url('<?php echo esc_url( $avatar_url ); ?>');"></div>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>

          <?php
          $terms = get_the_terms( $post->ID, 'director' ); ?>

          <h3>Ohjaaja<?php if ( 1 < count( $terms ) ) : echo 't'; endif; ?></h4>

          <?php if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) : ?>
            <ul>
              <?php foreach ( $terms as $term ) :
                $avatar_url = get_field( 'avatar', 'director_' . $term->term_id )['url'];
                ?>
                <li><?php echo esc_html( $term->name ); ?>
                <div style="width: 80px; height: 80px; border-radius: 50%; background-position: center; background-size: cover; background-image: url('<?php echo esc_url( $avatar_url ); ?>');"></div>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
          </div>

        <?php endwhile; ?>

    </section>
  <?php endif; ?>
</main>

<?php get_footer();
