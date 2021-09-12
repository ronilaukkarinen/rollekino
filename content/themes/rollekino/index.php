<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @Date:   2019-10-15 12:30:02
 * @Last Modified by:   Roni Laukkarinen
 * @Last Modified time: 2020-03-17 10:17:21
 *
 * @package rollekino
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 */

namespace Air_Light;

get_header(); ?>

<main class="site-main">

  <?php get_template_part( 'template-parts/hero', get_post_type() ); ?>
  <section class="block block-blog has-dark-bg">
    <div class="container">

      <?php if ( have_posts() ) : ?>
        <div class="cols">

        <?php if ( is_home() && ! is_front_page() ) : ?>
          <h1 id="content" class="screen-reader-text"><?php single_post_title(); ?></h1>
        <?php endif; ?>

        <?php while ( have_posts() ) : the_post(); ?>
          <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <h2><a href="<?php echo esc_url( get_the_permalink() ); ?>"><?php the_title(); ?></a></h2>
            <p><time datetime="<?php the_time( 'c' ); ?>"><?php echo get_the_date( get_option( 'date_format' ) ); ?></time></p>
          </article>
        <?php endwhile; ?>

        </div>
        <?php the_posts_pagination(); ?>

      <?php endif; ?>

    </div>
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

<?php get_footer();
