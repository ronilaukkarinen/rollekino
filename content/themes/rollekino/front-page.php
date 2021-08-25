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
          $rating_imdb = get_post_meta( get_the_ID(), '_imdb_rating', true );
          ?>

          <h2><?php the_title(); ?></h2>

          <p>IMDb: <?php echo esc_html( $rating_imdb ); ?></p>
          <p>Omat pisteet: <?php echo esc_html( $rating ); ?></p>
        <?php endwhile; ?>
      </div>

    </section>
  <?php endif; ?>
</main>

<?php get_footer();
