<?php
/**
 * The template for displaying all single posts
 *
 * @Date:   2019-10-15 12:30:02
 * @Last Modified by:   Timi Wahalahti
 * @Last Modified time: 2021-01-12 16:11:09
 *
 * @package rollekino
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 */

namespace Air_Light;

the_post();
get_header();

// Meta data
$backdrop_url = esc_url( wp_get_attachment_url( get_post_thumbnail_id() ) );
$poster_id = get_post_meta( get_the_ID(), 'poster', true );
$poster_url = wp_get_attachment_image_url( $poster_id, 'full' );
$rating = get_post_meta( get_the_ID(), 'rating', true );
$imdb_rating = get_post_meta( get_the_ID(), '_imdb_rating', true );
$imdb_url = get_post_meta( get_the_ID(), 'imdb_url', true );
$imdb_year = get_post_meta( get_the_ID(), '_imdb_year', true );
$imdb_release_date = get_post_meta( get_the_ID(), '_imdb_release_date', true );
$metascore_rating = get_post_meta( get_the_ID(), '_metascore_rating', true );
$imdb_runtime_total_minutes = get_post_meta( get_the_ID(), '_idmb_runtime', true );
$trailer_youtube_key = get_post_meta( get_the_ID(), '_trailer_youtube_key', true );
$metascore_url = 'https://www.metacritic.com/movie/' . sanitize_title( get_the_title() );

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

<main class="site-main">

<section class="block block-hero-movies block-hero-movies-single has-dark-bg">
    <div class="shade" aria-hidden="true"></div>

    <div class="container">
        <div class="backdrop">
          <div class="lazy" style="background-image: url('<?php echo esc_url( $backdrop_url ); ?>'); ?>"></div>

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

          <div class="movie-meta-data-box movie-meta-data-box-large">
            <div class="movie-poster-wrapper movie-poster-wrapper-large">
              <div class="movie-poster">
                <img src="<?php echo esc_url( $poster_url ); ?>" alt="<?php echo esc_html( get_the_title( $poster_id ) ); ?>" />
                <div class="frame" aria-hidden="true"></div>
              </div>
            </div>

            <div class="movie-meta-data-content">

              <ul class="poster-information">
                <?php
                  $terms = get_the_terms( get_the_ID(), 'director' ); ?>
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
                  $terms = get_the_terms( get_the_ID(), 'writer' ); ?>
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
                  $terms = get_the_terms( get_the_ID(), 'actor' ); ?>
                  <?php if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) : ?>
                    <li class="casting">
                      <span class="screen-reader-text">Pääosissa</span>
                      <?php foreach ( $terms as $term ) : ?>
                        <span class="name"><?php echo esc_html( $term->name ); ?></span>
                      <?php endforeach; ?>
                    </li>
                <?php endif; ?>
              </ul>

              <h1 class="movie-meta-data-title"><?php the_title(); ?> <span class="release-year"><?php echo esc_html( $imdb_year ); ?></span></h1>
              <ul class="movie-meta-data-list">
                <!-- <li class="movie-meta-data-watched">Katsottu <?php echo get_the_date(); ?></li> -->
                <li><?php rating_stars( $text = true ); ?></li>
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

  <section class="block block-movie-review has-dark-bg">
    <div class="gutenberg-content">

      <h2><?php the_title(); ?></h2>

<p>Vuosi: <?php echo esc_html( $imdb_year ); ?></p>
<p>Julkaisuajankohta: <?php echo esc_html( $imdb_release_date ); ?></p>

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
$terms = get_the_terms( get_the_ID(), 'actor' );

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
$terms = get_the_terms( get_the_ID(), 'director' ); ?>

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

      <?php the_content();

      // Required by WordPress Theme Check, feel free to remove as it's rarely used in starter themes
      wp_link_pages( array( 'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'rollekino' ), 'after' => '</div>' ) );

      entry_footer();

      if ( get_edit_post_link() ) {
        edit_post_link( sprintf( wp_kses( __( 'Muokkaa <span class="screen-reader-text">%s</span>', 'rollekino' ), [ 'span' => [ 'class' => [] ] ] ), get_the_title() ), '<p class="edit-link">', '</p>' );
      }

      the_post_navigation();

  		// If comments are open or we have at least one comment, load up the comment template.
      if ( comments_open() || get_comments_number() ) {
        comments_template();
      } ?>

    </div>
  </section>

</main>

<?php get_footer();
