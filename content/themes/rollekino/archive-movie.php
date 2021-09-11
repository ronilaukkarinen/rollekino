<?php
/**
 * Movies archive
 *
 * @package rollekino
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 */

namespace Air_Light;

get_header(); ?>

<div class="content-area">
	<main id="main" class="site-main">
    <?php get_template_part( 'template-parts/hero', get_post_type() ); ?>
    <section class="block block-movie-archive has-dark-bg">
      <div id="movies-listing"></div>
    </section>

    <?php
      wp_reset_postdata();
      if ( ! is_search() && empty( $_GET['s'] ) ) { // phpcs:ignore
        if ( ! is_front_page() ) {
          include get_theme_file_path( 'template-parts/search-modal.php' );
        }
      }
    ?>
	</main>
</div>

<?php get_footer();
