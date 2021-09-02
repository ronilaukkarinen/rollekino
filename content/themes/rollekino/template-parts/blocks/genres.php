<?php
/**
 * Block
 *
 * @package rollekino
 */

namespace Air_Light;

?>

<section class="block block-genres">
  <div class="container">
    <div class="cols">

      <?php
      $terms = get_terms(
        array(
          'taxonomy' => 'genre',
        )
      );

      if ( ! empty( $terms ) && is_array( $terms ) ) : ?>

        <?php foreach ( $terms as $term ) : ?>
        <div class="col">

          <?php
          $genre_backdrops = [];
          $genre_backdrops_args = [
            'orderby' => 'rand',
            'post_type' => 'movie',
            'posts_per_page' => 1,
            'tax_query' => array(
              array(
                'taxonomy' => 'genre',
                'terms' => $term->slug,
              ),
            ),
          ];

          $genre_backdrops_query = new \WP_Query( $genre_backdrops_args );
            if ( ! empty( $genre_backdrops_query->posts ) ) : ?>
              <?php while ( $genre_backdrops_query->have_posts() ) :
                $genre_backdrops_query->the_post();
                $backdrop_url = wp_get_attachment_image_url( get_post_thumbnail_id( $post->ID ), 'full' );
              ?>
                <div class="backdrop-genre" aria-hidden="true" style="background-image: url('<?php echo esc_url( $backdrop_url ); ?>');"></div>
              <?php endwhile; ?>
            <?php endif; ?>

              <a href="<?php echo esc_url( get_term_link( $term ) ) ?>">
                <?php echo esc_html( $term->name ); ?>
              </a>

          </div>
        <?php endforeach; ?>
      <?php endif; ?>

    </div>
  </section>
