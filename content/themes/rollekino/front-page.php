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

$movies = [];
$post_type = 'movie';
$args = [
  'post_type' => $post_type,
  'posts_per_page' => 10,
];

$query = new \WP_Query( $args );
?>

<main class="site-main">

  <?php if ( ! empty( $query->posts ) ) : ?>
    <section class="block block-movies">

      <div class="container">
        <?php while ( $query->have_posts() ) :
          $query->the_post();

          // Meta data
          $rating = get_post_meta( get_the_ID(), 'rating', true );
          $imdb_rating = get_post_meta( get_the_ID(), '_imdb_rating', true );
          $imdb_year = get_post_meta( get_the_ID(), '_imdb_year', true );
          $imdb_release_date = get_post_meta( get_the_ID(), '_imdb_release_date', true );
          $metascore_rating = get_post_meta( get_the_ID(), '_metascore_rating', true );
          ?>

          <h2><?php the_title(); ?></h2>

          <p>IMDb: <?php echo esc_html( $imdb_rating ); ?></p>
          <p>Omat pisteet: <?php echo esc_html( $rating ); ?></p>
          <p>Vuosi: <?php echo esc_html( $imdb_year ); ?></p>
          <p>Julkaisuajankohta: <?php echo esc_html( $imdb_release_date ); ?></p>
          <p>Metascore: <?php echo esc_html( $metascore_rating ); ?></p>

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


        <?php endwhile; ?>
      </div>

    </section>
  <?php endif; ?>
</main>

<?php get_footer();
