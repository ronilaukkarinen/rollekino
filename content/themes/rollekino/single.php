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

// Meta data
$backdrop_url = esc_url( wp_get_attachment_url( get_post_thumbnail_id() ) );
$poster_id = get_post_meta( get_the_ID(), 'poster', true );
$poster_url = wp_get_attachment_image_url( $poster_id, 'full' );
$rating = get_post_meta( get_the_ID(), 'rating', true );
$imdb_rating = get_post_meta( get_the_ID(), '_imdb_rating', true );
$imdb_year = get_post_meta( get_the_ID(), '_imdb_year', true );
$imdb_release_date = get_post_meta( get_the_ID(), '_imdb_release_date', true );
$metascore_rating = get_post_meta( get_the_ID(), '_metascore_rating', true );
$imdb_runtime_total_minutes = get_post_meta( get_the_ID(), '_idmb_runtime', true );
$trailer_youtube_key = get_post_meta( get_the_ID(), '_trailer_youtube_key', true );
?>

<main class="site-main">

  <section class="block block-single has-light-bg">
    <div class="gutenberg-content">

      <h2><?php the_title(); ?></h2>

<p>IMDb: <?php echo esc_html( $imdb_rating ); ?></p>
<p>Omat pisteet: <?php echo esc_html( $rating ); ?></p>
<p>Vuosi: <?php echo esc_html( $imdb_year ); ?></p>
<p>Julkaisuajankohta: <?php echo esc_html( $imdb_release_date ); ?></p>
<p>Metascore: <?php echo esc_html( $metascore_rating ); ?></p>

<?php
// Get the number of whole hours
$idmb_runtime_hours = floor( $imdb_runtime_total_minutes / 60 );
$imdb_runtime_minutes = $imdb_runtime_total_minutes % 60;
$runtime_human_readable = $idmb_runtime_hours . ' tuntia, ' . $imdb_runtime_minutes . ' minuuttia';
?>

<p><?php echo esc_html( $runtime_human_readable ); ?></p>
<p>YouTube: <?php echo esc_html( $trailer_youtube_key ); ?></p>

<h3>Pääosissa</h3>

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

<?php
$terms = get_the_terms( $post->ID, 'director' ); ?>

<h3>Ohjaaja<?php if ( 1 < count( $terms ) ) : echo 't'; endif; ?></h4>

<?php if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) : ?>
  <ul>
    <?php foreach ( $terms as $term ) :
      $avatar_url = get_field( 'avatar', 'director_' . $term->term_id )['url'];
      ?>
      <li><?php echo esc_html( $term->name ); ?>
      <div style="width: 80px; height: 80px; border-radius: 50%; background-position: center; background-size: cover; background-image: url('<?php echo esc_url( $avatar_url ); ?>');"></div>
      </li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>

      <?php the_content();

      // Required by WordPress Theme Check, feel free to remove as it's rarely used in starter themes
      wp_link_pages( array( 'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'rollekino' ), 'after' => '</div>' ) );

      entry_footer();

      if ( get_edit_post_link() ) {
        edit_post_link( sprintf( wp_kses( __( 'Edit <span class="screen-reader-text">%s</span>', 'rollekino' ), [ 'span' => [ 'class' => [] ] ] ), get_the_title() ), '<p class="edit-link">', '</p>' );
      }

      the_post_navigation();

  		// If comments are open or we have at least one comment, load up the comment template.
      if ( comments_open() || get_comments_number() ) {
        comments_template();
      } ?>

    </div>
  </section>

</main>

<?php get_footer();
