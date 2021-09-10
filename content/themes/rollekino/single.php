<?php
/**
 * The template for displaying all single posts
 *
 * @Date:   2019-10-15 12:30:02
 * @Last Modified by:   Timi Wahalahti
 * @Last Modified time: 2021-01-12 16:11:09
 *
 * @package rollekino
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 */

namespace Air_Light;

the_post();
get_header();
?>

<main class="site-main">

  <?php get_template_part( 'template-parts/hero', get_post_type() ); ?>
  <section class="block block-single has-dark-bg">
    <div class="gutenberg-content">

      <h2><?php the_title(); ?></h2>

      <?php the_content();

      if ( get_edit_post_link() ) {
        edit_post_link( sprintf( wp_kses( __( 'Muokkaa <span class="screen-reader-text">%s</span>', 'rollekino' ), [ 'span' => [ 'class' => [] ] ] ), get_the_title() ), '<p class="edit-link">', '</p>' );
      }
      ?>

    </div>
  </section>

</main>

<?php get_footer();
