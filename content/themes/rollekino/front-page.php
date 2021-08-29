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

  <?php
  wp_reset_postdata();
  $movies = [];
  $post_type = 'movie';
  $args = [
    'orderby' => 'rand',
    'order' => 'DESC',
    'post_type' => $post_type,
    'posts_per_page' => 1,
  ];

  $query = new \WP_Query( $args );

  if ( ! empty( $query->posts ) ) : ?>
    <section class="block block-hero-movies">
      <div class="shade" aria-hidden="true"></div>

      <div class="container">
        <div class="content">
          <h2 class="block-title">Rollen kotiteatterissa on katsottu ja arvioitu <?php echo esc_attr( wp_count_posts( 'movie' )->publish ); ?> elokuvaa vuodesta 2005.</h2>

          <form role="search" method="get" class="search-form" action="<?php echo esc_url( get_home_url() ); ?>">
				    <label for="search">Hae elokuvaa</label>
					  <input id="search" type="search" class="search-field" value="" name="s">
				    <input type="submit" class="search-submit" value="Hae">
			    </form>
        </div>

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

          <div class="movie-meta-data">

            <div class="movie-poster-wrapper movie-poster-wrapper-small">
              <div class="movie-poster">
                <img src="<?php echo esc_url( $poster_url ); ?>" alt="<?php echo esc_html( get_the_title( $poster_id ) ); ?>" />
                <div class="frame" aria-hidden="true"></div>
              </div>
            </div>

            <div class="movie-meta-data-content">
              <h3 class="movie-meta-data-title"><?php the_title(); ?> <span class="release-year"><?php echo esc_html( $imdb_year ); ?></span></h3>
              <ul class="movie-meta-data-list">
                <li></li>
                <li><?php echo esc_html( $rating ); ?></li>
              </ul>
            </div>

          </div>

        <?php endwhile; ?>

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
    'posts_per_page' => 79,
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
</main>

<?php get_footer();
