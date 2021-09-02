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
    <ul class="cols">

      <?php
      $terms = get_terms(
        array(
          'taxonomy' => 'genre',
          // Exclude musicals, short films, reality and news
          'exclude' => [ 11996, 10560, 3790, 2305 ],
        )
      );

      if ( ! empty( $terms ) && is_array( $terms ) ) : ?>

        <?php foreach ( $terms as $term ) : ?>
        <li class="col">

          <?php
          $genre_backdrops = [];
          $genre_backdrops_args = [
            'orderby' => 'rand',
            'post_type' => 'movie',
            'posts_per_page' => 1,

            // Featured image must exist (backdrop)
            'meta_query' => array(
              array(
                'key' => '_thumbnail_id',
              ),
            ),

            // Filter by current genre
            'tax_query' => array(
              array(
                'taxonomy' => 'genre',
                'terms' => $term->term_id,
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

              <a href="<?php echo esc_url( get_term_link( $term ) ) ?>" class="global-link genre-link">
                <?php echo esc_html( $term->name ); ?>
              </a>

          </li>
        <?php endforeach; ?>
      <?php endif; ?>

    </ul>
  </section>
