<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @Date:   2019-10-15 12:30:02
 * @Last Modified by:   Timi Wahalahti
 * @Last Modified time: 2021-01-12 17:23:47
 *
 * @package rollekino
 */

namespace Air_Light;

$results = [];

if ( ! empty( $_GET['s'] ) && have_posts() ) {
  while ( have_posts() ) {
    the_post();
    $post_type = get_post_type();

    $results[ $post_type ]['posts'][] = [
      'title'     => (string) get_the_title(),
      'permalink' => (string) get_permalink(),
      'excerpt'   => (string) get_the_excerpt(),
    ];
  }
} wp_reset_postdata();

// Get post type objects for results
foreach ( $results as $slug => $post_type ) {
  $results[ $slug ]['object'] = (object) get_post_type_object( $slug );
  $results[ $slug ]['count']  = (int) count( $results[ $slug ]['posts'] );
}

get_header(); ?>

<main class="site-main">

  <div id="search"></div>

</main>

<?php get_footer();
