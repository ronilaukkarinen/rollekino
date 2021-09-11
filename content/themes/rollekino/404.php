<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @Date:   2019-10-15 12:30:02
 * @Last Modified by:   Timi Wahalahti
 * @Last Modified time: 2021-01-12 15:13:28
 *
 * @package rollekino
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 */

namespace Air_Light;

$backdrop_url = wp_get_attachment_url( 73114 );
$backdrop_video_url = wp_get_attachment_url( 73116 );
get_header(); ?>

<main class="site-main">

  <div class="backdrop">
    <div class="shade" aria-hidden="true"></div>
    <div class="lazy" style="background-image: url('<?php echo esc_url( $backdrop_url ); ?>');"></div>
    <?php // vanilla_lazyload_div( get_post_thumbnail_id() ); ?>

    <div class="video js-video">
      <video src="<?php echo esc_url( $backdrop_video_url ); ?>" loop muted autoplay></video>
      <div aria-hidden="true" class="video-preview lazy" data-bg="<?php echo esc_url( $backdrop_url ); ?>"></div>
    </div>
  </div>

  <section class="block block-error-404">
    <div class="container">
      <div class="content">

        <h1 id="content">These aren't the Droids you're looking for</h1>
        <p>Sivua ei löydy.</p>

      </div>
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

<?php

// Enable visible footer if fits project:
// get_footer();

// WordPress scripts and hooks
wp_footer();
