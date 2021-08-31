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
  'orderby' => 'date',
  'order' => 'DESC',
  'post_type' => $post_type,
  'posts_per_page' => 32,
];

$query = new \WP_Query( $args );
if ( ! empty( $query->posts ) ) : ?>
  <section class="block block-movies-latest">
    <div class="container">
      <h2 class="block-title-secondary">Viimeksi katsotut elokuvat</h2>
      <p class="read-more"><a href="#">Katso kaikki <?php include get_theme_file_path( '/svg/arrow-right.svg' ); ?></a></a>

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
<?php endif;
