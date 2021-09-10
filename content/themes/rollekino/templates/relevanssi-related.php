<?php
/**
 * @Author: Roni Laukkarinen
 * @Date: 2021-08-26 14:08:03
 * @Last Modified by:   Roni Laukkarinen
 * @Last Modified time: 2021-09-10 21:48:42
 *
 * @package flux
 */
namespace Air_Light;

wp_reset_postdata();
$articles = [];
$related_posts = array_slice( $related_posts, 0, 6 );

if ( empty( $related_posts ) ) {
  return;
}
?>

<section class="block block-related">
  <div class="container">
      <h2 class="block-title-secondary">
        <?php ask_e( 'Samankaltaisia elokuvia, tai jotain sinnepäin' ); ?>
      </h2>
      <p class="read-more">
        <a href="<?php echo esc_url( get_post_type_archive_link( 'movie' ) ); ?>">
          Katso kaikki arviot
          <?php include get_theme_file_path( '/svg/arrow-right.svg' ); ?>
        </a>
      </p>

    <div class="cols">
    <?php foreach ( $related_posts as $article_id ) : ?>

      <div class="col">
        <?php
          $args = [
            'title'       => get_the_title( $article_id ),
            'link'        => get_the_permalink( $article_id ),
          ];

          $poster_id = get_post_meta( $article_id, 'poster', true );
          $poster_url = wp_get_attachment_image_url( $poster_id, 'full' );
          $imdb_year = get_post_meta( $article_id, '_imdb_year', true );
        ?>

          <div class="movie-meta-data has-link">
            <div class="movie-meta-data-box">
              <a aria-label="Elokuvan <?php echo esc_html( $args['title'] ); ?> arvioon" class="global-link" href="<?php echo esc_url( $args['link'] ); ?>"></a>

                <div class="movie-poster-wrapper">
                  <div class="movie-poster">
                    <img src="<?php echo esc_url( $poster_url ); ?>" alt="<?php echo esc_html( $args['title'] ); ?>" />
                    <div class="frame" aria-hidden="true"></div>
                  </div>
                </div>

              <div class="movie-meta-data-content">
                <h3 class="movie-meta-data-title"><?php echo esc_html( $args['title'] ); ?> <span class="release-year"><?php echo esc_html( $imdb_year ); ?></span></h3>
                <ul class="movie-meta-data-list">
                  <li class="movie-meta-data-watched">Katsottu <?php echo esc_html( get_the_date( 'j.n.Y', $article_id ) ); ?></li>
                  <li><?php rating_stars_by_id( $article_id ); ?></li>
                </ul>
              </div>
            </div>
          </div>
      </div><!-- .col -->

    <?php endforeach; ?>
    </div>
  </div>
</section>
