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
    include get_theme_file_path( 'template-parts/blocks/hero.php' );
    include get_theme_file_path( 'template-parts/blocks/latest-movies.php' );
    include get_theme_file_path( 'template-parts/blocks/genres.php' );
    include get_theme_file_path( 'template-parts/blocks/best-movies.php' );
  ?>

  <?php
    wp_reset_postdata();
    if ( ! is_search() && empty( $_GET['s'] ) ) { // phpcs:ignore
      if ( ! is_front_page() ) {
        include get_theme_file_path( 'template-parts/search-modal.php' );
      }
    }
  ?>
</main>

<?php get_footer();
