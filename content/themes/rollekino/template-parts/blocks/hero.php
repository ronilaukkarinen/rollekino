<?php
/**
 * Block
 *
 * @package rollekino
 */

namespace Air_Light;

wp_reset_postdata();
$movies = [];
$post_type = 'movie';
$args = [
  'orderby' => 'rand',
  'post_type' => $post_type,
  'posts_per_page' => 1,
];

$query = new \WP_Query( $args );

if ( ! empty( $query->posts ) ) : ?>
  <section class="block block-search block-hero-movies has-dark-bg">
    <div class="shade" aria-hidden="true"></div>

    <div class="container">
      <div class="content">
        <h1 class="block-title">Rollen kotiteatterissa on katsottu ja arvioitu <?php echo esc_attr( wp_count_posts( 'movie' )->publish ); ?> elokuvaa vuodesta 2005.</h1>

        <div id="search"></div>
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
          </div>
        </div>

        <div class="movie-meta-data">

          <button
            class="play play-button hidden"
            id="play-<?php echo esc_html( $trailer_youtube_key ); ?>"
            type="button">
            <span class="play-label hidden">
              <?php include get_theme_file_path( '/svg/play.svg' ); ?> Jatka trailerin pyörittämistä sittenkin
            </span>
            <span class="pause-label hidden">
              <?php include get_theme_file_path( '/svg/pause.svg' ); ?> Älä spoilaa, pysäytä taustavideo
            </span>
          </button>

          <div class="movie-meta-data-box has-link">
            <a aria-label="Elokuvan <?php the_title(); ?> arvioon" class="global-link" href="<?php echo esc_url( get_the_permalink() ); ?>"></a>
            <div class="movie-poster-wrapper movie-poster-wrapper-small">
              <div class="movie-poster">
                <img src="<?php echo esc_url( $poster_url ); ?>" alt="<?php echo esc_html( get_the_title( $poster_id ) ); ?>" />
                <div class="frame" aria-hidden="true"></div>
              </div>
            </div>

            <div class="movie-meta-data-content">
              <h3 class="movie-meta-data-title"><?php the_title(); ?> <span class="release-year"><?php echo esc_html( $imdb_year ); ?></span></h3>
              <ul class="movie-meta-data-list">
                <li class="movie-meta-data-watched">Katsottu <?php echo get_the_date(); ?></li>
                <li><?php rating_stars(); ?></li>
              </ul>
            </div>
          </div>

        </div>

      <?php endwhile; ?>

  </section>
<?php endif;
