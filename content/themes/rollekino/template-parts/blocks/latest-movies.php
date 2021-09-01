<?php
/**
 * Block
 *
 * @package rollekino
 */

namespace Air_Light;

wp_reset_postdata();
$args_latest = [
  'post_type' => 'movie',
  'posts_per_page' => 32,
];

$query_latest = new \WP_Query( $args_latest );
if ( ! empty( $query_latest->posts ) ) : ?>
  <section class="block block-movies-latest">
    <div class="container">
      <h2 class="block-title-secondary">Viimeksi katsotut elokuvat</h2>
      <p class="read-more"><a href="#">Katso kaikki <?php include get_theme_file_path( '/svg/arrow-right.svg' ); ?></a></a>

      <div class="movie-grid">
        <?php while ( $query_latest->have_posts() ) :
          $query_latest->the_post();

          // Metadata
          $poster_id = get_post_meta( get_the_ID(), 'poster', true );
          $poster_url = wp_get_attachment_image_url( $poster_id, 'full' );
          ?>

          <?php //if ( ! empty( $poster_id ) ) : ?>
            <div class="movie-poster-wrapper movie-poster-wrapper-small movie-poster-has-link">
              <a aria-label="Elokuvan <?php the_title(); ?> arvioon" class="global-link" href="<?php echo esc_url( get_the_permalink() ); ?>"></a>
              <div class="movie-poster">
                <img src="<?php echo esc_url( $poster_url ); ?>" alt="<?php echo esc_html( get_the_title( $poster_id ) ); ?>" />
                <div class="frame" aria-hidden="true"></div>
              </div>
            </div>
          <?php //endif; ?>

        <?php endwhile; ?>
      </div>

    </div>
  </section>
<?php endif;
