<?php
/**
 * Default hero template file.
 *
 * This is the default hero image for page templates, called
 * 'block'. Strictly air specific.
 *
 * @Date:   2019-10-15 12:30:02
 * @Last Modified by:   Timi Wahalahti
 * @Last Modified time: 2021-02-23 13:59:45
 *
 * @package rollekino
 */

if ( ! is_singular( 'post' ) ) :
wp_reset_postdata();
$movies = [];
$post_type = 'movie';

if ( get_the_ID() === 150 ) :
  $args = [
    // 'orderby' => 'rand',
    'p' => 45952,
    'post_type' => $post_type,
    'posts_per_page' => 1,
  ];
else :
  $args = [
    'orderby' => 'rand',
    'post_type' => $post_type,
    'posts_per_page' => 1,
  ];
endif;

$query = new \WP_Query( $args );
?>

<section class="block block-hero block-hero-normal block-hero-movies<?php echo esc_attr( implode( ' ', $block_classes ) ); ?>">
  <div class="shade" aria-hidden="true"></div>

  <div class="container">
    <div class="content">
      <?php if ( is_post_type_archive( 'movie' ) ) : ?>
        <h1 class="block-title">Leffat</h1>
        <p class="block-description">Täällä ne ovat, kaikki <?php echo esc_attr( wp_count_posts( 'movie' )->publish ); ?> leffa-arviota. Selaa rauhassa.</p>
      <?php elseif ( is_home() ) : ?>
        <h1 class="block-title">Leffablogi</h1>
        <p class="block-description">Tavallisia blogikirjoituksia elokuvista ja vähän niiden vierestäkin.</p>
      <?php else : ?>
        <h1 class="block-title"><?php the_title(); ?></h1>
        <?php if ( get_the_ID() === 150 ) : ?>
          <p class="block-description block-description-quote">“If you build it, he will come.” &mdash; <a href="<?php echo esc_url( get_the_permalink( 45952 ) ); ?>">Field of dreams (1989)</a></p>
        <?php endif; ?>
      <?php endif; ?>
    </div>

    <?php if ( ! empty( $query->posts ) ) : ?>

      <?php while ( $query->have_posts() ) :
        $query->the_post();

        // Meta data
        $backdrop_url = esc_url( wp_get_attachment_url( get_post_thumbnail_id() ) );
        $poster_id = get_post_meta( get_the_ID(), 'poster', true );
        $poster_url = wp_get_attachment_image_url( $poster_id, 'full' );
        ?>

        <div class="backdrop">
          <div class="lazy" style="background-image: url('<?php echo esc_url( $backdrop_url ); ?>');"></div>
        </div>

      <?php endwhile; ?>
    <?php endif; ?>
  </div>
</section>

<?php
wp_reset_postdata();

else :
$backdrop_url = wp_get_attachment_url( get_post_thumbnail_id() );
?>

<section class="block block-hero block-hero-normal block-hero-movies<?php echo esc_attr( implode( ' ', $block_classes ) ); ?>">
  <div class="shade" aria-hidden="true"></div>

  <div class="container">
    <div class="content">
      <h1 class="block-title"><?php the_title(); ?></h1>
      <p class="block-description block-description-quote"><?php echo esc_html( get_the_date( 'j.n.Y', get_the_ID() ) ); ?> <span class="auth"><a href="https://twitter.com/rolle"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/gravatar.jpg" alt="Kasvokuva minusta, Roni Laukkarisesta" class="gravatar-small" /> @rolle</a></span></p>
    </div>

    <div class="backdrop">
      <div class="lazy" style="background-image: url('<?php echo esc_url( $backdrop_url ); ?>');"></div>
    </div>
  </div>

</section>

<?php endif;
